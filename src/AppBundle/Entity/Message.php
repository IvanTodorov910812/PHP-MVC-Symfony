<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
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
     *
     * @ORM\Column(name="about", type="string", length=255)
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="datetime")
     */
    private $dateAdded;

    /**
     * @var bool
     *
     * @ORM\Column(name="isReaded", nullable=false, type="boolean")
     */
    private $isReader;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="senders")
     */
    private $sender;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="recipients")
     */
    private $recipient;


    public function __construct()
    {
        $this->dateAdded = new \DateTime('now');
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
     * Set about.
     *
     * @param string $about
     *
     * @return Message
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about.
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateAdded.
     *
     * @param \DateTime $dateAdded
     *
     * @return Message
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
     * Set isReader.
     *
     * @param bool $isReader
     *
     * @return Message
     */
    public function setIsReader($isReader)
    {
        $this->isReader = $isReader;

        return $this;
    }

    /**
     * Get isReader.
     *
     * @return bool
     */
    public function getIsReader()
    {
        return $this->isReader;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     * @return Message
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;

    }

    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     * @return Message
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }
}
