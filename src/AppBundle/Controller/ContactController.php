<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Document;
use AppBundle\Form\ContactType;
use AppBundle\Service\Contact\ContactServiceInterface;
use AppBundle\Service\Document\DocumentServiceInterface;
use AppBundle\Service\FormError\FormErrorServiceInterface;
use Exception;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends Controller
{
    const LIMIT = 5;

    use DataTablesTrait;

    /** @var FormErrorServiceInterface $formErrorService */
    private $formErrorService;

    /** @var ContactServiceInterface $contactService */
    private $contactService;

    /** @var DocumentServiceInterface $documentService */
    private $documentService;

    /**
     * CarController constructor.
     *
     * @param FormErrorServiceInterface $formErrorsService
     * @param ContactServiceInterface $contactService
     * @param DocumentServiceInterface $documentService
     */
    public function __construct(FormErrorServiceInterface $formErrorsService, ContactServiceInterface $contactService, DocumentServiceInterface $documentService)
    {
        $this->formErrorService = $formErrorsService;
        $this->contactService = $contactService;
        $this->documentService = $documentService;
    }

    /**
     * Create a new contact entity.
     *
     * @Route("/contact/new", methods={"GET", "POST"}, name="newContact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->newContact($request, $contact);
//            var_dump($form);die;
            $this->addFlash('success', 'Contact was succesfully created.');

            return $this->redirectToRoute('allContacts');
        }

        return $this->render('contacts/NewContact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView()
        ]);
    }

//    /**
//     * Displays a form to edit an existing car entity.
//     *
//     * @Route("/{id}/edit", name="contactEdit", methods={"GET", "POST"}, requirements={"id": "\d+"})
//     * @param Request $request
//     * @param Contact $contact
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
//     * @throws Exception
//     */
//    public function editAction(Request $request, Contact $contact)
//    {
//        $refUrl = $request->getRequestUri();
//        if (null === $contact->getOwner()) {
//            $this->addFlash('warning', 'Моля, изберете или въведете собственик на МПС.');
//            return $this->redirectToRoute('car_new_owner', ['car' => $car->getId(), 'type' => 'owner', 'ref' => $refUrl]);
//        }
//
//        $form = $this->createForm(CarType::class, $car);
//        $form->handleRequest($request);
//
//        $this->formErrorService->checkErrors($form);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->carService->editCar($request, $car);
//            $this->addFlash('success', 'Данните за МПС бяха успешно записани.');
//
//            return $this->redirectToRoute('car_edit', ['id' => $car->getId()]);
//        }
//
//        return $this->render('car/edit.html.twig', [
//            'car' => $car,
//            'form' => $form->createView(),
//            'refUrl' => $refUrl,
//            'canDelete' => $this->carService->canDelete($car)
//        ]);
//    }

//    /**
//     * Deletes a car entity.
//     *
//     * @Route("/{car}", methods={"DELETE"}, name="car_delete", requirements={"car": "\d+"})
//     * @param Car $car
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function deleteAction(Car $car)
//    {
//        if (!$this->carService->canDelete($car)) {
//            $this->addFlash('danger', 'Нямате права за тази операция.');
//            return $this->redirectToRoute('car_edit', ['id' => $car->getId()]);
//        }
//
//        if (0 < $cnt = $car->getPolicies()->count()) {
//            $this->addFlash('danger', 'Не може да изтриете това МПС, защото принадлежи към ' . $cnt . ' бр. полици.');
//            return $this->redirectToRoute('car_edit', ['id' => $car->getId()]);
//        }
//
//        try {
//            $this->carService->deleteCar($car);
//            $this->addFlash('success', 'МПС бе успешно изтрито.');
//
//            return $this->redirectToRoute('car_index');
//
//        } catch (Exception $ex) {
//            $this->addFlash('danger', $ex->getMessage());
//            return $this->redirectToRoute('car_edit', ['id' => $car->getId()]);
//        }
//    }

    /**
     * @Route("/{contact}/document/{document}/delete", name="car_document_delete", methods={"DELETE"}, requirements={"contact": "\d+", "document": "\d+"})
     * @param Request $request
     * @param Contact $contact
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteDocument(Request $request, Contact $contact, Document $document)
    {
        try {
            $this->documentService->deleteDocument($document);
            $this->addFlash('success', 'Document was succesfully removed.');

        } catch (Exception $ex) {
            $this->addFlash('danger', $ex->getMessage());
        }

        if (null !== $refUrl = $request->query->get('ref')) {
            return $this->redirect($refUrl);
        }

        return $this->redirectToRoute('allContacts');
    }

    /**
     * @Route("/contacts/all", name="allContacts", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allContacts(Request $request){

        $contacts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Contact::class)
            ->findBy([], ['fullName' => 'ASC']);

        $paginator  = $this->get('knp_paginator');

        $documentURL = $this
            ->getDoctrine()
            ->getRepository(Document::class)
            ->findOneBy(['contact' => $contacts]);

        $curUrl = $documentURL->getFileUrl();
        $pagination = $paginator->paginate(
            $contacts,
//            $documents,/* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::LIMIT/*limit per page*/
        );
        return $this->render("contacts/AllContacts.html.twig",
            [   'pagination' => $pagination,
                'curUrl' => $curUrl,
                ]);

    }
}
