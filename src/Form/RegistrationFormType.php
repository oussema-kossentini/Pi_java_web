<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Captcha\Bundle\CaptchaBundle\Form\type\CaptchaType;
use MeteoConcept\HCaptchaBundle\Form\HCaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;

use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', null, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => function ($value, ExecutionContextInterface $context) {
                        if (preg_match('/[0-9]/', $value)) {
                            $context->buildViolation('Le champ nom ne doit pas contenir de chiffres')
                                ->addViolation();
                        }
                    },
                ]),
            ],
        ])
        ->add('prenom', null, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^[^0-9]+$/',
                    'message' => 'Le champ nom ne doit pas contenir de chiffres',
                ]),
            ],
            'attr' => [
                'class' => 'form-control error-red', // add the custom CSS class
            ],
        ])
        ->add('adresse_ma', null, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/\.(com|tn)$/',
                    'message' => 'L\'adresse email doit avoir un domaine'
                ]),
            ],
        ])
        ->add('num_tel', null, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^\d{8}$/',
                    'message' => 'Le champ num_tel doit contenir exactement 8 chiffres',
                ]),
            ],
        ])
        ->add('date_naissance', null, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('passwordConfirmation', PasswordType::class, [
            'row_attr' => ['class' => 'form-row'],
            'constraints' => [
                new NotBlank(),
            ],
        ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('captcha', HCaptchaType::class, [
                'label' => 'Bot verification',
                'attr' => [
                    'id' => 'hcaptcha-widget',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
