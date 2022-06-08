<?php

namespace App\Form;

use App\Entity\Reservations;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;


class ReservationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // liste des champs de formulaire à afficher
        $builder
            ->add('theme_id', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "L'idTheme est vide",
                    ])
                ],
            ])
            ->add('user_id', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "L'IdClient est vide",
                    ]),
                ],
            ])
            ->add('date_reservee', DateTimeType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'constraints' => [
                    new NotBlank([
                        'message' => "La date est vide",
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
