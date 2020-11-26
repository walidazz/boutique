<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

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
            TextareaField::new('content', 'Contenu'),
            TextField::new('btnTitle', 'Titre du bouton'),
            TextField::new('btnUrl', 'Lien du bouton'),

        ];
    }
}
