<?php
//Entity non relié a la base de données, utiliser pour modifier un mot de passe

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;

class Password
{
    /**
     * @var string
     * @Assert\NotBlank(
     *      message="Veuillez rentrer votre ancien mot de passe"
     * )
     * @Assert\Length(
     *      min="8", 
     *      minMessage="Veuillez rentrer au moins 8 caractères",
     *      max=255,
     *      maxMessage="Veuillez rentrer moins de 255 caractères"
     * )
     */
    private $newPassword;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message="Veuillez rentrer votre nouveau mot de passe"
     * )
     * @Assert\Length(
     *      min="8", 
     *      minMessage="Veuillez rentrer au moins 8 caractères",
     *      max=255,
     *      maxMessage="Veuillez rentrer moins de 255 caractères"
     * )
     */
    private $oldPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }
}
