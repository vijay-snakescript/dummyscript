<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class UploadController extends Controller
{
    /**
     * Uploads the file to the temporary directory
     * and returns an encrypted path to the file
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $input = $request->file(config('artisan.input_name'));
        if (!$input->isValid()) {
            return Response::json($input->getErrorMessage(), 422);
        }

        if ($input === null) {
            return $this->handleChunkInitialization();
        }

        $file = is_array($input) ? $input[0] : $input;
        $username = Auth::user()->username;
        $path = config('artisan.public_files_path', 'uploads') . '/' . $username . '/' . date('m');
        $disk = config('artisan.public_files_disk', 'public');

        $filename = sanitize_filename($file->getClientOriginalName());
        File::ensureDirectoryExists($path, 0755, true);
        if (!($newFile = $file->storeAs($path, $filename, $disk))) {
            return Response::make('Could not save file', 500, [
                'Content-Type' => 'text/plain',
            ]);
        }

        return Response::json(['url' => generateFileUrl($newFile, $disk)], 200);
    }

    // protected function generateUrl($path)
    // {
    //     $disk = config('artisan.temporary_files_disk', 'public');
    //     $url = Storage::disk($disk)->url($path);

    //     return Str::replaceFirst(url('/'), '', $url);
    // }

    /**
     * This handles the case where filepond wants to start uploading chunks of a file
     * See: https://pqina.nl/filepond/docs/patterns/api/server/
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    private function handleChunkInitialization()
    {
        $username = Auth::user()->username;
        $path = config('artisan.temporary_files_path', 'uploads') . DIRECTORY_SEPARATOR . $username . DIRECTORY_SEPARATOR . date('m');
        $disk = config('artisan.temporary_files_disk', 'public');

        $fileCreated = Storage::disk($disk)
            ->put($path, '');

        if (!$fileCreated) {
            abort(500, 'Could not create file');
        }

        return Response::make($path, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Handle a single chunk
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws FileNotFoundException
     */
    public function chunk(Request $request)
    {
        // Retrieve upload ID
        $encryptedPath = $request->input('patch');
        if (!$encryptedPath) {
            abort(400, 'No id given');
        }

        try {
            $finalFilePath = artisanCrypt()->decrypt($encryptedPath);
            $id = basename($finalFilePath);
        } catch (DecryptException $e) {
            abort(400, 'Invalid encryption for id');
        }

        // Retrieve disk
        $disk = config('artisan.temporary_files_disk', 'public');

        // Load chunks directory
        $basePath = config('artisan.chunks_path') . DIRECTORY_SEPARATOR . $id;

        // Get patch info
        $offset = $request->server('HTTP_UPLOAD_OFFSET');
        $length = $request->server('HTTP_UPLOAD_LENGTH');

        // Validate patch info
        if (!is_numeric($offset) || !is_numeric($length)) {
            abort(400, 'Invalid chunk length or offset');
        }

        // Store chunk
        Storage::disk($disk)
            ->put($basePath . DIRECTORY_SEPARATOR . 'patch.' . $offset, $request->getContent(), ['mimetype' => 'application/octet-stream']);

        $this->persistFileIfDone($disk, $basePath, $length, $finalFilePath);

        return Response::make('', 204);
    }

    /**
     * This checks if all chunks have been uploaded and if they have, it creates the final file
     *
     * @param $disk
     * @param $basePath
     * @param $length
     * @param $finalFilePath
     * @throws FileNotFoundException
     */
    private function persistFileIfDone($disk, $basePath, $length, $finalFilePath)
    {
        $storage = Storage::disk($disk);
        // Check total chunks size
        $size = 0;
        $chunks = $storage
            ->files($basePath);

        foreach ($chunks as $chunk) {
            $size += $storage
                ->size($chunk);
        }

        // Process finished upload
        if ($size < $length) {
            return;
        }

        // Sort chunks
        $chunks = collect($chunks);
        $chunks = $chunks->keyBy(function ($chunk) {
            return substr($chunk, strrpos($chunk, '.') + 1);
        });
        $chunks = $chunks->sortKeys();

        // Append each chunk to the final file
        $data = '';
        foreach ($chunks as $chunk) {
            // Get chunk contents
            $chunkContents = $storage
                ->get($chunk);

            // Laravel's local disk implementation is quite inefficient for appending data to existing files
            // To be at least a bit more efficient, we build the final content ourselves, but the most efficient
            // Way to do this would be to append using the driver's capabilities
            $data .= $chunkContents;
            unset($chunkContents);
        }
        Storage::disk($disk)->put($finalFilePath, $data, ['mimetype' => 'application/octet-stream']);
        Storage::disk($disk)->deleteDirectory($basePath);
    }

    /**
     * Takes the given encrypted filepath and deletes
     * it if it hasn't been tampered with
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function delete(Request $request)
    {
        $data = json_decode($request->getContent());
        $filePath = $data->options->metadata->path ?? null;
        $fileDisk = $data->options->metadata->disk ?? config('artisan.temporary_files_disk', 'public');
        if (Storage::disk($fileDisk)->delete($filePath)) {
            return Response::make('', 200, [
                'Content-Type' => 'text/plain',
            ]);
        }

        return Response::make('', 500, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
