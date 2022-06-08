<?php

namespace App\Form;

use App\Entity\Supplements;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class SupplementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // liste des champs de formulaire à afficher
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Le Nom est vide",
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => "Le Nom doit contenir au minimum deux caractères",
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "La description est vide",
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => "La description doit contenir au minimum deux caractères",
                    ]),
                ],
            ])
            ->add('prix', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Le prix est vide",
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => "Le prix doit contenir au minimum deux caractères",
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supplements::class,
        ]);
    }
}
