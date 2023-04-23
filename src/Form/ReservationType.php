<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('ville')
            ->add('num_tel')
            ->add('adresse_m')
            ->add('date_livraison')
            ->add('type_produit')
            ->add('lieu_depart')
            ->add('lieu_arrivee')
            ->add('poids')
            ->add('drone')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
