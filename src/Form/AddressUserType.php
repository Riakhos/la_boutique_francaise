<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre prénom.'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre prénom'
                ]                   
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre nom.'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre adresse.'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 60,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre adresse'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postal',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre code postal.'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 8,
                        'minMessage' => 'Le code postal doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le code postal ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre ville.'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'La ville doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La ville ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre pays.'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Le pays doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le pays ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre pays'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Votre téléphone',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre numéro de téléphone.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}