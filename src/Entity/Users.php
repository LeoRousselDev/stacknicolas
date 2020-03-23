<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(
 *     fields={"mail"},
 *     message= "L'email que vous avez rentré est déjà utilisé"
 * )
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez rentrer un E-mail"
     * )
     * @Assert\Email(
     *      message="Veuillez rentrer un e-mail valide"
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min="8", 
     *      minMessage="Veuillez rentrer au moins 8 caractères",
     *      max=255,
     *      maxMessage="Veuillez rentrer moins de 255 caractères"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez rentrer un nom"
     * )
     * @Assert\Length(
     *      max=255,
     *      maxMessage="Veuillez rentrer moins de 255 caractères"
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez rentrer un prénom"
     * )
     * @Assert\Length(
     *      max=255,
     *      maxMessage="Veuillez rentrer moins de 255 caractères"
     * )
     */
    private $first_name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *      message="Veuillez rentrer un numéro"
     * )
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_ship;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_fact;

    /**
     * @ORM\Column(type="float")
     */
    private $coefficient;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $role;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {

        return ['ROLE_USER'];

    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getAddressShip(): ?string
    {
        return $this->address_ship;
    }

    public function setAddressShip(?string $address_ship): self
    {
        $this->address_ship = $address_ship;

        return $this;
    }

    public function getAddressFact(): ?string
    {
        return $this->address_fact;
    }

    public function setAddressFact(?string $address_fact): self
    {
        $this->address_fact = $address_fact;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(?bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $role): self
    {
        $this->role = $role;

        return $this;
    }



}
