<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private RoleRepository $roleRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, RoleRepository $roleRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->roleRepository = $roleRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email' => 'stephane@gmail.com',
                'role' => 'student',
                'firstName' => 'Stephane',
                'lastName' => 'Koeniguer',
                'roleSymfony' => ['ROLE_USER']
            ],
            [
                'email' => 'nikoloz@gmail.com',
                'role' => 'teacher',
                'firstName' => 'Nikoloz',
                'lastName' => 'Mamyan',
                'roleSymfony' => ['ROLE_USER']
            ],
            [
                'email' => 'leo@gmail.com',
                'role' => 'teacher',
                'firstName' => 'Leo',
                'lastName' => 'Kovalski',
                'roleSymfony' => ['ROLE_USER']
            ],
            [
                'email' => 'guoying@gmail.com',
                'role' => 'admin',
                'firstName' => 'Guoying',
                'lastName' => 'adminLastName',
                'roleSymfony' => ['ROLE_ADMIN']
            ],

        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setFirstNameUser($userData['firstName']);
            $user->setLastNameUser($userData['lastName']);
            $user->setDateRegisterUser(new \DateTime);
            $user->setIsActive(true);

            // search roles
            $role = $this->roleRepository->findOneBy(['typeRole' => $userData['role']]);
            $user->setRoleUser($role);
            $user->setRoles($userData['roleSymfony']);


            // Hash password
            $password = $this->passwordHasher->hashPassword($user, 'azertyuioP1');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            RoleFixtures::class
        ];

    }
}
