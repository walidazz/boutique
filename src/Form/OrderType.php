<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('adresses', EntityType::class, [
                // looks for choices from this entity
                'class' => Adress::class,
                'label' => 'Choisissez une adresse de livraison',
                // uses the User.username property as the visible option string
                //  'choice_label' => 'username',
                'required' => true,
                'choices' => $user->getAdresses(),
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('carriers', EntityType::class, [
                // looks for choices from this entity
                'class' => Carrier::class,
                'label' => 'Choisissez votre transporteur',
                // uses the User.username property as the visible option string
                //  'choice_label' => 'username',
                'required' => true,
              //  'choices' => $user->getAdresses(),
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
