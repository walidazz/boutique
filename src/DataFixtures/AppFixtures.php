<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;



    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();



        $admin = new User();
        $admin->setEmail('walidazzimani@gmail.com')
            ->setFirstName('Walid')
            ->setLastName('Azzimani')
            ->setPassword($this->encoder->encodePassword($admin, 'sharingan.'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);



        $tabCategory = ['Vêtement', 'Loisir', 'High-tech'];
        $categories = [];
        for ($j = 0; $j < count($tabCategory); $j++) {

            $category = new Category();
            $category->setName($tabCategory[0 + $j]);
            $manager->persist($category);
            $categories[] = $category;
        }


        for ($i = 1; $i < 9; $i++) {
            $product = new Product();
            $product->setCategory($categories[mt_rand(0, count($categories) - 1)])
                ->setDescription('Lorem ipsum dolor sit ametsectetur adipisicing elit. Non dolor et veritatis cum eum nulla id quos, eveniet omnis ullam nesciunt reiciendis quam quisquam! In illo voluptates sit officia aliquid?
')
                ->setIllustration('image' . $i . '.jpg')
                ->setName('Produit numero ' . $i)
                ->setPrice(mt_rand(10, 30))
                ->setSlug($slugify->slugify($product->getName()))
                ->setSubtitle("Petite introduction juste pour l'exemple");
            $manager->persist($product);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
