<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Sale;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * SaleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SaleRepository extends \Doctrine\ORM\EntityRepository
{
    /** @var EntityManagerInterface $em */
    private $em;

    /**
     * CarRepository constructor.
     * @param EntityManagerInterface $em
     * @param ClassMetadata $class
     */
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->em = $em;
    }

    /**
     * @param Sale $sale
     * @return Sale
     */
    public function save(Sale $sale)
    {
        if (null === $sale->getId()) {
            $this->em->persist($sale);
        }
        $this->em->flush();

        return $sale;
    }

    /**
     * @param Sale $sale
     */
    public function delete(Sale $sale)
    {
        $this->em->remove($sale);
        $this->em->flush();
    }
}
