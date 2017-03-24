<?php

namespace MDL\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Registration
 *
 * @ORM\Table(name="mdl_registration")
 * @ORM\Entity(repositoryClass="MDL\CoreBundle\Repository\RegistrationRepository")
 */
class Registration
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
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message = "email '{{ value }}' non valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\Date()
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="total_price", type="float")
     *
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_code", type="string", length=255, unique=true)
     *
     */
    private $registrationCode;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_ticket", type="integer")
     *
     */
    private $nbTicket;

    /**
     * @var int
     *
     * @ORM\Column(name="paid", type="integer")
     *
     */
    private $paid=0;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_duration", type="string", length=255)
     *
     */
    private $ticketDuration;

    /**
     * @ORM\OneToMany(targetEntity="MDL\CoreBundle\Entity\Visitor", mappedBy="registration", cascade={"persist"})
     * @Assert\Valid()
     */
    private $visitors;

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
     * Set email
     *
     * @param string $email
     *
     * @return Registration
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Registration
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     *
     * @return Registration
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set registrationCode
     *
     * @param string $registrationCode
     *
     * @return Registration
     */
    public function setRegistrationCode($registrationCode)
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    /**
     * Get registrationCode
     *
     * @return string
     */
    public function getRegistrationCode()
    {
        return $this->registrationCode;
    }



    /**
     * Set nbTicket
     *
     * @param int $nbTicket
     *
     * @return Registration
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }

    /**
     * Set ticketDuration
     *
     * @param string $ticketDuration
     *
     * @return Registration
     */
    public function setTicketDuration($ticketDuration)
    {
        $this->ticketDuration = $ticketDuration;

        return $this;
    }

    /**
     * Get ticketDuration
     *
     * @return string
     */
    public function getTicketDuration()
    {
        return $this->ticketDuration;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add visitor
     *
     * @param \MDL\CoreBundle\Entity\Visitor $visitor
     *
     * @return Registration
     */
    public function addVisitor(\MDL\CoreBundle\Entity\Visitor $visitor)
    {
        $this->visitors[] = $visitor;

        return $this;
    }

    /**
     * Remove visitor
     *
     * @param \MDL\CoreBundle\Entity\Visitor $visitor
     */
    public function removeVisitor(\MDL\CoreBundle\Entity\Visitor $visitor)
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * Set paid
     *
     * @param integer $paid
     *
     * @return Registration
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return integer
     */
    public function getPaid()
    {
        return $this->paid;
    }
}
