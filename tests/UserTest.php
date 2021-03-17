<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{

    private ValidatorInterface $validator;
    private const VALID_EMAIL = 'dfgdfgfdgdfgdfg@gmail.com';
    private const INVALID_EMAIL = 'mlskdflmsdkfmlsdkf';

    public function setUp()
    {

        $this->validator = self::bootKernel()->getContainer()->get('validator');
        //  $this->em = $this->container->get('doctrine')->getManager();
    }

    public function testUserEntityIsValid(): void
    {

        $user = new User();

        $user->setEmail(self::VALID_EMAIL)
       ->setPassword('dfgdfgdfg');





        $errors =   $this->validator->validate($user);
    //   /  dd($errors);
        $this->assertCount(0, $errors);
        // $this->assertSame('test', $kernel->getEnvironment());
        //$routerService = self::$container->get('router');
        //$myCustomService = self::$container->get(CustomService::class);
    }

    // public function getValidationErrors(User $user, int $errorsExpected)
    // {
    //     $errors =   $this->validator->validate($user);
    //     return  $this->assertCount($errorsExpected, $errors);
    // }

    // php bin/phpunit 
}
