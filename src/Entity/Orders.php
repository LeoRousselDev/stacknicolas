<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bill;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $delivery_form;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UsersID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBill(): ?string
    {
        return $this->bill;
    }

    public function setBill(string $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function getDeliveryForm(): ?string
    {
        return $this->delivery_form;
    }

    public function setDeliveryForm(string $delivery_form): self
    {
        $this->delivery_form = $delivery_form;

        return $this;
    }

    public function getUsersID(): ?Users
    {
        return $this->UsersID;
    }

    public function setUsersID(?Users $UsersID): self
    {
        $this->UsersID = $UsersID;

        return $this;
    }
}
