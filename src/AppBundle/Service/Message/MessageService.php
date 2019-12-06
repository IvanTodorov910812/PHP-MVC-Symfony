<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 22/03/2019
 * Time: 17:42
 */

namespace AppBundle\Service\Message;


use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Repository\MessageRepository;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageService implements MessageServiceInterface
{


    /** @var User $currentUser */
    private $currentUser;

    /** @var MessageRepository $messageRepo */
    private $messageRepo;

    /**
     *
     * @param TokenStorageInterface $tokenStorage
     * @param MessageRepository $messageRepo
     */
    public function __construct(TokenStorageInterface $tokenStorage, MessageRepository $messageRepo)
    {
        $this->currentUser = $tokenStorage->getToken()->getUser();
        $this->messageRepo = $messageRepo;
    }

    /**
     * @param Message $message
     * @return Message
     * @throws \Exception
     */
    public function newAppointment(Message $message)
    {   // need to be closed.. add the recepientId
        $message->setSender($this->currentUser);
        $this->messageRepo->save($message);

        return $message;
    }

    /**
     * @param Message $message
     * @return Message
     * @throws Exception
     */
    public function deleteAppointment(Message $message)
    {
        $this->messageRepo->delete($message);

        return $message;
    }
    /**
     * @param Message $message
     * @return Message
     * @throws Exception
     */
    public function sortedByDate(Message $message){

        $this->messageRepo->sortByDate($message);

        return $message;
    }
}