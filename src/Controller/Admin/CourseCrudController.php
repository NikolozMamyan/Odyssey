<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Repository\CourseRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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

    /**
     * Returns the class for displaying in the view
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    /**
     * Configure actions available
     *
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);
    }

    /**
     * Configure options display view
     *
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setEntityLabelInSingular('un cours')
            ->setEntityLabelInPlural('Cours');
    }

    /**
     * Configures the fields to display in index view and form
     *
     * @param string $pageName
     * @return iterable
     */
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



    }

}
