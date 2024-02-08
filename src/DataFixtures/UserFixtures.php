<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
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
        $user = new User();
        $user->setEmail('student@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setFirstNameUser('studentFirstName');
        $user->setLastNameUser('studentLastName');
        $user->setDateRegisterUser(new \DateTime);

        // search roles
        $role = $this->roleRepository->findOneBy(['typeRole' => 'student']);
        $user->setRoleUser($role);

        // Hash password
        $password = $this->passwordHasher->hashPassword($user, 'azertyuioP1');
        $user->setPassword($password);

        $user1 = new User();
        $user1->setEmail('teacher@gmail.com');
        $user1->setRoles(['ROLE_TEACHER']);
        $user1->setFirstNameUser('teacherFirstName');
        $user1->setLastNameUser('teacherLastName');
        $user1->setDateRegisterUser(new \DateTime);

        // search roles
        $role = $this->roleRepository->findOneBy(['typeRole' => 'teacher']);
        $user1->setRoleUser($role);

        // Hash password
        $password = $this->passwordHasher->hashPassword($user1, 'azertyuioP1');
        $user1->setPassword($password);

        $user2 = new User();
        $user2->setEmail('admin@gmail.com');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setFirstNameUser('adminFirstName');
        $user2->setLastNameUser('adminLastName');
        $user2->setDateRegisterUser(new \DateTime);

        // search roles
        $role = $this->roleRepository->findOneBy(['typeRole' => 'admin']);
        $user2->setRoleUser($role);

        // Hash password
        $password = $this->passwordHasher->hashPassword($user1, 'azertyuioP1');
        $user2->setPassword($password);

        $manager->persist($user);
        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            RoleFixtures::class
        ];

    }
}
