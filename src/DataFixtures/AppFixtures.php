<?php

namespace App\DataFixtures;

use App\Entity\Adress;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use DateTime;
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
            ->setAvatar('standard.png')
            ->setEnable(1)
            ->setCreatedAt(new DateTime())

            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $adress = new Adress();
        $adress->setAdress('14 route de bezouce')
            ->setCity('Meynes')
            ->setCountry('France')
            ->setFirstName('Walid')
            ->setLastName('Azzimani')
            ->setName('Domicile')
            ->setCompany('Societe privée')
            ->setPostal('30840')
            ->setUser($admin);

        $manager->persist($adress);

        $carrier = new Carrier();
        $carrier->setName('Colissimo')
            ->setDescription('Rapide et fiable')
            ->setPrice(4.99);
        $manager->persist($carrier);


        $category = new Category();
        $category->setName('Vétement');
        $manager->persist($category);
        $categories[] = $category;


        $robe = new Product();
        $robe->setCategory($category)
            ->setCreatedAt(new DateTime())
            ->setDescription("Une robe est un vêtement qui couvre le corps d'une seule pièce allant des épaules aux jambes. Suivant la matière utilisée, elle s'enfile par la tête ou les pieds, et comprend ou non des ouvertures supplémentaires (dos, devant, côté) permettant de l'ajuster ensuite plus ou moins près du corps, par un laçage, des boutons, agrafes, fermetures éclair, etc.")
            ->setIllustration('robe.png')
            ->setName('Robe')
            ->setIsBest(1)
            ->setPrice(39.99 * 100)
            ->setSlug($slugify->slugify($robe->getName()))
            ->setSubtitle("Vétements pour femme à la mode");
        $manager->persist($robe);


        $chapeau = new Product();
        $chapeau->setCategory($category)
            ->setCreatedAt(new DateTime())
            ->setDescription("Le chapeau est un couvre-chef, devenu un accessoire de mode. Il se distingue des autres couvre-chefs par sa matière, le feutre, la présence d'un bord plus ou moins large, et sa mise en forme.")
            ->setIllustration('chapeau.png')
            ->setName('Chapeau')
            ->setIsBest(1)
            ->setPrice(29.99 * 100)
            ->setSlug($slugify->slugify($chapeau->getName()))
            ->setSubtitle("Vétements pour homme à la mode");
        $manager->persist($chapeau);


        $chaussure = new Product();
        $chaussure->setCategory($category)
            ->setCreatedAt(new DateTime())
            ->setDescription("Les chaussures, ou souliers en Amérique du Nord francophone, constituent un élément d'habillement dont le rôle est de protéger les pieds. Il s'agit également d'un accessoire de mode qui vêt les femmes comme les hommes. Le terme chaussure dérive du verbe chausser, issu du latin calceare « mettre des souliers »")
            ->setIllustration('chaussure.png')
            ->setName('Chaussure')
            ->setIsBest(1)
            ->setPrice(69.99 * 100)
            ->setSlug($slugify->slugify($chaussure->getName()))
            ->setSubtitle("Chaussure pour homme");
        $manager->persist($chaussure);

        $echarpe = new Product();
        $echarpe->setCategory($category)
            ->setCreatedAt(new DateTime())
            ->setDescription("L'écharpe se portera tout aussi bien par dessus une veste en cuir qu'un pull de grande taille en laine. Si vous préférez les couleurs chaudes comme le rouge, le beige ou le marron, il convient de les assortir avec les éléments de votre tenue. Vous pouvez aussi porter des accessoires de la même couleur. Adepte des couleurs vives comme le jaune, l'orange ou le bleu ? Portez votre écharpe ou votre foulard de manière subtile et discrète. Astuce mode : pour éviter de tomber dans le")
            ->setIllustration('echarpe.png')
            ->setName('Echarpe')
            ->setIsBest(1)
            ->setPrice(19.99 * 100)
            ->setSlug($slugify->slugify($echarpe->getName()))
            ->setSubtitle("Echarpe pour homme");
        $manager->persist($echarpe);


        for ($i = 1; $i < 3; $i++) {
            $header = new Header();

            $header->setBtnTitle('Découvrir')
                ->setBtnUrl('/nos-produits')
                ->setCreatedAt(new DateTime())
                ->setContent("Le e-commerce ou commerce électronique regroupe l'ensemble des transactions commerciales s'opérant à distance par le biais d'interfaces électroniques et digitales.")
                ->setImage('header' . $i . '.png')
                ->setTitle('La boutique e-commerce 100% française');

            $manager->persist($header);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
