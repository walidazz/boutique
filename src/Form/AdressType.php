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
            ->add('name', TextType::class, ['attr' => ['placeholder' => "Intitulé de l'adresse (par exemple: Domicile , Travail ..)"]])
            ->add('firstName', TextType::class, ['attr' => ['placeholder' => 'Votre prénom']])
            ->add('lastName', TextType::class, ['attr' => ['placeholder' => 'Votre nom']])
            ->add('company', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'Company']])
            ->add('adress', TextType::class, ['attr' => ['placeholder' => 'Votre adresse ']])
            ->add('postal', TextType::class, ['attr' => ['placeholder' => 'Code postal']])
            ->add('city', TextType::class, ['attr' => ['placeholder' => 'Ville']])
            ->add('country', CountryType::class, ['attr' => ['placeholder' => 'Pays']])
            ->add('phone', TelType::class, ['attr' => ['placeholder' => 'Téléphone']])
            ->add("submit", SubmitType::class, ['label' => "Valider", 'attr' => ['class' => ' btn-success btn-block ']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
