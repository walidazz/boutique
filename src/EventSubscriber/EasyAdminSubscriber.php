<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration']

        ];
    }


    public function setIllustration(BeforeEntityPersistedEvent $event)
    {
        $entity =   $event->getEntityInstance();

        if ($entity instanceof Product) {
            $tmpName = $entity->getIllustration();

            $extension = pathinfo($_FILES['Product']['name']['illustration'], PATHINFO_EXTENSION);
            $filename = uniqid();

            $project_dir = $this->appKernel->getProjectDir();
            move_uploaded_file($tmpName, $project_dir . DIRECTORY_SEPARATOR . 'public' .   DIRECTORY_SEPARATOR . 'uploads'  . DIRECTORY_SEPARATOR . $filename . '.' . $extension);

            $entity->setIllustration($filename . '.' . $extension);
        }
    }
}
