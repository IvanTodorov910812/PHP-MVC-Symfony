<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 14/03/2019
 * Time: 21:57
 */

namespace AppBundle\Service\Role;


use AppBundle\Entity\Role;
use AppBundle\Repository\RoleRepository;

class RoleService implements RoleServiceInterface
{

    /** @var RoleRepository $roleRepo */
    private $roleRepo;

    /**
     * RoleService constructor.
     * @param RoleRepository $roleRepo
     */
    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Role|object|null
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->roleRepo->findOneBy($criteria, $orderBy);
    }
}