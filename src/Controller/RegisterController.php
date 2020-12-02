<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailService $mail): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setToken($this->generateToken());
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Un mail de confirmation a été envoyé sur votre adresse mail');
            // $mail->send($user, 'Inscription réussi', 'Vous voila maintenant inscrit !');
            $url = $this->generateUrl('confirm_account', [
                'token' => $user->getToken()
            ]);

            $content = "Bonjour " . $user->getFirstname() . "<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site La Boutique.com .<br/><br/>";
            $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'>mettre à jour votre mot de passe</a>.";


            $mail->send($user, 'Confirmez votre adresse mail', $content);
            return $this->redirectToRoute('homepage');
            # code...
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmAccount/{token}", name="confirm_account")
     */
    public function update($token, EntityManagerInterface $em): Response
    {
        /**
         * @var user
         */
        $user = $em->getRepository(User::class)->findOneByToken($token);

        if (!$user) {
            return $this->redirectToRoute('homepage');
        }

        $now = new \DateTime('now');



        if ($now > $user->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('danger', 'Votre demande de mot de passe est expiré !');
            return $this->redirectToRoute('register');
        } else {

            if ($token === $user->getToken()) {
                $user->setToken(null);
                $user->setEnable(true);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre compte est désormais actif ! ');
            }
        }
        return $this->redirectToRoute('homepage');
    }



    /**
     * Permet de genener un token
     *@return string
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
