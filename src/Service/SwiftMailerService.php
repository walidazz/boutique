<?php

namespace App\Service;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SwiftMailerService extends AbstractController
{


    /**
     * @param $order
     * @param $template
     * @param $to
     */
    public function sendInvoice(Order $order, $to, $template, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Facture n° ' . $order->getReference()))
        
            ->setFrom(['hisokath12@gmail.com' => 'Walid'])
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'email/' . $template,
                    [
                        'order' => $order,
                    ]
                ),
                'text/html'
            );

        // you can remove the following code if you don't define a text version for your emails



        $mailer->send($message);
    }
}
