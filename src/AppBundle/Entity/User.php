<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */


class User implements AdvancedUserInterface
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
     *
     * @Assert\NotBlank(message="Fullname is obligatory.")
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     *
     */
    private $fullName;

    /**
     *
     * @Assert\NotNull()
     * @Assert\Email(
     *     message = "Invalid email.",
     *     checkMX = true
     * )
     * @Assert\Regex(
     *     pattern="/^\w+@\w+\..{2,3}(.{2,3})?$/",
     *     match=true,
     *     message="Write a valid email address",
     *     groups={"registration"}
     * )
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     *
     * @Assert\NotBlank(message="Password is obligatory.")
     * @Assert\Length(
     *     min="6",
     *     max="12",
     *     minMessage="Password must contain at least min. {{ limit }} symbols",
     *     maxMessage="Password cannot be taller than {{ limit }} 12 symbols",
     *     groups={"registration"}
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Za-z0-9]+$/",
     *     match=true,
     *     message="The Password must contain only digits and small letters",
     *     groups={"registration"}
     * )
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registeredAt", type="datetime")
     */
    private $registeredAt;


    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     *
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *   )
     */
    private $roles;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Delivery", mappedBy="author", cascade={"remove"})
     *
     */
    private $deliveries;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sale", mappedBy="author", cascade={"remove"})
     *
     */
    private $sales;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Supplier", mappedBy="author", cascade={"remove"})
     *
     */
    private $createdSupplier;

    /**
     * @var ArrayCollection|Contact[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contact", mappedBy="author")
     */
    private $createdContact;

    /**
     * @var ArrayCollection|Message[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="sender")
     */
    private $senders;

    /**
     * @var ArrayCollection|Message[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="recipient")
     */
    private $recipients;

    public function __construct()
    {
        $this->registeredAt = new \DateTime('now');
        $this->roles = new ArrayCollection();
        $this->createdSupplier = new ArrayCollection();
        $this->createdContact = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->senders = new ArrayCollection();
        $this->recipients = new ArrayCollection();
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return Role[]|ArrayCollection
     */
    public function getProfileRoles()
    {
        return $this->roles;
    }

    /**
     * @param Role[]|ArrayCollection $roles
     * @return User
     */
    public function setProfileRoles($roles)
    {
        foreach ($roles as $role) {
            $this->addProfileRole($role);
        }

        return $this;
    }


    /**
     * @param Role $role
     * @return User
     */
    public function addProfileRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
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
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set registeredAt.
     *
     * @param \DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * Get registeredAt.
     *
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }


    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return in_array('ROLE_SUPER_ADMIN', $this->getRoles());
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isSuperAdmin() || in_array('ROLE_ADMIN', $this->getRoles());
    }

    /**
     * @return bool
     */
    public function isOffice()
    {
        return in_array('ROLE_OFFICE', $this->getRoles());
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return in_array('ROLE_USER', $this->getRoles());
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return 'https://ui-avatars.com/api/?name=' . rawurlencode($this->getFullName()) . '&rounded=true&background=a0a0a0';
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $stringRoles = [];
        /** @var Role $role */
        foreach ($this->roles as $role) {
            $stringRoles[] = $role->getRole();
        }

        return $stringRoles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        // TODO: Implement isAccountNonExpired() method.
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        // TODO: Implement isAccountNonLocked() method.
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        // TODO: Implement isCredentialsNonExpired() method.
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        return true;
    }

    /**
     * @return ArrayCollection
     */
    public function getCreatedSupplier()
    {
        return $this->createdSupplier;
    }

    /**
     * @param Supplier $createdSupplier
     * @return User
     */
    public function addCreatedSupplier($createdSupplier)
    {
        $this->$createdSupplier[] = $createdSupplier;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDeliveries()
    {
        return $this->deliveries;
    }

    /**
     * @param Delivery $delivery
     * @return User
     */
    public function addDelivery(Delivery $delivery)
    {
        $this->deliveries[] = $delivery;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSales(): ArrayCollection
    {
        return $this->sales;
    }

    /**
     * @param Sale $sale
     * @return User
     */
    public function addSale(Sale $sale)
    {
        $this->sales[] = $sale;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param int $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return Contact[]|ArrayCollection
     */
    public function getCreatedContact()
    {
        return $this->createdContact;
    }

    /**
     * @param Contact $contact
     * @return User
     */
    public function addCreatedContact(Contact $contact)
    {
        if (!$this->createdContact->contains($contact)) {
            $this->createdContact->add($contact);
            $contact->setAuthor($this);
        }

        return $this;
    }

}
