<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
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
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_url;

    /**
     * @ORM\ManyToMany(targetEntity="Actor", inversedBy="movies")
     * @ORM\JoinTable(name="actors_movies", joinColumns={@ORM\JoinColumn(name="movie_uuid", referencedColumnName="uuid")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="actor_uuid", referencedColumnName="uuid")})
     */
    private $actors;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @return mixed
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * @param mixed $actors
     */
    public function setActors($actors): void
    {
        $this->actors = $actors;
    }
}
