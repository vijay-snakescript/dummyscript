<?php

namespace App\Helpers\Classes;

use File;
use RuntimeException;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class GhostscriptWrapper
{
    private string $binaryPath;
    private string $device;
    private string $process_id;
    private int $margins;
    private int $dpi;
    private string $version;
    private array $files;
    private array $arguments = [];
    private ?string $outputPaperSize = null;
    private ?string $outputPassword;
    private ?string $outputDirectory;
    private ?string $outputFormat;
    private ?string $outputFilename;
    private ?bool $outputGrayscale;

    public function __construct(string $binaryPath, string $process_id)
    {
        $this->binaryPath = $binaryPath;
        $this->process_id = $process_id;
        $this->device = 'pdfwrite';
        $this->margins = 0;
        $this->version = 1.7;
        $this->dpi = 72;
        $this->files = [];
        $this->outputPassword = null;
        $this->outputDirectory = null;
        $this->outputFormat = null;
        $this->outputFilename = 'output';
        $this->outputGrayscale = null;
    }

    /**
     * Adds a file to be converted with optional page numbers, page rotation angles, and password.
     * @param string $filePath The path of the file to be converted.
     * @param string|null $pages the pages.
     * @param string|null $rotation the page rotation.
     * @param string|null $password The password for the file. Set to null if the file is not password-protected.
     * @return GhostscriptWrapper
     */
    public function addFile(string $filePath, string|null $pages, $rotation = null, ?string $password = null, $isImage = false, $postscript = null): GhostscriptWrapper
    {
        $this->files[] = [
            'path' => $filePath,
            'pages' => $pages,
            'rotation' => $rotation,
            'password' => $password,
            'isImage' => $isImage,
            'postscript' => $postscript,
        ];

        return $this;
    }

    /**
     * Sets the ghostscript drvice
     * @param string $text The text to be used as the watermark.
     * @return GhostscriptWrapper
     */
    public function setDrvice(string $device): GhostscriptWrapper
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Sets output PDF version
     * @param string $text The text to be used as the watermark.
     * @return GhostscriptWrapper
     */
    public function setVersion(string $version): GhostscriptWrapper
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Sets output paper size
     *
     * @param string $size The text to be used as the watermark.
     * @return GhostscriptWrapper
     */
    public function setOutputPaperSize(string $size): GhostscriptWrapper
    {
        $this->outputPaperSize = strtolower($size);

        return $this;
    }

    /**
     * Sets the password for the output file.
     * @param string $password The password to set for the output file.
     * @return GhostscriptWrapper
     */
    public function setOutputPassword(string $password): GhostscriptWrapper
    {
        $this->outputPassword = $password;

        return $this;
    }

    /**
     * Set the output file to grayscale
     * @return GhostscriptWrapper
     */
    public function setOutputGrayscale(): GhostscriptWrapper
    {
        $this->outputGrayscale = true;

        return $this;
    }

    /**
     * Removes the password from the output file.
     * @return GhostscriptWrapper
     */
    public function removeOutputPassword(): GhostscriptWrapper
    {
        $this->outputPassword = null;

        return $this;
    }

    /**
     * Sets the output directory where the converted files will be saved.
     * @param string $directory The output directory path.
     * @return GhostscriptWrapper
     */
    public function setOutputDirectory(string $directory): GhostscriptWrapper
    {
        $this->outputDirectory = $directory;

        return $this;
    }

    /**
     * Sets the output file format.
     * @param string $format The output file format.
     * @return GhostscriptWrapper
     */
    public function setOutputFormat(string $format): GhostscriptWrapper
    {
        $this->outputFormat = $format;

        return $this;
    }

    /**
     * Sets the output file name.
     * @param string $name The output file name.
     * @return GhostscriptWrapper
     */
    public function setOutputFilename(string $name): GhostscriptWrapper
    {
        if (!empty($name)) {
            $this->outputFilename = $name;
        }

        return $this;
    }

    /**
     * Sets argument.
     * @param string $name The output file name.
     * @return GhostscriptWrapper
     */
    public function setArgument(string $name): GhostscriptWrapper
    {
        $this->arguments[] = $name;

        return $this;
    }

    /**
     * Sets DPI.
     * @param int $dpi dots per inch
     * @return GhostscriptWrapper
     */
    public function setDPI(int $dpi): GhostscriptWrapper
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Sets margins.
     * @param string|null $margin The output file name.
     * @return GhostscriptWrapper
     */
    public function setMargins(string|null $margin): GhostscriptWrapper
    {
        $margins = 0;
        if ($margin === 'small-margin') {
            $margins = .25 * 72;
        } else if ($margin === 'big-margin') {
            $margins = .5 * 72;
        }

        $this->margins = $margins;

        return $this;
    }

    /**
     * Builds the Ghostscript command based on the added files and watermark settings.
     * @param bool $merge The output files should be saved in separate files.
     *
     * @return string The Ghostscript command.
     */
    private function buildCommand(bool $merge = false): string
    {
        $filename = !$merge ? "{$this->outputFilename}-%03d" : $this->outputFilename;
        $command = [
            $this->binaryPath,
            '-q',
            '-dNOSAFER',
            "-dCompatibilityLevel={$this->version}",
            '-dNOPAUSE',
            '-dQUIET',
            "-sDEVICE={$this->device}",
            '-dBATCH',
            "-sOutputFile=\"{$this->outputDirectory}/{$filename}.{$this->outputFormat}\"",
            ...$this->arguments,
        ];

        if ($this->outputPaperSize) {
            $size = strtolower($this->outputPaperSize);
            $command[] = "-sPAPERSIZE={$size}";
            $command[] = "-dAutoRotatePages=/PageByPage";
        }

        if ($this->outputGrayscale) {
            $command[] = '-sProcessColorModel=DeviceGray';
            $command[] = '-sColorConversionStrategy=Gray';
            $command[] = '-dOverrideICC';
        }

        foreach ($this->files as $index => $file) {
            $filePath = Str::of($file['path'])->replace('\\', '/')->toString();
            $password = $file['password'];
            $rotation = $this->getRotation($file['rotation']);
            if ($file['isImage']) {
                $command[] = "-c \"({$filePath})";
                $command[] = "<<";
                $command[] = "/PageSize 2 index viewJPEGgetsize 2 array astore";
                $command[] = "/HWResolution [ {$this->dpi} {$this->dpi} ]";
                $command[] = "/Orientation {$rotation}";
                if ($this->margins) {
                    $command[] = "/.HWMargins [{$this->margins} {$this->margins} {$this->margins} {$this->margins}]";
                }
                $command[] = ">>";
                $command[] = "setpagedevice";
                $command[] = "viewJPEG showpage\"";
            } else {
                $command[] = "-f \"{$filePath}\"";
                // $command[] = "-dFirstPage={$file['pages']}";
                // $command[] = "-dLastPage={$file['pages']}";
                // $command[] = "-c \"<<";
                // $command[] = "/Orientation {$rotation}";
                // $command[] = ">>\"";
            }

            if (!empty($password)) {
                $command[] = "-sPDFPassword={$password}";
            }
        }

        if ($this->outputPassword !== null) {
            $command[] = "-sOwnerPassword={$this->outputPassword} -sUserPassword={$this->outputPassword}";
        }

        return implode(' ', $command);
    }

    /**
     * Converts the added files to the specified output format and applies watermarks.
     * @param bool $separate
     * @return bool
     */
    public function convert($separate = false)
    {
        // Check if there are files to convert
        if (empty($this->files)) {
            throw new RuntimeException('No files to convert.');
        }

        // Check if the output directory is set
        if ($this->outputDirectory === null) {
            throw new RuntimeException('Output directory not specified.');
        }

        // Check if the output format is set
        if ($this->outputFormat === null) {
            throw new RuntimeException('Output format not specified.');
        }

        $command = $this->buildCommand($separate);
        $process = Process::fromShellCommandline($command);
        $process->run();

        if ($process->isSuccessful()) {
            return $this->output();
        } else {
            throw new RuntimeException('Failed to convert the files.');
        }
    }

    protected function getRotation($angle)
    {
        return (!$angle || $angle == 0) ? 0 : ($angle / 90);
    }

    /**
     * Read the outputDir and return files
     *
     * @return Array|bool
     */
    protected function output()
    {
        $path = Storage::disk('public')->path("temp/{$this->process_id}");
        $files = File::allFiles($path);
        $resultFiles = collect();
        foreach ($files as $file) {
            $resultFiles->push([
                'filename' => $file->getFilename(),
                'url' => url(Storage::disk('public')->url("temp/{$this->process_id}/{$file->getFilename()}")),
                'size' => $file->getSize(),
            ]);
        }

        return $resultFiles->count() == 0 ? false : $resultFiles->toArray();
    }
}
