<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 */
class Document
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
     * @var string|null
     *
     * @ORM\Column(name="fileName", type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fileUrl", type="string", length=255, nullable=true)
     */
    private $fileUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mimeType", type="string", length=255, nullable=true)
     */
    private $mimeType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploadAt", type="datetime")
     */
    private $uploadAt;

    /**
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact", inversedBy="documents")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=true)
     */
    private $contact;

    /**
     * @var Delivery
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Delivery", inversedBy="documents")
     * @ORM\JoinColumn(name="delivery_id", referencedColumnName="id", nullable=true)
     */
    private $delivery;

    /**
     * Add one Object for the testing the documents - Web Services Amazon S3
     */

    public function __construct()
    {
        $this->uploadAt = new \DateTime('now');
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
     * Set fileName.
     *
     * @param string|null $fileName
     *
     * @return Document
     */
    public function setFileName($fileName = null)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName.
     *
     * @return string|null
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileUrl.
     *
     * @param string|null $fileUrl
     *
     * @return Document
     */
    public function setFileUrl($fileUrl = null)
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    /**
     * Get fileUrl.
     *
     * @return string|null
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * Set mimeType.
     *
     * @param string|null $mimeType
     *
     * @return Document
     */
    public function setMimeType($mimeType = null)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string|null
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set uploadAt.
     *
     * @param \DateTime $uploadAt
     *
     * @return Document
     */
    public function setUploadAt($uploadAt)
    {
        $this->uploadAt = $uploadAt;

        return $this;
    }

    /**
     * Get uploadAt.
     *
     * @return \DateTime
     */
    public function getUploadAt()
    {
        return $this->uploadAt;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param Contact $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param Delivery $delivery
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
    }
}
