<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;





class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('cin', null, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^\d{8}$/',
                    'message' => 'Le champ Cin doit contenir exactement 8 chiffres',
                ]),
            ],
        ])
            ->add('nom', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]*$/',
                        'message' => 'Nom should contain only letters',
                    ]),
                ],
            ])
            ->add('prenom', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]*$/',
                        'message' => 'Prenom should contain only letters',
                    ]),
                ],
            ])
            ->add('ville', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]*$/',
                        'message' => 'Ville should contain only letters',
                    ]),
                ],
            ])
           
            ->add('date')
            ->add('prix')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
