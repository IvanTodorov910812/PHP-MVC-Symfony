<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 21/03/2019
 * Time: 15:31
 */

namespace AppBundle\Service\Sale;


use AppBundle\Entity\Sale;
use AppBundle\Entity\User;
use AppBundle\Repository\SaleRepository;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SaleService implements SaleServiceInterface
{

    /** @var User $currentUser */
    private $currentUser;

    /** @var SaleRepository $saleRepository */
    private $saleRepository;

    /**
     *
     * @param TokenStorageInterface $tokenStorage
     * @param SaleRepository $saleRepository
     */
    public function __construct(TokenStorageInterface $tokenStorage, SaleRepository $saleRepository)
    {
        $this->currentUser = $tokenStorage->getToken()->getUser();
        $this->saleRepository = $saleRepository;
    }


    /**
     * @param Sale $sale
     * @return Sale
     * @throws \Exception
     */
    public function newSale(Sale $sale)
    {
        $sale->setAuthor($this->currentUser);
        $sale->setTotalSum($sale->getQuantity(), $sale->getPrice());
        $sale->setTax($sale->getTotalSum());
        $sale->setTotalSumBrutto($sale->getTotalSum(), $sale->getTax());
        $this->saleRepository->save($sale);

        return $sale;
    }

    /**
     * @param Sale $sale
     * @return Sale
     * @throws Exception
     */
    public function editSale(Sale $sale)
    {
        $sale->setAuthor($this->currentUser);
        $this->saleRepository->save($sale);

        return $sale;
    }

    /**
     * @param Sale $sale
     * @return Sale
     * @throws Exception
     */
    public function deleteSale(Sale $sale)
    {
        $this->saleRepository->delete($sale);
        return $sale;
    }
}