<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 19/03/2019
 * Time: 21:28
 */

namespace AppBundle\Service\Delivery;


use AppBundle\Entity\Delivery;
use AppBundle\Entity\Document;
use AppBundle\Entity\User;
use AppBundle\Repository\DeliveryRepository;
use AppBundle\Service\Aws\UploadInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeliveryService implements DeliveryServiceInterface
{

    /** @var User $currentUser */
    private $currentUser;

    /** @var DeliveryRepository $deliveryRepo */
    private $deliveryRepo;

    /** @var UploadInterface $uploadService */
    private $uploadService;

    /**
     *
     * @param TokenStorageInterface $tokenStorage
     * @param DeliveryRepository $deliveryRepo
     */
    public function __construct(TokenStorageInterface $tokenStorage, DeliveryRepository $deliveryRepo, UploadInterface $uploadService)
    {
        $this->currentUser = $tokenStorage->getToken()->getUser();
        $this->deliveryRepo = $deliveryRepo;
        $this->uploadService = $uploadService;
    }


    /**
     * @param string $barcodeName
     * @return Delivery[]|null
     */
    public function findByBarcode(string $barcodeName)
    {
        return $this->deliveryRepo->findByBarcode($barcodeName);
    }

    /**
     * @param Request $request
     * @param Delivery $delivery
     * @return Delivery
     * @throws \Exception
     */
    public function newDelivery(Request $request,Delivery $delivery)
    {
        $this->processUpload($request, $delivery);

        $delivery->setAuthor($this->currentUser);
        $delivery->setTotalCost($delivery->getPrice(), $delivery->getQuantity());
        $delivery->setTax($delivery->getTotalCost());

        $this->deliveryRepo->save($delivery);

        return $delivery;
    }

    /**
     * @param Request $request
     * @param Delivery $delivery
     * @return Delivery
     * @throws Exception
     */
    public function editDelivery(Request $request, Delivery $delivery)
    {
        $delivery->setAuthor($this->currentUser);
        $this->deliveryRepo->save($delivery);

        return $delivery;
    }

    /**
     * @param Delivery $delivery
     * @return Delivery
     * @throws Exception
     */
    public function deleteDelivery(Delivery $delivery)
    {
        $this->deliveryRepo->delete($delivery);

        return $delivery;
    }



    public function find($id)
    {
        $this->deliveryRepo->findBy($id);
    }

    public function allDeliveries(){

        $this->deliveryRepo->findAll();
    }


    /**
     * Upload Contact documents
     *
     * @param Request $request
     * @param Delivery $delivery
     */
    private function processUpload(Request $request, Delivery $delivery)
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
                $delivery->addDocument($document);
            }
        }
    }


}