<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Beelab\Recaptcha2Bundle\Form\Type\RecaptchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Beelab\Recaptcha2Bundle\Validator\Constraints\Recaptcha2;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Votre prénom', 'attr' => ['placeholder' => 'Merci de saisir votre prénom']])
            ->add('lastName', TextType::class, ['label' => 'Votre nom', 'attr' => ['placeholder' => 'Merci de saisir votre nom']])

            ->add('email', EmailType::class, ['label' => 'Votre Email', 'attr' => ['placeholder' => 'Merci de saisir votre email']])
            //  ->add('roles')
            ->add('imageFile',FileType::class, ['required' => false])

            ->add('captcha', RecaptchaType::class, [
                'constraints' => new Recaptcha2(),
            ])
            ->add("submit", SubmitType::class, ['label' => "Valider", 'attr' => ['class' => 'btn btn-primary btn-md']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
