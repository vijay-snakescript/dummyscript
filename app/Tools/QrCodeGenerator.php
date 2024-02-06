<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeGenerator implements ToolInterface
{
    /**
     * Is ImageMatic Extension installed
     *
     * @var boolean
     */
    private $imagick = false;

    protected $qrFormat = 'svg';

    public function __construct()
    {
        if (extension_loaded('imagick')) {
            $this->imagick = true;
        }
    }

    public function render(Request $request, Tool $tool)
    {
        $type = 0;
        $imagick = $this->imagick;

        return view('tools.qr-code-generator', compact('tool', 'type', 'imagick'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'size' => 'required',
            'correction' => 'required',
            'style' => 'required',
            'eye' => 'required',
            'color' => 'required',
            'background_color' => 'required',
            'type' => 'required',
        ]);

        try {
            $qrCode = QrCode::size($request->size)
                ->encoding('UTF-8')
                ->errorCorrection($request->correction)
                ->style($request->input('style', 'square'))
                ->eye($request->input('eye', 'square'))
                ->margin($request->padding)
                ->color(hexToRgb($request->color)['r'], hexToRgb($request->color)['g'], hexToRgb($request->color)['b'])
                ->backgroundColor(hexToRgb($request->background_color)['r'], hexToRgb($request->background_color)['g'], hexToRgb($request->background_color)['b']);

            if ($request->color_type != 0) {
                $qrCode = $qrCode->gradient(
                    hexToRgb($request->color)['r'],
                    hexToRgb($request->color)['g'],
                    hexToRgb($request->color)['b'],
                    hexToRgb($request->color_sec)['r'],
                    hexToRgb($request->color_sec)['g'],
                    hexToRgb($request->color_sec)['b'],
                    $request->color_type
                );
            }

            $logoUrl = false;
            $qrDownload = clone $qrCode;
            if ($this->imagick) {
                $format = $request->input('format', 'svg');
                $logoUrl = $request->input('logo', false);
                if ($request->file('file')) {
                    $format = 'png';
                    $logo = tempFileUpload($request->file('file'), true, true);
                    if ($logo) {
                        $logoUrl = url($logo);
                    }
                }

                if ($logoUrl) {
                    $qrCode->format($format)->merge($logoUrl, .2, true);
                }

                $this->qrFormat = $format;
            }

            $type = $request->type;
            switch ($type) {
                case '1':
                    $response =  $qrCode->generate($request->url);
                    $preview =  $qrDownload->format($this->qrFormat)->generate($request->url);
                    break;
                case '2':
                    $vcard = "BEGIN:VCARD\nVERSION:4.0\nFN:{$request->full_name}\nEMAIL:{$request->email}\nTEL;TYPE=cell:{$request->contact} \nADR;TYPE=home:;;{$request->address}\nEND:VCARD";
                    $response =  $qrCode->generate($vcard);
                    $preview =  $qrDownload->format($this->qrFormat)->generate($vcard);
                    break;
                case '3':
                    $response =  $qrCode->generate($request->text);
                    $preview =  $qrDownload->format($this->qrFormat)->generate($request->text);
                    break;
                case '4':
                    $response =  $qrCode->email($request->email, $request->subject, $request->email_text);
                    $preview =  $qrDownload->format($this->qrFormat)->email($request->email, $request->subject, $request->email_text);
                    break;
                case '5':
                    $response =  $qrCode->SMS($request->number, $request->sms_text);
                    $preview =  $qrDownload->format($this->qrFormat)->SMS($request->number, $request->sms_text);
                    break;
                default:
                    abort(404);
                    break;
            }

            $imagick = $this->imagick;
            $results = [
                'url' => $request->url,
                'number' => $request->number,
                'sms_text' => $request->sms_text,
                'number' => $request->number,
                'email' => $request->email,
                'subject' => $request->subject,
                'email_text' => $request->email_text,
                'full_name' => $request->full_name,
                'size' => $request->size,
                'padding' => $request->padding,
                'text' => $request->text,
                'phone' => $request->contact,
                'address' => $request->address,
                'correction' => $request->correction,
                'style' => $request->style,
                'eye' => $request->eye,
                'format' => $request->format,
                'color' => $request->color,
                'color_sec' => $request->color_sec,
                'color_type' => $request->color_type,
                'background_color' => $request->background_color,
                'qrCode' => $response,
                'qrPreview' => $preview,
                'qrFormat' => $this->qrFormat,
                'logo' => $logoUrl,
                'mime' => $this->mime(),
            ];

            return view('tools.qr-code-generator', compact('results', 'tool', 'type', 'imagick'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    protected function mime()
    {
        $supportedMimes = ['eps' => 'application/eps', 'png' => 'image/png', 'svg' => 'image/svg+xml;charset=utf-8'];

        return $supportedMimes[$this->qrFormat] ?? 'image/png';
    }
}
