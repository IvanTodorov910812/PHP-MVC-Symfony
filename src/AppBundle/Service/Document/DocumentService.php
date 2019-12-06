<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 20/03/2019
 * Time: 16:56
 */

namespace AppBundle\Service\Document;


use AppBundle\Entity\Document;
use AppBundle\Repository\DocumentRepository;
use AppBundle\Service\Aws\UploadInterface;

/**
 * Class DocumentService
 * @package AppBundle\Service\Document
 */
class DocumentService implements DocumentServiceInterface
{

    /** @var DocumentRepository $documentRepo */
    private $documentRepo;

    /** @var UploadInterface $uploadService */
    private $uploadService;

    /**
     * DocumentService constructor.
     * @param DocumentRepository $documentRepo
     * @param UploadInterface $uploadService
     */
    public function __construct(DocumentRepository $documentRepo, UploadInterface $uploadService)
    {
        $this->documentRepo = $documentRepo;
        $this->uploadService = $uploadService;
    }

    /**
     * @param Document $document
     */
    public function deleteDocument(Document $document)
    {
        $this->uploadService->delete(basename($document->getFileUrl()));
        $this->documentRepo->delete($document);
    }
}