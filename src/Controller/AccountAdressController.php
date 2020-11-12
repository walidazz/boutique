<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{
    /**
     * @Route("/profile/adress", name="account_adress")
     */
    public function index(): Response
    {

        return $this->render('/account/account_adress.html.twig',);
    }

    /**
     * @Route("/profile/edit/adress/{id}", name="edit_adress")
     * @Route("/profile/add/adress", name="add_adress")
     */
    public function edit(Request $request, EntityManagerInterface $manager, Adress $adress = null): Response
    {
        if (!$adress) {
            $adress = new Adress();
            $create = true;
        } else {
            $create = false;
        }
        //FIXME:
        // if ($adress->getUser() != $this->getUser()) {
        //     return $this->redirectToRoute('account');
        // }

        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adress->setUser($this->getUser());
            $manager->persist($adress);
            $manager->flush();

            ($create) ?  $this->addFlash('success', 'Adresse ajoutée avec succes !') : $this->addFlash('success', 'Adresse modifiée avec succes !');
            return $this->redirectToRoute('account_adress');
        }

        return $this->render('/account/adress_form.html.twig', ['form' => $form->createView()]);
    }


    //FIXME: regler probleme paramConverteur

    /**
     * @Route("/profile/delete/adress/{id}", name="delete_adress")
     */
    public function delete(Adress $adress, EntityManagerInterface $em): Response
    {
        if ($adress->getUser() == $this->getUser()) {
            $em->remove($adress);
            $em->flush();
            $this->addFlash('success', 'Suppression effectués');
        }
        return $this->redirectToRoute('account_adress');
    }
}
