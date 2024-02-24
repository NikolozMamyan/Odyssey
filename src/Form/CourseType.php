<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image du course',
                'label_attr'=> [
                    'class' => 'form-label mt-4'
                ],
                'required'=> true, 
            ])
            ->add('title', TextType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'choices' => $options['categories'],
            ])
            ->add('description', TextType::class)
            ->add('content', CKEditorType::class, [
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
