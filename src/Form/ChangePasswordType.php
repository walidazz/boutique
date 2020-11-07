<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])
            ->add('firstName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon adresse prénom'
            ])
            ->add('lastName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon nom'
            ])
            //  ->add('roles')
            ->add('oldPassword', PasswordType::class, [
                "mapped" => false,
                'label' => 'Mon  mot de passe actuel',
                'attr' => [
                    "placeholder" => "Veuillez saisir votre mot de passe actuel"
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passes ne correspondent pas !',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mon nouveau mot de passe', 'attr' => ['placeholder' => 'Merci de saisir un nouveau mot de passe']],
                'second_options' => ['label' => 'Repetez votre nouveau mot de passe', 'attr' => ['placeholder' => 'Merci de répéter votre nouveau mot de passe']],
            ])
            ->add("submit", SubmitType::class, ['label' => "Mettre à jour", 'attr' => ['class' => 'btn btn-primary btn-md']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
