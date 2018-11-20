<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MovieList", orphanRemoval=true, mappedBy="user_uuid")
     */
    private $movies_lists;

    public function __construct()
    {
        $this->movies_lists = new ArrayCollection();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return Collection|MovieList[]
     */
    public function getMoviesLists(): Collection
    {
        return $this->movies_lists;
    }

    public function addMoviesList(MovieList $moviesList): self
    {
        if (!$this->movies_lists->contains($moviesList)) {
            $this->movies_lists[] = $moviesList;
            $moviesList->setUserUuid($this);
        }

        return $this;
    }

    public function removeMoviesList(MovieList $moviesList): self
    {
        if ($this->movies_lists->contains($moviesList)) {
            $this->movies_lists->removeElement($moviesList);
            // set the owning side to null (unless already changed)
            if ($moviesList->getUserUuid() === $this) {
                $moviesList->setUserUuid(null);
            }
        }

        return $this;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'uuid' => $this->getUuid(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'movies_lists' => $this->getMoviesLists(),
        ];
    }
}
