<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReclamationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $possibleWords = ['livraison', 'paiement', 'service','autre'];
        $builder
       //     ->add('type')
         //   ->add('description')
            ->add('date')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'livraison' => 'livraison',
                    'paiement' => 'paiement',
                    'autre' => 'autre',
                    'service' => 'service',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a type',
                    ]),
                ],
            ])
            






            
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description',
                    ]),
                ],
            ])
            


            
            //->add('email')
            ->add('email', null, [
                'row_attr' => ['class' => 'form-row'],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/\.(com|tn)$/',
                        'message' => 'L\'adresse email doit avoir un domaine'
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
