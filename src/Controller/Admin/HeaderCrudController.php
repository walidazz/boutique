<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    //TODO: gerer l'upload des images avec VichUpload 

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du header'),
            ImageField::new('image')->setBasePath('/uploads/header_image/')->setLabel('Image')->onlyOnIndex(),

            TextareaField::new('content', 'Contenu'),
            TextField::new('btnTitle', 'Titre du bouton'),
            TextField::new('btnUrl', 'Lien du bouton'),
            ImageField::new('imageFile')->setFormType(VichImageType::class)->onlyOnForms(),


        ];
    }
}
