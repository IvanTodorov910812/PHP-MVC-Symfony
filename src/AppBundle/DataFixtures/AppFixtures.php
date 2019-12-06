<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
 *
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface $passwordEncoder */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadRoles($manager);
        $this->loadUsers($manager);
//        $this->loadSuppliers($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadRoles(ObjectManager $manager)
    {
        foreach ($this->getRoleData() as [$name, $description, $position]) {
            $role = new Role();
            $role->setName($name);
            $role->setDescription($description);
            $role->setPosition($position);

            $manager->persist($role);
            $this->addReference($role->getName(), $role);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email, $password, $fullName, $roles]) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setFullName($fullName);

            $roleArray = [];
            foreach ($roles as $role) {
                $roleArray[] = $this->getReference($role);
            }
            $user->setRoles($roleArray);

            $manager->persist($user);
            $this->addReference($user->getEmail(), $user);
        }

        $manager->flush();
    }


    /**
     * @return array
     */
    private function getRoleData()
    {
        return [
            // $roleData = [$name, $title, $position];
            ['ROLE_SUPER_ADMIN', 'SuperAdministrator', 1],
            ['ROLE_ADMIN', 'Administrator', 2],
            ['ROLE_OFFICE', 'OfficeManager', 3],
            ['ROLE_USER', 'Клиент', 4]
        ];
    }


//    /**
//     * @param ObjectManager $manager
//     */
//    private function loadSuppliers(ObjectManager $manager)
//    {
//        foreach ($this->getSupplierData() as [$name, $description, $email, $telefon, $city, $street, $authorId]) {
//            $supplier = new Supplier();
//            $supplier->setName($name);
//            $supplier->setDescription($description);
//            $supplier->setEmail($email);
//            $supplier->setTelefon($telefon);
//            $supplier->setCity($city);
//            $supplier->setStreet($street);
//            $supplier->setAuthorId($authorId);
//
//            $manager->persist($supplier);
//            $this->addReference($supplier->getName(), $supplier);
//        }
//
//        $manager->flush();
//    }

    /**
     * @return array
     */
    private function getUserData()
    {
        return [
            // $userData = [$email, $password, $fullName, $roles];
            ['ivan@abv.bg', '1', 'Todorov Ivan', ['ROLE_ADMIN']],
            ['penka@abv.bg', '1', 'Penka Lqlq', ['ROLE_ADMIN','ROLE_OFFICE']],
            ['admin@admin.bg', '1', 'Admin Admin', ['ROLE_SUPER_ADMIN']],
        ];
    }

//    /**
//     * @return array
//     */
//    private function getSupplierData()
//    {
//        return [
//            // $supplierData = [$name, $description, $email, $telefon, $city, $street, $authorId];
//            ['Nestle', 'Nestle Company', 'nestle@abv.bg', '08899445464', 'Sofia', 'AleksandarPapazov', 1],
//            ['Coca Cola', 'Coca Cola Company', 'cocacola@abv.bg', '08899445464', 'Plovdiv', 'AleksandarIvanov', 2],
//        ];
//    }
}
