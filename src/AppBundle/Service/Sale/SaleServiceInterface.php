<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 21/03/2019
 * Time: 15:28
 */

namespace AppBundle\Service\Sale;


use AppBundle\Entity\Sale;
use AppBundle\Entity\Supplier;
use Exception;

interface SaleServiceInterface
{
    /**
     * @param Sale $sale
     * @return Sale
     * @throws \Exception
     */
    public function newSale(Sale $sale);

    /**
     * @param Sale $sale
     * @return Sale
     * @throws Exception
     */
    public function editSale(Sale $sale);


    /**
     * @param Sale $sale
     * @return Sale
     * @throws Exception
     */
    public function deleteSale(Sale $sale);

}