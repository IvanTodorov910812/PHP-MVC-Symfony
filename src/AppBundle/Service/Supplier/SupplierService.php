<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 18/03/2019
 * Time: 16:28
 */

namespace AppBundle\Service\Supplier;


use AppBundle\Entity\Supplier;
use AppBundle\Entity\User;
use AppBundle\Repository\SupplierRepository;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupplierService implements SupplierServiceInterface
{

    /** @var User $currentUser */
    private $currentUser;

    /** @var SupplierRepository $supplierRepo */
    private $supplierRepo;

    /**
     * StickerService constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param SupplierRepository $supplierRepo
     */
    public function __construct(TokenStorageInterface $tokenStorage, SupplierRepository $supplierRepo)
    {
        $this->currentUser = $tokenStorage->getToken()->getUser();
        $this->supplierRepo = $supplierRepo;
    }

    /**
     * @param Supplier $supplier
     * @return bool
     */
    public function canDelete(Supplier $supplier)
    {
        return $this->currentUser->isAdmin() || (null !== $supplier->getAuthor() && $this->currentUser->getId() === $supplier->getAuthor()->getId());
    }


    /**
     * @param Supplier $supplier
     * @return Supplier
     * @throws \Exception
     */
    public function newSupplier(Supplier $supplier)
    {

        $supplier->setAuthor($this->currentUser);
        $this->supplierRepo->save($supplier);

        return $supplier;
    }

    /**
     * @param Supplier $supplier
     * @return Supplier
     * @throws Exception
     */
    public function editSupplier(Supplier $supplier)
    {
        $supplier->setAuthor($this->currentUser);
        $this->supplierRepo->save($supplier);

        return $supplier;
    }

    /**
     * @param Supplier $supplier
     * @return Supplier
     * @throws Exception
     */
    public function deleteSupplier(Supplier $supplier)
    {
        $this->supplierRepo->delete($supplier);

        return $supplier;
    }

    /**
     * @param mixed $id
     * @return object|null|Supplier
     */
    public function find($id)
    {
       $this->supplierRepo->find($id);
    }
}