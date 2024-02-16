<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AddressRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity(fields: ['delivery'], message: 'Une seule adresse peut être définie comme adresse de livraison')]
#[UniqueEntity(fields: ['invoice'], message: 'Une seule adresse peut être définie comme adresse de facturation')]
#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $civility;

    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $number;

    #[ORM\Column(type: 'string', length: 255)]
    private $way;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $postal_code;

    #[ORM\Column(type: 'boolean', nullable: true, unique: true)]
    private $delivery;

    #[ORM\Column(type: 'boolean', nullable: true, unique: true)]
    private $invoice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }


    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getWay(): ?string
    {
        return $this->way;
    }

    public function setWay(string $way): self
    {
        $this->way = $way;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }


    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function isDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function setDelivery(?bool $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function isInvoice(): ?bool
    {
        return $this->invoice;
    }

    public function setInvoice(?bool $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }
}
