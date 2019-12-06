<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 19/03/2019
 * Time: 21:28
 */

namespace AppBundle\Service\Delivery;

use AppBundle\Entity\Delivery;
use Exception;
use Symfony\Component\HttpFoundation\Request;

interface DeliveryServiceInterface
{

    /**
     * @param Request $request
     * @param Delivery $delivery
     * @return Delivery
     * @throws \Exception
     */
    public function newDelivery(Request $request,Delivery $delivery);

    /**
     * @param Request $request
     * @param Delivery $delivery
     * @return Delivery
     * @throws Exception
     */
    public function editDelivery(Request $request, Delivery $delivery);


    /**
     * @param Delivery $delivery
     * @return Delivery
     * @throws Exception
     */
    public function deleteDelivery(Delivery $delivery);

    /**
     * @param mixed $id
     * @return object|null|Delivery
     */
    public function find($id);

//
//    public function findByBarcode(string $barcodeName);
//
//    /**
//     *
//     * @return object|null|Delivery
//     */
//    public function allDeliveries();


}