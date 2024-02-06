<?php

namespace App\Helpers\Classes;

use Exception;
use Illuminate\Support\Str;
use setasign\Fpdi\Tcpdf\Fpdi;
use Intervention\Image\Facades\Image;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;

class PDFManager extends Fpdi
{
    private $watermarkFontFamily;
    private $watermarkFontSize;
    private $watermarkRotation;
    private $watermarkPositionX;
    private $watermarkPositionY;
    private $watermarkOnTop;
    private $watermarkPlacement;
    private $watermarkText;
    private $watermarkImage;
    private $watermarkImageFile;
    private $imageScaleFactor = 2.5;
    private $watermarkAlpha = .5;
    private $watermarkColor;
    private $pageHeight;
    private $pageWidth;

    /**
     * Set the font family for the watermark.
     *
     * @param string $fontFamily
     */
    public function setWatermarkFontFamily(string $fontFamily)
    {
        $fontFamily = Str::slug($fontFamily);
        $this->watermarkFontFamily = \TCPDF_FONTS::addTTFfont(resource_path("themes/default/assets/fonts/{$fontFamily}.ttf"), 'TrueType', '', 96);
    }

    /**
     * Set the font size for the watermark.
     *
     * @param int $fontSize
     */
    public function setWatermarkFontSize(int $fontSize)
    {
        $this->watermarkFontSize = $fontSize;
    }

    /**
     * Set the rotation angle for the watermark.
     *
     * @param int $rotation
     */
    public function setWatermarkRotation(int $rotation)
    {
        $this->watermarkRotation = $rotation;
    }

    /**
     * Set the position of the watermark on the page.
     *
     * @param string $position
     */
    public function setWatermarkPosition(string $position)
    {
        $this->watermarkPlacement = $position;
    }

    /**
     * Set whether the watermark should be placed on top of the PDF.
     *
     * @param bool $watermarkOnTop
     */
    public function setWatermarkOnTop(bool $watermarkOnTop)
    {
        $this->watermarkOnTop = $watermarkOnTop;
    }

    /**
     * Calculate the position of the watermark based on the given position string.
     *
     * @param string $position
     */
    private function calculateWatermarkPosition()
    {
        if (!empty($this->watermarkText)) {
            $this->textWatermarkPosition();
        } elseif (!empty($this->watermarkImage)) {
            $this->imageWatermarkPosition();
        }
    }

    /**
     * Calculate the position of the watermark based on the given position string.
     */
    private function imageWatermarkPosition()
    {
        if (file_exists($this->watermarkImage)) {
            $image = Image::make($this->watermarkImage)->encode('png');
            if ($this->watermarkRotation > 0) {
                $image->rotate($this->watermarkRotation);
            }

            $imageWidth = $this->pixelsToUnits($image->width()) / $this->imageScaleFactor;
            $imageHeight = $this->pixelsToUnits($image->height()) / $this->imageScaleFactor;

            $centerX = ($this->pageWidth - $imageWidth) / 2;
            $centerY = ($this->pageHeight - $imageHeight) / 2;
            $right = $this->pageWidth - $imageWidth - ($this->rMargin / 2);
            $bottom = ($this->pageHeight - ($imageHeight + ($this->rMargin / 2)));

            // Update the rectangle center coordinates based on the placement
            switch ($this->watermarkPlacement) {
                case 'center':
                    $textPositionX = $centerX;
                    $textPositionY = $centerY;
                    break;
                case 'top-left':
                    $textPositionX = $this->rMargin / 2;
                    $textPositionY = $this->rMargin / 2;
                    break;
                case 'top-right':
                    $textPositionX = $right;
                    $textPositionY = $this->rMargin / 2;
                    break;
                case 'top-center':
                    $textPositionX = $centerX;
                    $textPositionY = $this->rMargin / 2;
                    break;
                case 'bottom-left':
                    $textPositionX = $this->lMargin / 2;
                    $textPositionY = $bottom - $this->bMargin / 2;
                    break;
                case 'bottom-right':
                    $textPositionX = $right;
                    $textPositionY = $bottom - $this->bMargin / 2;
                    break;
                case 'bottom-center':
                    $textPositionX = $centerX;
                    $textPositionY = $bottom - $this->bMargin / 2;
                    break;
                default:
                    throw new Exception(__('tools.invalidWatermarkPlacement'));
            }

            $this->watermarkPositionX = $textPositionX;
            $this->watermarkPositionY = $textPositionY;
            $this->watermarkImageFile = $image;
        }
    }

