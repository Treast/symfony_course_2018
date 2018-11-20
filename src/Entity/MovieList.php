<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieListRepository")
 */
class MovieList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="movies_lists")
     * @ORM\JoinColumn(name="user_uuid", referencedColumnName="uuid")
     */
    private $user_uuid;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUserUuid(): ?User
    {
        return $this->user_uuid;
    }

    public function setUserUuid(?User $user_uuid): self
    {
        $this->user_uuid = $user_uuid;

        return $this;
    }
}
