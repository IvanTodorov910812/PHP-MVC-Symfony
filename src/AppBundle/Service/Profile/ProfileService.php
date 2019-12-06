<?php

namespace AppBundle\Service\Profile;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileService
 * @package AppBundle\Service\Profile
 *
 */
class ProfileService implements ProfileServiceInterface
{
    /** @var UserPasswordEncoder $encoder */
    private $encoder;

    /** @var User $user */
    private $user;

    /** @var UserRepository $userRepo */
    private $userRepo;

    /**
     * Profileservice constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenStorageInterface $tokenStorage
     * @param UserRepository $userRepo
     */
    public function __construct(UserPasswordEncoderInterface $encoder, TokenStorageInterface $tokenStorage, UserRepository $userRepo)
    {
        $this->encoder = $encoder;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->userRepo = $userRepo;
    }

    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function newProfile(User $user)
    {
        if (0 === count($user->getRoles())) {
            throw new \Exception('Profile must have minimum one role.');
        }

        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->userRepo->save($user);

        return $user;
    }

    /**
     * @param User $user
     * @return User
     * @throws Exception
     */
    public function editProfile(User $user)
    {
        if (0 === count($user->getRoles())) {
            throw new \Exception('Profile must have minimum one role.');
        }

        $this->userRepo->save($user);

        return $user;
    }

    /**
     * @param FormInterface $form
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function changePassword(FormInterface $form, User $user)
    {
        $oldPassword = $form->get('old_password')->getData();
        $newPassword = $form->get('new_password')->getData();
        // Change user password
        if (!empty($oldPassword) && !empty($newPassword)) {
            if (!$this->encoder->isPasswordValid($user, $oldPassword)) {
                throw new Exception('The old password is false!');
            }
            $user->setPassword($this->encoder->encodePassword($user, $newPassword));

            return true;
        }

        return false;
    }

    /**
     * @param mixed $id
     * @return object|null|User
     */
    public function find($id)
    {
        return $this->userRepo->find($id);
    }
}
