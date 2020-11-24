<?php

namespace App\Service;

use Mailjet\Client;
use App\Entity\User;
use Mailjet\Resources;


class MailService
{

    private $api_key =  "1f7b3e7376ecfaa6b5536aca3996ee7f";
    private $api_secret_key = '8bb4f13bf47352a4f5d9ad7465d0e2e6';
    private $modele_key = '1959225';

//FIXME: envoie de mail ne se fait pas 

    public function send(User $to, string $object, string $content)
    {
        $mj = new Client($this->api_key, $this->api_secret_key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "hisokath12@gmail.com",
                        'Name' => "LaBoutique"
                    ],
                    'To' => [
                        [
                            'Email' => $to->getEmail(),
                            'Name' => $to->getFirstName() . ' ' . $to->getLastName()
                        ]
                    ],
                    'TemplateID' => $this->modele_key,
                    'TemplateLanguage' => true,
                    'Subject' => $object,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
