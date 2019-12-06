<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 18/03/2019
 * Time: 16:25
 */

namespace AppBundle\Service\Supplier;


use AppBundle\Entity\Supplier;
use Exception;
use Symfony\Component\Form\FormInterface;

interface SupplierServiceInterface
{

    /**
     * @param Supplier $supplier
     * @return Supplier
     * @throws \Exception
     */
    public function newSupplier(Supplier $supplier);

    /**
     * @param Supplier $supplier
     * @return Supplier
     * @throws Exception
     */
    public function editSupplier(Supplier $supplier);


    /**
     * @param Supplier $supplier
     * @return Supplier
     * @throws Exception
     */
    public function deleteSupplier(Supplier $supplier);

    /**
     * @param mixed $id
     * @return object|null|Supplier
     */
    public function find($id);

    public function canDelete(Supplier $supplier);


}