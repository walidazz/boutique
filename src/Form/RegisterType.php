<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Beelab\Recaptcha2Bundle\Form\Type\RecaptchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Beelab\Recaptcha2Bundle\Validator\Constraints\Recaptcha2;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Votre prénom', 'attr' => ['placeholder' => 'Merci de saisir votre prénom']])
            ->add('lastName', TextType::class, ['label' => 'Votre nom', 'attr' => ['placeholder' => 'Merci de saisir votre nom']])

            ->add('email', EmailType::class, ['label' => 'Votre Email', 'attr' => ['placeholder' => 'Merci de saisir votre email']])
            //  ->add('roles')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas !',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['placeholder' => 'Merci de saisir un mot de passe']],
                'second_options' => ['label' => 'Repetez votre mot de passe', 'attr' => ['placeholder' => 'Merci de répéter votre mot de passe']],
            ])
            ->add('captcha', RecaptchaType::class, [
                'constraints' => new Recaptcha2(),
            ])
            ->add("submit", SubmitType::class, ['label' => "S'inscrire", 'attr' => ['class' => 'btn btn-primary btn-md']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
