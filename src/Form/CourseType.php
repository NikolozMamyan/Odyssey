<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'choices' => $options['categories'],
            ])
            ->add('description', TextType::class)
            ->add('content', TextareaType::class, [
                'attr' => ['rows' => 15], // Définit le nombre de lignes dans le textarea
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
            'categories' => [],
        ]);
    }
}
