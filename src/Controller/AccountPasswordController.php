<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/profile/changePassword", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        $oldPassword = $form->get('oldPassword')->getData();
        $newPassword = $form->get('newPassword')->getData();
        if ($form->isSubmitted() && $form->isValid()) {

            if ($encoder->isPasswordValid($user, $oldPassword)) {
                $user->setPassword($encoder->encodePassword($user, $newPassword));
                $em->flush();
                $this->addFlash('success', "Votre mot de passe a bien été mise à jour !");
                return $this->redirectToRoute('account');
            }
        }


        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
