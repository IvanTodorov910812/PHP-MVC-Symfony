<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 *
 * @ORM\Table(name="sales")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SaleRepository")
 */
class Sale
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Product is obligatory.")
     *
     * @ORM\Column(name="productName", type="string", length=20)
     */
    private $productName;

    /**
     * @var int
     * @Assert\NotBlank(message="Quantity is obligatory.")
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var double
     * @Assert\NotBlank(message="Price is obligatory.")
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     * @Assert\NotBlank(message="Measure is obligatory.")
     *
     * @ORM\Column(name="measure", type="string", length=10)
     */
    private $measure;

    /**
     * @var string
     * @Assert\NotBlank(message="Client is obligatory.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/",
     *     match=true,
     *     message="Write a valid Client name",
     *     groups={"registration"}
     * )
     *
     * @ORM\Column(name="clientName", type="string", length=20)
     */
    private $clientName;


    /**
     * @var int
     * @Assert\NotBlank(message="Phone is obligatory.")
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/",
     *     match=true,
     *     message="Phone number must contain only digits",
     *     groups={"registration"}
     * )
     * @ORM\Column(name="clientPhone", type="string", length=50)
     */
    private $clientPhone;

    /**
     * @var string
     * @Assert\NotBlank(message="Address is obligatory.")
     * @ORM\Column(name="clientAddress", type="string", length=255)
     */
    private $clientAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="soldOutOn", type="datetime")
     */
    private $soldOutOn;

    /**
     * @var int
     *
     * @ORM\Column(name="authorId", type="integer")
     */
    private $authorId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sales")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     *
     */
    private $author;

    /**
     * @var double
     *
     * @ORM\Column(name="totalSum", type="decimal", precision=10, scale=2)
     */
    private $totalSum;

    /**
     * @var double
     *
     * @ORM\Column(name="totalSumBrutto", type="decimal", precision=10, scale=2)
     */
    private $totalSumBrutto;

    /**
     * @var double
     *
     * @ORM\Column(name="tax", type="decimal", precision=10, scale=2)
     */
    private $tax;



    public function __construct()
    {
        $this->soldOutOn = new \DateTime('now');
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productName.
     *
     * @param string $productName
     *
     * @return Sale
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName.
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return Sale
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set measure.
     *
     * @param string $measure
     *
     * @return Sale
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * Get measure.
     *
     * @return string
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * Set soldOutOn.
     *
     * @param \DateTime $soldOutOn
     *
     * @return Sale
     */
    public function setSoldOutOn($soldOutOn)
    {
        $this->soldOutOn = $soldOutOn;

        return $this;
    }

    /**
     * Get soldOutOn.
     *
     * @return \DateTime
     */
    public function getSoldOutOn()
    {
        return $this->soldOutOn;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId(int $authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Sale
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @param string $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * @param float $quantity
     * @param float $price
     */
    public function setTotalSum($quantity, $price)
    {
        $this->totalSum = $quantity * $price;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param float $totalSum
     */
    public function setTax($totalSum)
    {
        $taxPercentage = 0.1;
        $this->tax = $totalSum * $taxPercentage;
    }

    /**
     * @return float
     */
    public function getTotalSumBrutto()
    {
        return $this->totalSumBrutto;
    }

    /**
     * @param float $totalSum
     * @param float $tax
     */
    public function setTotalSumBrutto($totalSum, $tax)
    {
        $this->totalSumBrutto = $totalSum + $tax;
    }

    /**
     * @return string
     */
    public function getClientAddress()
    {
        return $this->clientAddress;
    }

    /**
     * @param string $clientAddress
     */
    public function setClientAddress($clientAddress)
    {
        $this->clientAddress = $clientAddress;
    }

    /**
     * @return int
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * @param int $clientPhone
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;
    }
}
