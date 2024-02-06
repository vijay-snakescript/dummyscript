<?php

namespace App\Helpers\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Example Usage
 *
 * Replace with the actual path to the LibreOffice binary
 * $wrapper = new LibreOfficeWrapper($binaryPath = '/usr/bin/soffice');

 * $wrapper->addFile('/path/to/file1.docx');
 * $wrapper->addFile('/path/to/file2.xlsx');
 * $wrapper->addFile('/path/to/file3.odt');

 * $result = $wrapper->convert($outputFormat = 'pdf');

 * if ($result !== false) {
 *     echo 'Conversion successful. Output file: ' . $result;
 * } else {
 *     echo 'Conversion failed.';
 * }
 */

class LibreOfficeWrapper
{
    private $binaryPath;
    private $files;
    private $timeout;
    private $output;
    private $options = [];
    private $filter = null;

    /**
     * Constructor.
     *
     * @param string|null $binaryPath The path to the LibreOffice binary.
     */
    public function __construct($binaryPath = null, $timeout = 60)
    {
        $this->binaryPath = $binaryPath;
        $this->timeout = $timeout;
        $this->files = new Collection();
    }

    /**
     * Add a file to the collection.
     *
     * @param string $filePath The path of the file to add.
     */
    public function addFile($filePath)
    {
        $this->files->push($filePath);

        return $this;
    }

    /**
     * Set the path to the LibreOffice binary.
     *
     * @param string $binaryPath The path to the LibreOffice binary.
     */
    public function setBinaryPath($binaryPath)
    {
        $this->binaryPath = $binaryPath;

        return $this;
    }

    /**
     * Set the path to the LibreOffice binary.
     *
     * @param string $binaryPath The path to the LibreOffice binary.
     */
    public function setOutputPath($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @param string|null $extension
     *
     * @return array|mixed
     */
    private function getAllowedConverter($extension = null)
    {
        $allowedConverter = [
            '' => ['pdf'],
            'pptx' => ['pdf'],
            'ppt' => ['pdf'],
            'pdf' => ['pdf'],
            'docx' => ['pdf', 'odt', 'html'],
            'doc' => ['pdf', 'odt', 'html'],
            'wps' => ['pdf', 'odt', 'html'],
            'dotx' => ['pdf', 'odt', 'html'],
            'docm' => ['pdf', 'odt', 'html'],
            'dotm' => ['pdf', 'odt', 'html'],
            'dot' => ['pdf', 'odt', 'html'],
            'odt' => ['pdf', 'html'],
            'xlsx' => ['pdf'],
            'xls' => ['pdf'],
            'png' => ['pdf'],
            'jpg' => ['pdf'],
            'jpeg' => ['pdf'],
            'jfif' => ['pdf'],
            'PPTX' => ['pdf'],
            'PPT' => ['pdf'],
            'PDF' => ['pdf'],
            'DOCX' => ['pdf', 'odt', 'html'],
            'DOC' => ['pdf', 'odt', 'html'],
            'WPS' => ['pdf', 'odt', 'html'],
            'DOTX' => ['pdf', 'odt', 'html'],
            'DOCM' => ['pdf', 'odt', 'html'],
            'DOTM' => ['pdf', 'odt', 'html'],
            'DOT' => ['pdf', 'odt', 'html'],
            'ODT' => ['pdf', 'html'],
            'XLSX' => ['pdf'],
            'XLS' => ['pdf'],
            'PNG' => ['pdf'],
            'JPG' => ['pdf'],
            'JPEG' => ['pdf'],
            'JFIF' => ['pdf'],
            'Pptx' => ['pdf'],
            'Ppt' => ['pdf'],
            'Pdf' => ['pdf'],
            'Docx' => ['pdf', 'odt', 'html'],
            'Doc' => ['pdf', 'odt', 'html'],
            'Wps' => ['pdf', 'odt', 'html'],
            'Dotx' => ['pdf', 'odt', 'html'],
            'Docm' => ['pdf', 'odt', 'html'],
            'Dotm' => ['pdf', 'odt', 'html'],
            'Dot' => ['pdf', 'odt', 'html'],
            'Ddt' => ['pdf', 'html'],
            'Xlsx' => ['pdf'],
            'Xls' => ['pdf'],
            'Png' => ['pdf'],
            'Jpg' => ['pdf'],
            'Jpeg' => ['pdf'],
            'Jfif' => ['pdf'],
            'rtf'  => ['docx', 'txt', 'pdf'],
            'txt'  => ['pdf', 'odt', 'doc', 'docx', 'html'],
        ];

        if (null !== $extension) {
            if (isset($allowedConverter[$extension])) {
                return $allowedConverter[$extension];
            }

            return [];
        }

        return $allowedConverter;
    }

    /**
     * Enable linearization for the output file.
     */
    public function setOption(string $command)
    {
        $this->options[] = $command;

        return $this;
    }

    /**
     * Enable linearization for the output file.
     */
    public function useFilter($filter = null)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Convert the files to the specified output format.
     *
     * @param string $outputFormat The output format to convert the files to.
     *
     * @return Collection Returns the output file path if successful, false otherwise.
     */
    public function convert($outputFormat)
    {
        $command = $this->buildCommand($outputFormat);

        try {
            $process = Process::fromShellCommandline($command);
            $process
                ->setTimeout($this->timeout)
                ->mustRun();

            // Conversion successful, return the output file path
            $result = collect();
            $this->files->each(function ($file) use ($outputFormat, &$result) {
                $filepath = $this->output . '/' . pathinfo($file, PATHINFO_FILENAME) . '.' . $outputFormat;
                if (file_exists($filepath)) {
                    $result->push([
                        'path' => $filepath,
                        'filename' => pathinfo($file, PATHINFO_FILENAME) . '.' . $outputFormat,
                        'size' => File::size($filepath),
                    ]);
                }
            });

            return $result;
        } catch (ProcessFailedException $exception) {
            // Conversion failed
            throw new \Exception('LibreOffice failed: ' . $exception->getMessage());
        }
    }

    /**
     * Build Command
     *
     * Builds the qpdf command based on the specified options.
     *
     * @return string - The built qpdf command.
     */
    public function buildCommand($outputFormat)
    {
        // Build the command to convert the files
        $command = $this->binaryPath ?? 'soffice';
        $command .= ' --headless --invisible --convert-to ' . $outputFormat;
        if (!empty($this->filter)) {
            $command .= ':"' . $this->filter . '"';
        }
        $command .= ' --outdir ' . $this->output;

        if (!empty($this->options)) {
            $command .= ' ' . implode(' ', $this->options);
        }

        // Add input files to the command
        $this->files->each(function ($file) use (&$command) {
            $command .= ' ' . escapeshellarg($file);
        });

        return $command;
    }
}
