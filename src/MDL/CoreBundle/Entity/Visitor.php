<?php
namespace MDL\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Visitor
 *
 * @ORM\Table(name="mdl_visitor")
 * @ORM\Entity(repositoryClass="MDL\CoreBundle\Repository\VisitorRepository")
 */
class Visitor
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
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le nom doit faire au moins {{ limit }} caractères.")
     */
    private $lastname;
    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le prénom doit faire au moins {{ limit }} caractères.")
     *
     */
    private $firstname;
    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     *
     */
    private $country;
    /**
     * @var \DateTime
     * @ORM\Column(name="date_of_birth", type="date")
     * @Assert\Date()
     */
    private $dateOfBirth;
    /**
     * @ORM\Column(name="reduced_pricing", type="boolean")
     */
    private $reducedPricing ;
    /**
     * @ORM\ManyToOne(targetEntity="MDL\CoreBundle\Entity\Pricing")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $price;
    /**
     * @ORM\ManyToOne(targetEntity="MDL\CoreBundle\Entity\Registration", inversedBy="visitors")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $registration;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Visitor
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }
    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Visitor
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }
    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    /**
     * Set country
     *
     * @param string $country
     *
     * @return Visitor
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Visitor
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }
    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    /**
     * Set price
     *
     * @param \MDL\CoreBundle\Entity\Pricing $price
     *
     * @return Visitor
     */
    public function setPrice(\MDL\CoreBundle\Entity\Pricing $price)
    {
        $this->price = $price;
        return $this;
    }
    /**
     * Get price
     *
     * @return \MDL\CoreBundle\Entity\Pricing
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * Set registration
     *
     * @param \MDL\CoreBundle\Entity\Registration $registration
     *
     * @return Visitor
     */
    public function setRegistration(\MDL\CoreBundle\Entity\Registration $registration)
    {
        $this->registration = $registration;
        return $this;
    }
    /**
     * Get registration
     *
     * @return \MDL\CoreBundle\Entity\Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }
    /**
     * Set reducedPricing
     *
     * @param boolean $reducedPricing
     *
     * @return Visitor
     */
    public function setReducedPricing($reducedPricing)
    {
        $this->reducedPricing = $reducedPricing;
        return $this;
    }
    /**
     * Get reducedPricing
     *
     * @return boolean
     */
    public function getReducedPricing()
    {
        return $this->reducedPricing;
    }
}