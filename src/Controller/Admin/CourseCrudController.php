<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Repository\CourseRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class CourseCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un cours')
            ->setEntityLabelInPlural('Cours');
    }

    public function configureFields(string $pageName): iterable
    {
        yield HiddenField::new('id')->hideOnForm()->hideOnIndex();

        yield TextField::new('title')
            ->setLabel('Titre');
        yield TextField::new('description')
            ->setLabel('Description');
        yield TextEditorField::new('content')
            ->setLabel('Contenu')
            ->hideOnIndex();
        yield AssociationField::new('categories')
            ->setLabel('Catégories')
            ->formatValue(function ($value, $entity) {
                $categoryNames = [];
                foreach ($entity->getCategories() as $category) {
                    $categoryNames[] = $category->getName();
                }
                return implode(', ', $categoryNames);
                })
            ->setCssClass('text-left');
        yield AssociationField::new('createdBy')
            ->setLabel('Créer par');
        yield ChoiceField::new('status')
            ->setLabel('Statut')
            ->setChoices([
                'Valider' => 'Validé',
                'Refuser' => 'Refusé',
                'En attente de validation' => 'en attente',
            ]);

        //todo: display average note
        yield TextField::new('NoteAverage');



    }


}
