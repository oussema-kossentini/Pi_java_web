<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Offre;
use App\Entity\Sponsors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateDebut', DateType::class, [
            'label' => 'Date de début',
            'widget' => 'single_text',
            
            'attr' => [
                'class' => 'js-datepicker',
                'autocomplete' => 'off',
            ],
        ])
        ->add('dateFin', DateType::class, [
            'label' => 'Date de fin',
            'widget' => 'single_text',
    
            'attr' => [
                'class' => 'js-datepicker',
                'autocomplete' => 'off',
            ],
        ])
        ->add('remise', IntegerType::class, [
            'label' => 'Remise (%)',
            'attr' => [
                'min' => 0,
                'max' => 100,
            ],
        ])
            ->add('sponsor', EntityType::class, [
                'class' => Sponsors::class,
                'choice_label' => 'nomSponsor',
                
              ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
