<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 27/03/2019
 * Time: 23:55
 */

namespace AppBundle\Service\Contact;


use AppBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;

interface ContactServiceInterface
{

    /**
     * @param Request $request
     * @param Contact $contact
     * @return Contact
     */
    public function newContact(Request $request, Contact $contact);

    /**
     * @param Request $request
     * @param Contact $contact
     * @return Contact
     * @throws \Exception
     */
    public function editContact(Request $request, Contact $contact);

    /**
     * @param Contact $contact
     */
    public function deleteContact(Contact $contact);


//    /**
//     * @param Request $request
//     * @param Contact $contact
//     */
//    public function links();

}