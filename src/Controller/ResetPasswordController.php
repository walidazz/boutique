<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailService;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordChangeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{


    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    /**
     * @Route("/reset/password", name="reset_password")
     */
    public function index(Request $request, MailService $mailService): Response
    {


        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }
        $mail = $request->get('email');
        if ($mail) {
            $user = $this->em->getRepository(User::class)->findOneByEmail($mail);
            if ($user) {


                $reset_password = new ResetPassword();
                $reset_password->setUser($user)
                    ->setToken(uniqid())
                    ->setCreatedAt(new \DateTime('now'));
                $this->em->persist($reset_password);
                $this->em->flush();
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $content = "Bonjour " . $user->getFirstname() . "<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site La Boutique.com .<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'>mettre à jour votre mot de passe</a>.";


                $mailService->send($user, 'Changement de mot de passe', $content);
                $this->addFlash('success', 'Vous allez recevoir dans quelques secondaire un mail avec la procédure pour réinitialier votre mot de passe.');
                return $this->redirectToRoute('homepage');
            } else {
                $this->addFlash('warning', 'Utilisateur non trouvé');
            }
        }


        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
        ]);
    }


    /**
     * @Route("/update/password/{token}", name="update_password")
     */
    public function update($token, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        /**
         * @var ResetPassword
         */
        $reset_password = $this->em->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('homepage');
        }

        $now = new \DateTime('now');



        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('danger', 'Votre demande de mot de passe est expiré !');
            return $this->redirectToRoute('reset_password');
        } else {

            $form = $this->createForm(ResetPasswordChangeType::class);


            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reset_password->getUser()->setPassword($encoder->encodePassword($reset_password->getUser(), $form->get('newPassword')->getData()));
                $this->em->persist($reset_password);
                $this->em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succes !');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('reset_password/reset_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
