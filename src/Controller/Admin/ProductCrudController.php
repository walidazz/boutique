<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\AttachmentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }
    //FIXME: regler le probleme autour du switch

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            ImageField::new('illustration')->setBasePath('/uploads/article_image/')->setLabel('Image du produit')->onlyOnIndex(),

            TextField::new('subtitle'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('category'),
            CollectionField::new('pictures')
                ->setEntryType(AttachmentType::class)->setFormTypeOption('by_reference', false)->onlyOnForms(),
            ImageField::new('imageFile')->setFormType(VichImageType::class)->onlyOnForms(),
            // CollectionField::new('pictures')
            //     ->setTemplatePath('/images/image.html.twig')
            //     ->onlyOnDetail(),
            //TODO: les attachments ne s'affichent pas en detail si CollectionField est activé onForm()
            BooleanField::new('isBest', 'Produit vedette'),
        ];
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
