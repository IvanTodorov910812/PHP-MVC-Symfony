<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Delivery
 *
 * @ORM\Table(name="deliveries")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryRepository")
 */
class Delivery
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
     * @Assert\NotBlank(message="Product name is obligatory.")
     *
     * @ORM\Column(name="productName", type="string", length=50)
     */
    private $productName;

    /**
     * @var string
     * @Assert\Length(
     *      min = 15,
     *      max = 15,
     *      minMessage = "Barcode must be at least {{ limit }} characters long",
     *      maxMessage = "Barcode cannot be longer than {{ limit }} characters"
     * )
     *
     * @Assert\NotBlank(message="Barcode is obligatory.")
     *
     * @ORM\Column(name="barcode", type="string", length=15)
     */
    private $barcode;

    /**
     * @Assert\NotBlank(message="Quantity is obligatory.")
     * @var string
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/",
     *     match=true,
     *     message="Quantity should contain only digits",
     *     groups={"registration"}
     * )
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     * @Assert\NotBlank(message="Measure is obligatory.")
     *
     * @ORM\Column(name="measure", type="string", length=10)
     */
    private $measure;

    /**
     * @var double
     * @Assert\NotBlank(message="Price is obligatory.")
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="datetime")
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bestToDate", type="datetime", nullable=true)
     */
    private $bestToDate;

    /**
     * @var int
     *
     * @ORM\Column(name="authorId", type="integer")
     */
    private $authorId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="deliveries")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     *
     */
    private $author;

    /**
     * @var double
     *
     * @ORM\Column(name="totalCostSum", type="decimal", precision=10, scale=2)
     */
    private $totalCost;

    /**
     * @var double
     *
     * @ORM\Column(name="tax", type="decimal", precision=10, scale=2)
     */
    private $tax;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Document", mappedBy="delivery", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $documents;

    /**
     * Contact constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dateAdded = new \DateTime('now');
        $this->documents = new ArrayCollection();
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
     * @return Delivery
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
     * @param string $quantity
     *
     * @return Delivery
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return string
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
     * @return Delivery
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
     * Set dateAdded.
     *
     * @param \DateTime $dateAdded
     *
     * @return Delivery
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded.
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set bestToDate.
     *
     * @param \DateTime $bestToDate
     *
     * @return Delivery
     */
    public function setBestToDate($bestToDate)
    {
        $this->bestToDate = $bestToDate;

        return $this;
    }

    /**
     * Get bestToDate.
     *
     * @return \DateTime
     */
    public function getBestToDate()
    {
        return $this->bestToDate;
    }

    /**
     * @return int
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param int $barcode
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Delivery
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        return $this;
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
     * @param float $price
     * @param float $quantity
     */
    public function setTotalCost($price, $quantity){
        $this->totalCost = $price * $quantity;
    }

    /**
     * @return float
     */
    public function getTotalCost()
    {
        return $this->totalCost;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param float $totalCost
     */
    public function setTax($totalCost)
    {
        $taxPercentage = 0.1;
        $this->tax = $totalCost * $taxPercentage;
    }

    /**
     * @return Document[]|ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param Document[]|ArrayCollection $documents
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    /**
     * @param Document $document
     * @return $this
     */
    public function addDocument(Document $document)
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setDelivery($this);
        }

        return $this;
    }

    /**
     * @param Document $document
     * @return $this
     */
    public function removeDocument(Document $document)
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            $document->setDelivery(null);
        }

        return $this;
    }
}
