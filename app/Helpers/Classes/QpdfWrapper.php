<?php

namespace App\Helpers\Classes;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Example usage
 *
 * $qpdfPath = '/path/to/qpdf'; // Specify the path to the qpdf binary
 * $imageFiles = [
 *      '/path/to/image1.jpg',
 *     '/path/to/image2.png',
 *    '/path/to/image3.jpeg'
 * ]; // Specify the paths to the image files
 * $outputFile = '/path/to/output.pdf'; // Specify the path to the output PDF file
 *
 * $qpdf = new QpdfWrapper($qpdfPath);
 *
 * foreach ($imageFiles as $imageFile) {
 *    $qpdf->addFile($imageFile);
 * }
 *
 * $qpdf->setOutputFile($outputFile);
 * $qpdf->setCompression();
 * $qpdf->setOutputEncryption('password123');
 *
 * if ($qpdf->execute()) {
 *     echo 'Images converted to PDF successfully.';
 * } else {
 *     echo 'Failed to convert images to PDF.';
 * }
 */

class QpdfWrapper
{
    private $qpdfPath;
    private $files = [];
    private $outputFile;
    private $compress = false;
    private $compressionQuality;
    private $outputPassword = null;
    private $pageSize = null;
    private $rotation = [];
    private $pageOrientation = null;
    private $pageMargins = null;
    private $options = [];

    public function __construct($qpdfPath = 'qpdf')
    {
        $this->qpdfPath = $qpdfPath;
    }

    /**
     * Add File
     *
     * Adds a file to be processed with optional page numbers, rotation, and password.
     *
     * @param string $filePath - The path to the input file.
     * @param array $pageNumbers - An array of page numbers to include in the output (optional).
     * @param array $rotationByPage - An array of page numbers and their corresponding rotation angles (optional).
     * @param string $password - The password for the input file if it is password-protected (optional).
     * @return $this
     */
    public function addFile($filePath, $pageNumbers = [], $password = '')
    {
        $file = [
            'path' => $filePath,
            'pages' => $pageNumbers,
            'password' => $password
        ];

        $this->files[] = $file;

        return $this;
    }

    /**
     * Set Output File
     *
     * Sets the path of the output file.
     *
     * @param string $filePath - The path to the output file.
     * @return $this
     */
    public function setOutputFile($filePath)
    {
        $this->outputFile = $filePath;

        return $this;
    }

    /**
     * Set Compression
     *
     * Enables compression for the output file.
     *
     * @return $this
     */
    public function setCompression()
    {
        $this->compress = true;

        return $this;
    }

    /**
     * Set margin orientation
     *
     * Page margin for the output file.
     *
     * @return $this
     */
    public function setPageMargins($margin)
    {
        if ($margin == 'no-margin') {
            $this->pageMargins = 0;
        } else if ($margin == 'small-margin') {
            $this->pageMargins = 36;
        } else if ($margin == 'big-margin') {
            $this->pageMargins = 72;
        }

        return $this;
    }

    /**
     * Set page orientation
     *
     * Page orientation for the output file.
     *
     * @return $this
     */
    public function setPageOrientation($orientation)
    {
        $this->pageOrientation = $orientation;

        return $this;
    }

    /**
     * Set page size
     *
     * Page size for the output file.
     *
     * @return $this
     */
    public function setPageSize($size)
    {
        $this->pageSize = $size;

        return $this;
    }

    /**
     * Set Compression Quality
     *
     * Sets the quality of compression for the output file.
     *
     * @param int $quality - The quality of compression (1-100).
     * @return $this
     */
    public function setCompressionQuality($quality)
    {
        $this->compressionQuality = $quality;

        return $this;
    }

    /**
     * Enable linearization for the output file.
     */
    public function setLinearize()
    {
        $this->options[] = '--linearize';

        return $this;
    }

    /**
     * Set Output Encryption
     *
     * Sets the password for the output file encryption.
     *
     * @param string $password - The password for the output file.
     * @return $this
     */
    public function setOutputEncryption($password)
    {
        $this->outputPassword = $password;

        return $this;
    }

