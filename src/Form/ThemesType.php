<?php

namespace App\Form;

use App\Entity\Themes;
//use Doctrine\DBAL\Types\Integer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ThemesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // liste des champs de formulaire à afficher
        $builder
            ->add('name', TextType::class, [
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
            ->add('duree', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "La durée est vide",
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => "La durée doit contenir au minimum deux caractères (h)",
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
            ])
            ->add('ageMinimum', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "L'âge minimum est vide",
                    ]),
                    new Length([
                        'max' => 2,
                        'maxMessage' => "L'âge minimum doit contenir au maximum deux caractères",
                    ]),
                ],
            ])
            ->add('ageMaximum', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "L'âge maximum est vide",
                    ]),
                    new Length([
                        'max' => 2,
                        'maxMessage' => "L'âge maximum doit contenir au maximum deux caractères",
                    ]),
                ],
            ])
            ->add('nbEnfantsMinimum', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nombre d'enfants minimum est vide",
                    ]),
                    new Length([
                        'max' => 2,
                        'maxMessage' => "Le nombre d'enfants maximum doit contenir au maximum deux caractères",
                    ]),
                ],
            ])
            ->add('nbEnfantsMaximum', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nombre d'enfants maximum est vide",
                    ]),
                    new Length([
                        'max' => 2,
                        'maxMessage' => "Le nombre d'enfants maximum doit contenir au maximum deux caractères",
                    ]),
                ],
            ])  
            ->add('imageIllustration', FileType::class, [
                'constraints' => $options['data']->getId() 
                    ? [] 
                    : [ new NotBlank(['message' => "Aucune image sélectionnée"])],
                'data_class' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Themes::class,
        ]);
    }
}
