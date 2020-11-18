<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt', 'Date de commande'),
            TextField::new('user.fullname', 'Utilisateur'),
            MoneyField::new('total')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payé')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail');
    }
}
