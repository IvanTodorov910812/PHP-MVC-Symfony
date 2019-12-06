<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 */
class Contact
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
     * @Assert\NotBlank(message="Name is obligatory.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/",
     *     match=true,
     *     message="Write a valid Contact name",
     *     groups={"registration"}
     * )
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var int
     * @Assert\NotBlank(message="Phone is obligatory.")
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/",
     *     match=true,
     *     message="Phone number must contain only digits",
     *     groups={"registration"}
     * )
     * @ORM\Column(name="Phone", type="string", length=50)
     */
    private $phone;

    /**
     * @var string
     * @Assert\NotBlank(message="Address is obligatory.")
     * @ORM\Column(name="Address", type="string", length=255)
     */
    private $address;


    /**
     * @var string
     * @Assert\NotBlank(message="Email is obligatory.")
     * @Assert\Regex(
     *     pattern="/^\w+@\w+\..{2,3}(.{2,3})?$/",
     *     match=true,
     *     message="Write a valid email address",
     *     groups={"registration"}
     * )
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;


    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Document", mappedBy="contact", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $documents;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="createdContact")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $author;


    /**
     * Contact constructor.
     * @throws \Exception
     */
    public function __construct()
    {
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
     * Set fullName.
     *
     * @param string $fullName
     *
     * @return Contact
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set phone.
     *
     * @param int $phone
     *
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Contact
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
            $document->setContact($this);
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
            $document->setContact(null);
        }

        return $this;
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
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
