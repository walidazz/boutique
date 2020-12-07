<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('email'),
            ImageField::new('avatar')->setBasePath('/uploads/user_image/')->setLabel('avatar')->onlyOnIndex(),
            TextField::new('fullname'),
            BooleanField::new('enable', 'Actif'),
            ImageField::new('imageFile')->setFormType(VichImageType::class)->onlyOnForms(),
            DateTimeField::new('createdAt', 'Inscrit depuis le')


        ];
    }
}
