<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 27/03/2019
 * Time: 23:55
 */

namespace AppBundle\Service\Contact;


use AppBundle\Entity\Contact;
use AppBundle\Entity\Document;
use AppBundle\Entity\User;
use AppBundle\Repository\ContactRepository;
use AppBundle\Service\Aws\UploadInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ContactService implements ContactServiceInterface
{

    /** @var User $currentUser */
    private $currentUser;

    /** @var ContactRepository */
    private $contactRepo;

    /** @var UploadInterface $uploadService */
    private $uploadService;

    /**
     * ReportService constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param ContactRepository $contactRepo
     * @param UploadInterface $uploadService
     */
    public function __construct(TokenStorageInterface $tokenStorage, ContactRepository $contactRepo, UploadInterface $uploadService)
    {
        $this->currentUser = $tokenStorage->getToken()->getUser();
        $this->contactRepo = $contactRepo;
        $this->uploadService = $uploadService;
    }

    /**
     * @param Request $request
     * @param Contact $contact
     * @return Contact
     */
    public function newContact(Request $request, Contact $contact)
    {
        $this->processUpload($request, $contact);

        $contact->setAuthor($this->currentUser);
        $this->contactRepo->save($contact);

        return $contact;
    }

    /**
     * @param Request $request
     * @param Contact $contact
     * @return Contact
     * @throws \Exception
     */
    public function editContact(Request $request, Contact $contact)
    {
        $this->processUpload($request, $contact);
        $this->contactRepo->save($contact);

        return $contact;
    }

    /**
     * @param Contact $contact
     */
    public function deleteContact(Contact $contact)
    {
        // delete uploaded files from S3 cloud
        if ($contact->getDocuments()->count() > 0) {
            foreach ($contact->getDocuments() as $document) {
                $this->uploadService->delete(basename($document->getFileUrl()));
            }
        }

        $this->contactRepo->delete($contact);
    }

    /**
     * Upload Contact documents
     *
     * @param Request $request
     * @param Contact $contact
     */
    private function processUpload(Request $request, Contact $contact)
    {

        if (null !== $request->files->get('documents')) {
            /** @var UploadedFile $file */
            foreach ($request->files->get('documents') as $file) {
                $fileUrl = $this->uploadService->upload(
                    $file->getPathname(),
                    $this->uploadService->generateUniqueFileName() . '.' . $file->getClientOriginalExtension(),
                    $file->getClientMimeType()
                );
//                var_dump($file);die;
                $document = new Document();
                $document->setFileUrl($fileUrl);
                $document->setFileName($file->getClientOriginalName());
                $document->setMimeType($file->getClientMimeType());
                $contact->addDocument($document);
            }
        }
    }

//    /**
//     * @param Request $request
//     * @param Contact $contact
//     */
//    public function links(Contact $contact, Request $request)
//    {
//        $byContact = $this->contactRepo->findUrlByContact($contact);
//    }
}