<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => '<strong>Choisissez votre adresse de livraison</strong>',
                'label_html' => true,
                'required'=> true,
                'class' => Address::class,
                'expanded' => true,
                'choices' => $options['addresses'],
                'attr' => [
                    'class' => 'bg-light-blue p-3',
                    'style' => 'max-width: 800px; margin: 0 auto;'
                ]

            ])
            ->add('carriers', EntityType::class, [
                'label' => '<strong>Choisissez votre transporteur de livraison</strong>',
                'label_html' => true,
                'required'=> true,
                'class' => Carrier::class,
                'expanded' => true,
                'label_html' => true,
                'attr' => [
                    'class' => 'bg-light-blue p-3',
                    'style' => 'max-width: 800px; margin: 0 auto;;'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Valider',
                'attr' => [
                    'class' => 'w-100 btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null
        ]);
    }
}