    /**
     * Calculate the position of the watermark based on the given position string.
     */
    private function textWatermarkPosition()
    {
        $textWidth = $this->GetStringWidth($this->watermarkText, $this->watermarkFontFamily, '', $this->watermarkFontSize);
        $textHeight = $this->GetStringHeight($textWidth, $this->watermarkFontSize);

        // Convert the rotation angle from degrees to radians
        $angle = deg2rad($this->watermarkRotation);
        $textWidthEstimated = $textWidth * abs(cos($angle)) + $textHeight * abs(sin($angle));
        $textHeightEstimated = $textHeight * abs(cos($angle)) + $textWidth * abs(sin($angle));

        $xPos = 0;
        $yPos = 0;

        if ($this->watermarkRotation > 0 && $this->watermarkRotation <= 90) {
            $xPos = 0;
            $yPos = $textHeightEstimated;
        } else if ($this->watermarkRotation == 225) {
            $xPos = $textWidthEstimated;
            $yPos = 0;
        } else if ($this->watermarkRotation > 90 && $this->watermarkRotation < 270) {
            $xPos = $textWidthEstimated;
            $yPos = $textHeightEstimated;
        } else if ($this->watermarkRotation >= 270) {
            $xPos = $textWidthEstimated;
            $yPos = 0;
        }

        $centerX = $xPos + ($this->pageWidth - $textWidthEstimated) / 2;
        $centerY = $yPos + ($this->pageHeight - $textHeightEstimated) / 2;
        $right = $xPos + $this->pageWidth - $textWidthEstimated - ($this->rMargin / 2);
        $bottom = $yPos + ($this->pageHeight - ($textHeightEstimated + ($this->rMargin / 2)));

        // Update the rectangle center coordinates based on the placement
        switch ($this->watermarkPlacement) {
            case 'center':
                $textPositionX = $centerX;
                $textPositionY = $centerY;
                break;
            case 'top-left':
                $textPositionX = $xPos + $this->rMargin / 2;
                $textPositionY = $yPos + $this->rMargin / 2;
                break;
            case 'top-right':
                $textPositionX = $right;
                $textPositionY = $yPos + $this->rMargin / 2;
                break;
            case 'top-center':
                $textPositionX = $centerX;
                $textPositionY = $yPos + $this->rMargin / 2;
                break;
            case 'bottom-left':
                $textPositionX = $xPos + $this->lMargin / 2;
                $textPositionY = $bottom - $this->bMargin / 2;
                break;
            case 'bottom-right':
                $textPositionX = $right;
                $textPositionY = $bottom - $this->bMargin / 2;
                break;
            case 'bottom-center':
                $textPositionX = $centerX;
                $textPositionY = $bottom - $this->bMargin / 2;
                break;
            default:
                throw new Exception(__('tools.invalidWatermarkPlacement'));
        }

        $this->watermarkPositionX = $textPositionX;
        $this->watermarkPositionY = $textPositionY;
    }

    /**
     * Override the _putinfo method to set custom PDF metadata.
     */
    protected function _putinfo()
    {
        $oid = $this->_newobj();
        $out = '<<';
        $prev_isunicode = $this->isunicode;
        if ($this->docinfounicode) {
            $this->isunicode = true;
        }
        if (!\TCPDF_STATIC::empty_string($this->title)) {
            $out .= ' /Title ' . $this->_textstring($this->title, $oid);
        }
        if (!\TCPDF_STATIC::empty_string($this->author)) {
            $out .= ' /Author ' . $this->_textstring($this->author, $oid);
        }
        if (!\TCPDF_STATIC::empty_string($this->subject)) {
            $out .= ' /Subject ' . $this->_textstring($this->subject, $oid);
        }
        if (!\TCPDF_STATIC::empty_string($this->keywords)) {
            $out .= ' /Keywords ' . $this->_textstring($this->keywords, $oid);
        }
        if (!\TCPDF_STATIC::empty_string($this->creator)) {
            $out .= ' /Creator ' . $this->_textstring($this->creator, $oid);
        }
        $this->isunicode = $prev_isunicode;
        $out .= ' /Producer ' . $this->_textstring("\115\x6f\x6e\163\x74\x65\162\x54\x6f\157\154\163\x20\166" . setting("\x76\x65\162\x73\151\157\156") . "\x20\50\x68\164\x74\160\163\x3a\x2f\57\x62\x69\164\x2e\154\x79\57\155\x6f\x6e\x73\x74\145\x72\164\157\157\x6c\163\51", $oid);
        $out .= ' /CreationDate ' . $this->_datestring(0, $this->doc_creation_timestamp);
        $out .= ' /ModDate ' . $this->_datestring(0, $this->doc_modification_timestamp);
        $out .= ' /Trapped /False';
        $out .= ' >>';
        $out .= "\n" . 'endobj';
        $this->_out($out);

        return $oid;
    }

    /**
     * set a watermark text.
     *
     * @param string $text
     * @throws Exception if watermark properties are not set
     */
    public function setWatermarkText(string $text)
    {
        $this->watermarkText = $text;
    }

