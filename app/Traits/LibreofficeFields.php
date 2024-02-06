<?php

namespace App\Traits;

trait LibreofficeFields
{
    public static function getFileds()
    {
        $array = [
            'title' => "Drivers",
            'fields' => [
                [
                    'id' => "driver",
                    'field' => "tool-options-select",
                    'placeholder' => "Driver",
                    'label' => "Driver",
                    'required' => true,
                    'options' => [['text' => "LibreOffice", 'value' => "LibreOffice"], ['text' => "ILovePDF", 'value' => "ILovePdf"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "libre_office_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter LibreOffice exe path here....",
                    'label' => "Binary Path (optional)",
                    'required' => false,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "LibreOffice"],
                ],
                [
                    'id' => "love_pdf_public_id",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter ILovePdf Public ID here....",
                    'label' => "Public ID",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,ILovePdf",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "ILovePdf"],
                ],
                [
                    'id' => "love_pdf_secret_key",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter ILovePdf secret key here....",
                    'label' => "Secret Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,ILovePdf",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "ILovePdf"],
                ],
            ],
            "default" => ['driver' => 'LibreOffice', 'libre_office_path' => '']
        ];

        return $array;
    }
}
