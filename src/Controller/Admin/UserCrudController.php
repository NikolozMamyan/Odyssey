<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserCrudController extends AbstractCrudController
{

    /**
     * Returns the class for displaying in the view
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
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
            ->setEntityLabelInSingular('un utilisateur')
            ->setEntityLabelInPlural('Utilisateurs');
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
        yield TextField::new('email')
            ->setLabel('email');
        yield TextField::new('firstNameUser')
            ->setLabel('Nom');
        yield TextField::new('lastNameUser')
            ->setLabel('Prénom');
        yield TextField::new('password')
            ->hideOnIndex()
            ->setLabel('Password')
            ->setFormType(PasswordType::class);
        yield DateField::new('dateRegisterUser')
            ->setLabel('Membre depuis');
        yield AssociationField::new('roleUser')
            ->setLabel('Role');
        yield BooleanField::new('isActive')
            ->setLabel('Actif');

    }
}
