<?php

namespace App\Service;

use Mailjet\Client;
use App\Entity\User;
use Mailjet\Resources;


class MailService
{

    private $api_key = '7e6ef355980e43affa2d37126f2c1432';
    private $api_key_secret = 'e5d00b07068104026c9f214469cf32f0';
    private $modele_key = 1960287;

    //FIXME: envoie de mail ne se fait pas 

    public function send(User $to, string $object, string $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "walidazzimani@gmail.com",
                        'Name' => "Walid"
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
