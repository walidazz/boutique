<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class OrderCrudController extends AbstractCrudController
{

    private $em;
    private $crudUrlGenerator;
    private $mail;


    public function __construct(EntityManagerInterface $em, CrudUrlGenerator $crudUrlGenerator, MailService $mail)
    {
        $this->em = $em;
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->mail = $mail;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-dolly')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-shipping-fast')->linkToCrudAction('updateDelivery');
        $updateOrderDelivered = Action::new('updateOrderDelivered', 'Livrée', 'fas fa-flag-checkered ')->linkToCrudAction('updateOrderDelivered');

        return $actions
            ->add(Crud::PAGE_INDEX, 'detail')
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery)
            ->add('detail', $updateOrderDelivered);
    }


    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('reference'),
            DateField::new('createdAt', 'Date de commande'),
            TextField::new('user.fullname', 'Utilisateur'),
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de livraison')->setCurrency('EUR'),
            ChoiceField::new('state', 'status')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
                'Livrée' => 4
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->onlyOnDetail()
        ];
    }


    public function updatePreparation(AdminContext $context)
    {
        /** @var Order */
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->em->flush();
        $url =   $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)->setAction(Action::INDEX)->generateUrl();
        $this->addFlash('success', "la commande {$order->getReference()} est bien cours de préparation");
        $this->mail->send($order->getUser(), 'Suivi de votre commande', "la commande {$order->getReference()} est bien cours de préparation");
        return $this->redirect($url);
    }


    public function updateDelivery(AdminContext $context)
    {
        /** @var Order */
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->em->flush();
        $url =   $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)->setAction(Action::INDEX)->generateUrl();
        $this->addFlash('success', "la commande {$order->getReference()} est bien cours de livraison");
        $this->mail->send($order->getUser(), 'Suivi de votre commande', "la commande {$order->getReference()} est bien cours de livraison");


        return $this->redirect($url);
    }


    public function updateOrderDelivered(AdminContext $context)
    {
        /** @var Order */
        $order = $context->getEntity()->getInstance();
        $order->setState(4);
        $this->em->flush();
        $url =   $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)->setAction(Action::INDEX)->generateUrl();
        $this->addFlash('success', "la commande {$order->getReference()} a bien été livrée !");

        return $this->redirect($url);
    }
}
