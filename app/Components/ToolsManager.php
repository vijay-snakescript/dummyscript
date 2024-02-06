<?php

namespace App\Components;

use App\Models\Tool;
use Illuminate\Support\Manager;
use App\Components\Drivers\ILovePdf;
use App\Components\Drivers\FreeIpApi;
use App\Components\Drivers\PDFToDocx;
use App\Components\Drivers\NullDriver;
use App\Components\Drivers\QPDFDriver;
use App\Components\Drivers\IpApiDriver;
use App\Components\Drivers\LibreOffice;
use App\Components\Drivers\MozApiDriver;
use App\Components\Drivers\OcrSpaceDriver;
use App\Components\Drivers\OpenAiRewriter;
use App\Components\Drivers\DefaultRewriter;
use App\Components\Drivers\GoogleSearchApi;
use App\Components\Drivers\GoogleVisionOcr;
use App\Components\Drivers\QpdfPagesDriver;
use App\Helpers\Classes\GhostscriptWrapper;
use App\Components\Drivers\GhostscriptDriver;
use App\Components\Drivers\TesseractOCRDriver;
use App\Components\Drivers\WhoisDomainChecker;
use App\Components\Drivers\TinypngApiCompressor;
use App\Components\Drivers\ImageCompressorDriver;
use App\Components\Drivers\ThumioScreenshotDriver;
use App\Components\Drivers\DefaultScreenshotDriver;

class ToolsManager extends Manager
{
    protected $tool;
    protected $settings;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
        $this->settings = $tool->settings;
    }

    /**
     * Null Driver throws exception
     *
     * @return NullDriver
     */
    public function createNullDriver()
    {
        return new NullDriver;
    }

    /**
     * Tesseract OCR Driver
     *
     * @return TesseractOCRDriver
     */
    public function createTesseractOCRDriver()
    {
        return new TesseractOCRDriver();
    }

    /**
     * Tesseract OCR Driver
     *
     * @return TesseractOCRDriver
     */
    public function createQpdfDriver()
    {
        return new QPDFDriver($this->tool);
    }

    /**
     * PDF 2 DOCX Driver
     *
     * @return PDFToDocx
     */
    public function createPDFToDocxDriver()
    {
        return new PDFToDocx($this->tool);
    }

    /**
     * Tesseract OCR Driver
     *
     * @return TesseractOCRDriver
     */
    public function createQpdfPagesDriver()
    {
        return new QpdfPagesDriver($this->tool);
    }

    /**
     * Tesseract OCR Driver
     *
     * @return TesseractOCRDriver
     */
    public function createGoogleVisionOcrDriver()
    {
        return new GoogleVisionOcr();
    }

    /**
     * IP-API.com Driver
     *
     * @return IpApiDriver
     */
    public function createIpApiDriver()
    {
        return new IpApiDriver();
    }

    /**
     * TinyPng.com driver Driver
     *
     * @return TinypngApiCompressor
     */
    public function createTinypngApiCompressorDriver()
    {
        return new TinypngApiCompressor($this->settings->tinypng_apikey);
    }

    /**
     * Article rewriter
     *
     * @return DefaultRewriter
     */
    public function createDefaultRewriterDriver()
    {
        return new DefaultRewriter();
    }

    /**
     * Browsershot Screenshot
     *
     * @return DefaultScreenshotDriver
     */
    public function createDefaultScreenshotDriver()
    {
        return new DefaultScreenshotDriver($this->tool);
    }

    /**
     * Thum.io Screenshot
     *
     * @return ThumioScreenshotDriver
     */
    public function createThumioScreenshotDriver()
    {
        return new ThumioScreenshotDriver($this->tool);
    }

    /**
     * OpenAi Rewriter
     *
     * @return OpenAiRewriter
     */
    public function createOpenAiRewriterDriver()
    {
        return new OpenAiRewriter($this->settings->openai_apikey);
    }

    /**
     * TinyPng.com driver Driver
     *
     * @return ImageCompressorDriver
     */
    public function createImageCompressorDriver()
    {
        return new ImageCompressorDriver();
    }

    /**
     * FreeIPAPI.com Driver
     *
     * @return FreeIpApi
     */
    public function createFreeIpApiDriver()
    {
        return new FreeIpApi();
    }

    /**
     * OCRSpace Driver
     *
     * @return OcrSpaceDriver
     */
    public function createOcrSpaceDriver()
    {
        return new OcrSpaceDriver($this->settings->ocr_space_key);
    }

    /**
     * whois Driver
     *
     * @return WhoISApiDriver
     */
    public function createWhoisDomainCheckerDriver()
    {
        return new WhoisDomainChecker();
    }

    /**
     * Moz Driver
     *
     * @return MozApiDriver
     */
    public function createMozApiDriver()
    {
        return new MozApiDriver($this->tool);
    }

    /**
     * Google Search Driver
     *
     * @return GoogleSearchApiDriver
     */
    public function createGoogleSearchApiDriver()
    {
        return new GoogleSearchApi($this->tool);
    }

    /**
     * Ghostscript
     *
     * @return GhostscriptDriver
     */
    public function createGhostscriptDriver()
    {
        return new GhostscriptDriver($this->tool);
    }

    /**
     * Libre Office
     *
     * @return LibreOffice
     */
    public function createLibreOfficeDriver()
    {
        return new LibreOffice($this->tool);
    }

    /**
     * I Love Pdf
     *
     * @return ILovePdf
     */
    public function createILovePdfDriver()
    {
        return new ILovePdf($this->tool);
    }


    /**
     * Get a driver instance.
     *
     * @param  string|null  $name
     * @return mixed
     */
    public function channel($name = null)
    {
        return $this->driver($name);
    }

    /**
     *
     * Get the default SMS driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->settings?->driver ?? 'null';
    }
}
