<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 22/03/2019
 * Time: 17:29
 */

namespace AppBundle\Service\Message;


use AppBundle\Entity\Message;
use Exception;

interface MessageServiceInterface
{

    /**
     * @param Message $message
     * @return Message
     * @throws \Exception
     */
    public function newAppointment(Message $message);

    /**
     * @param Message $message
     * @return Message
     * @throws Exception
     */
    public function deleteAppointment(Message $message);

    public function sortedByDate(Message $message);

}