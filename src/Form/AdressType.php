<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => "Intitulé de l'adresse", 'attr' => ['placeholder' => "Intitulé de l'adresse (par exemple: Domicile , Travail ..)"]])
            ->add('firstName', TextType::class, ['label' => 'Nom', 'attr' => ['placeholder' => 'Votre prénom']])
            ->add('lastName', TextType::class, ['label' => 'Prénom', 'attr' => ['placeholder' => 'Votre nom']])
            ->add('company', TextType::class, ['label' => 'Compagnie', 'required' => false, 'attr' => ['placeholder' => 'Company']])
            ->add('adress', TextType::class, ['label' => 'Adresse', 'attr' => ['placeholder' => 'Votre adresse ']])
            ->add('postal', TextType::class, ['label' => 'Code postal', 'attr' => ['placeholder' => 'Code postal']])
            ->add('city', TextType::class, ['label' => 'Ville', 'attr' => ['placeholder' => 'Ville']])
            ->add('country', CountryType::class, ['label' => 'Pays', 'attr' => ['placeholder' => 'Pays']])
            ->add('phone', TelType::class, ['label' => 'Téléphone', 'attr' => ['placeholder' => 'Téléphone']])
            ->add("submit", SubmitType::class, ['label' => "Valider", 'attr' => ['class' => ' btn-success btn-block ']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