    /**
     * Set Output Encryption
     *
     * Sets the password for the output file encryption.
     *
     * @param array $pages - The password for the output file.
     * @return $this
     */
    public function setPagesRotation(array $pages)
    {
        $this->rotation = $pages;

        return $this;
    }

    /**
     * Remove Password
     *
     * Removes the password from the input file.
     *
     * @param string $password - The password for the input file.
     * @return $this
     */
    public function removePassword($password)
    {
        $file = [
            'password' => $password
        ];

        $this->files[] = $file;

        return $this;
    }

    /**
     * Execute
     *
     * Executes the qpdf command.
     *
     * @return bool - Whether the command execution was successful or not.
     */
    public function execute()
    {
        $command = $this->buildCommand();

        try {
            $process = Process::fromShellCommandline($command);
            $process->mustRun();

            return true;
        } catch (ProcessFailedException $exception) {
            info($exception->getMessage());
            return false;
        }
    }

    /**
     * Build Command
     *
     * Builds the qpdf command based on the specified options.
     *
     * @return string - The built qpdf command.
     */
    private function buildCommand()
    {
        $command = $this->qpdfPath ?? 'qpdf';
        $command .= " --empty --";

        // Add options to the command
        if (!empty($this->options)) {
            $command .= ' ' . implode(' ', $this->options);
        }

        $command .= " --pages";
        foreach ($this->files as $file) {
            $command .= " " . escapeshellarg($file['path']);

            if (!empty($file['password'])) {
                $command .= " --password={$file['password']}";
            }

            if (!empty($file['pages'])) {
                $command .= " {$this->getPageRange($file['pages'])}";
            }
        }
        $command .= " --";

        if (!empty($this->rotation)) {
            foreach ($this->rotation as $pageNumber => $rotation) {
                $command .= " --rotate={$rotation}:{$pageNumber}";
            }
        }

        $command .= " --";

        if ($this->compress) {
            $command .= " --compress";
        }

        if (!empty($this->compressionQuality)) {
            $command .= " --compress-streams=--quality={$this->compressionQuality}";
        }

        if (!empty($this->outputPassword)) {
            $password = escapeshellarg($this->outputPassword);
            $command .= " --encrypt {$password} {$password} 256 --";
        }

        if (!empty($this->pageSize)) {
            // $command .= " --pagesize={$this->pageSize}";
        }

        if (!empty($this->pageOrientation)) {
            $command .= " --orientation={$this->pageOrientation}";
        }

        if (!empty($this->pageMargins)) {
            $command .= " --margins={$this->pageMargins}:{$this->pageMargins}:{$this->pageMargins}:{$this->pageMargins}";
        }

        if (!empty($this->outputFile)) {
            $command .= " " . escapeshellarg($this->outputFile);
        }

        return $command;
    }

    /**
     * Get Page Range
     *
     * Converts the page numbers array into a qpdf-compatible page range string.
     *
     * @param array $pageNumbers - The page numbers array.
     * @return string - The page range string.
     */
    private function getPageRange($pageNumbers)
    {
        $ranges = [];
        $start = $pageNumbers[0];
        $end = $pageNumbers[0];

        for ($i = 1; $i < count($pageNumbers); $i++) {
            if ($pageNumbers[$i] == $end + 1) {
                $end = $pageNumbers[$i];
            } else {
                $ranges[] = $this->formatPageRange($start, $end);
                $start = $pageNumbers[$i];
                $end = $pageNumbers[$i];
            }
        }

        $ranges[] = $this->formatPageRange($start, $end);

        return implode(",", $ranges);
    }

    /**
     * Format Page Range
     *
     * Formats the start and end page numbers into a page range string.
     *
     * @param int $start - The start page number.
     * @param int $end - The end page number.
     * @return string - The formatted page range string.
     */
    private function formatPageRange($start, $end)
    {
        if ($start == $end) {
            return $start == 'all' ? '' : "$start";
        } else {
            return "$start-$end";
        }
    }
}
