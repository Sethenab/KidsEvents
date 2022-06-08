<?php

namespace App\Form;

use App\Entity\Supplements;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use App\Repository\SupplementsRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent    ;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;


class SupplementsChoiceType extends AbstractType
{

    public function __construct(private SupplementsRepository $supplementsRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
	{
		
	}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add("suppl", ChoiceType::class, [
            'mapped' => false,
            'required' => false,
            'expanded' => true,
            'multiple' =>true,
            'label'=>' Suppléments',
            'choices' => $this->supplementsRepository->findAll(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

}