    /**
     * set a watermark image.
     *
     * @param string $path
     * @throws Exception if watermark properties are not set
     */
    public function setWatermarkImage(string $path)
    {
        $this->watermarkImage = $path;
    }

    /**
     * set a watermark transparancy.
     *
     * @param $alpha
     * @throws Exception if watermark properties are not set
     */
    public function setWatermarkAlpha($alpha = 0.5)
    {
        $this->watermarkAlpha = $alpha;
    }

    /**
     * set a watermark color.
     *
     * @param int $r
     * @param int $g
     * @param int $b
     * @throws Exception if watermark properties are not set
     */
    public function setWatermarkColor($r, $g, $b)
    {
        $this->watermarkColor = [$r, $g, $b];
    }


    public function Footer()
    {
    }

    /**
     * Add an underlay watermark to the PDF.
     */
    public function Header()
    {
        if (is_null($this->watermarkPositionX) || is_null($this->watermarkPositionY)) {
            throw new Exception('Text watermark properties are not set.');
        }

        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->setAutoPageBreak(false, 0);

        if (!$this->watermarkOnTop) {
            $this->textWatermark();
            $this->imageWatermark();
        }

        $this->setAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }

    /**
     * Add an overlay watermark to the PDF.
     */
    public function addWatermark()
    {
        if (is_null($this->watermarkPositionX) || is_null($this->watermarkPositionY)) {
            throw new Exception('Text watermark properties are not set.');
        }
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->setAutoPageBreak(false, 0);

        if ($this->watermarkOnTop === true) {
            $this->textWatermark();
            $this->imageWatermark();
        }

        $this->setAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }

    /**
     * Add an image watermark to the PDF.
     */
    private function imageWatermark()
    {
        $this->StartTransform();
        $this->watermarkPositionX = $this->watermarkPositionX;
        $this->watermarkPositionY = $this->watermarkPositionY;
        if ($this->watermarkImageFile instanceof \Intervention\Image\Image) {
            $this->SetAlpha($this->watermarkAlpha);
            $x = $this->GetX();
            $y = $this->GetY();
            $scale = $this->getImageScale();
            $width = $this->pixelsToUnits($this->watermarkImageFile->width()) / $this->imageScaleFactor;
            $height = $this->pixelsToUnits($this->watermarkImageFile->height()) / $this->imageScaleFactor;

            // Move to the watermark position
            $this->setImageScale($this->imageScaleFactor);
            $this->SetXY($this->watermarkPositionX, $this->watermarkPositionY);
            $this->Image('@' . $this->watermarkImageFile->stream('png'), $this->watermarkPositionX, $this->watermarkPositionY, $width, $height);
            $this->SetXY($x, $y);
            $this->setImageScale($scale);
        }
        $this->StopTransform();
    }

    /**
     * Add text watermark to the PDF.
     */
    private function textWatermark()
    {
        if (!empty($this->watermarkText)) {
            if (!empty($this->watermarkText)) {
                $this->SetFont($this->watermarkFontFamily, 'B', $this->watermarkFontSize);
                if (is_array($this->watermarkColor) && count($this->watermarkColor) === 3) {
                    $this->SetTextColor(...$this->watermarkColor);
                }
                $this->SetAlpha($this->watermarkAlpha);
                $this->StartTransform();
                $this->Rotate($this->watermarkRotation, $this->watermarkPositionX, $this->watermarkPositionY);
                $this->Text($this->watermarkPositionX, $this->watermarkPositionY, $this->watermarkText);
                // $this->setXY($this->watermarkPositionX, $this->watermarkPositionY);
                // $this->Cell(0, 0, $this->watermarkText, 0);
                $this->StopTransform();
                $this->Rotate(0);
                $this->SetAlpha(1);
            }
        }
    }

    /**
     * Import an existing PDF file.
     *
     * @param string $inputFile Path to the input PDF file
     * @throws Exception if the input file is not found or cannot be opened
     */
    public function importPdf(string $inputFile)
    {
        if (!file_exists($inputFile)) {
            throw new Exception('Input PDF file not found.');
        }

        try {
            $pageCount = $this->setSourceFile($inputFile);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $this->importPage($pageNo);
                $originalPageSize = $this->getTemplateSize($templateId);
                $orientation = $originalPageSize['orientation'];
                $this->pageWidth = $originalPageSize['width'];
                $this->pageHeight = $originalPageSize['height'];
                $this->calculateWatermarkPosition();

                // Set the page size of the current page to match the original page size
                $this->AddPage($orientation, [$originalPageSize['width'], $originalPageSize['height']]);
                $this->useTemplate($templateId);
                $this->addWatermark();
            }
        } catch (CrossReferenceException $th) {
            throw new Exception(__('tools.compressionNotSupported'));
        } catch (Exception $e) {
            throw new Exception(__('common.somethingWentWrong'));
        }
    }
}
