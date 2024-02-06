<?php

namespace App\Components\Drivers;

use GuzzleHttp\Client;
use App\Contracts\ToolDriverInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class OpenAiRewriter implements ToolDriverInterface
{
    private array $prompts = [
        'en' => "Rewrite this text:\n\n%s\n\n----\Rewrite text:\n",
    ];

    protected $apikey;
    protected $model = "text-davinci-003";
    protected $endpoint = "https://api.openai.com/v1/completions";

    public function __construct($apikey)
    {
        $this->apikey = $apikey;
    }

    public function parse($article,$prompt=null)
    {
        try {
            $client = new Client();
            $response = $client->request('POST', $this->endpoint, [
                'body' => json_encode([
                    'model' => $this->model,
                    'prompt' => $prompt == null ? $this->get_prompt($article) : $prompt,
                    "temperature" => 0,
                    'max_tokens' => min(
                        $this->calculate_max_tokens($article),
                        $this->get_max_tokens_for_model($this->model)
                    ),
                ]),
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apikey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $body = $response->getBody();
            $json = json_decode($body, true);
        } catch (ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents());

            return ['success' => false, 'message' => $error->error->message];
        } catch (GuzzleException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        $choices = $json['choices'];
        if (count($choices) == 0) {
            return ['success' => false, 'message' => __('common.somethingWentWrong')];
        }

        $resultText = trim($choices[0]['text']);

        return ['success' => true, 'text' => $resultText];
    }

    protected function get_prompt($inputText)
    {
        $language = app()->getLocale();
        if (!isset($this->prompts[$language])) {
            $language = 'en';
        }

        return sprintf($this->prompts[$language], $inputText);
    }

    protected function calculate_max_tokens($inputText)
    {
        // return intval(get_number_of_words_in_text($inputText) * 1.3);
        return 4000 - intval(get_number_of_words_in_text($inputText) * 1.3);
    }

    protected function get_max_tokens_for_model($model)
    {
        if ($model == 'text-davinci-002' || $model == 'text-davinci-003') {
            return 3700;
        }

        return 1700;
    }
}
