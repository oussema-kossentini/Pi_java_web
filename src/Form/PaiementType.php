<?php

namespace App\Form;

use App\Entity\Paiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('adresseM', null, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/\.(com|tn)$/',
                    'message' => 'L\'adresse email doit avoir un domaine'
                ]),
            ],
        ])
            ->add('date')
            ->add('cvc', null, [
                'row_attr' => ['class' => 'form-row'],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^\d{3}$/',
                        'message' => 'Le champ CVC doit contenir exactement 3 chiffres',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
