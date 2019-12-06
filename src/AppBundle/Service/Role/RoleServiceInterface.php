<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 14/03/2019
 * Time: 21:57
 */
namespace AppBundle\Service\Role;

use AppBundle\Entity\Role;
/**
 * Interface RoleServiceInterface
 * @package AppBundle\Service\Role
 */

interface RoleServiceInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Role|object|null
     */
    public function findOneBy(array $criteria, array $orderBy = null);
}