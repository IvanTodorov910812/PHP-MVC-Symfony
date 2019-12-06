<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 14/03/2019
 * Time: 21:36
 */

namespace AppBundle\Service\Profile;

use Exception;
use Symfony\Component\Form\FormInterface;
use AppBundle\Entity\User;


/**
 * Interface ProfileServiceInterface
 * @package AppBundle\Service\Profile
 */
interface ProfileServiceInterface
{
    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function newProfile(User $user);

    /**
     * @param User $user
     * @return User
     * @throws Exception
     */
    public function editProfile(User $user);

    /**
     * @param FormInterface $form
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function changePassword(FormInterface $form, User $user);

    /**
     * @param mixed $id
     * @return object|null|User
     */
    public function find($id);


}