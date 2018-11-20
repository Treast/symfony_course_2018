<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @JMS\Expose
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @JMS\Expose
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @JMS\Expose
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose
     */
    private $image_url;

    /**
     * @ORM\ManyToMany(targetEntity="Actor", inversedBy="movies")
     * @ORM\JoinTable(name="actors_movies", joinColumns={@ORM\JoinColumn(name="movie_uuid", referencedColumnName="uuid")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="actor_uuid", referencedColumnName="uuid")})
     * @JMS\Expose
     */
    private $actors;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Expose
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Playlist", mappedBy="movies")
     */
    private $playlists;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
    }

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

    /**
     * @return Collection|Playlist[]
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists[] = $playlist;
            $playlist->addMovie($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->contains($playlist)) {
            $this->playlists->removeElement($playlist);
            $playlist->removeMovie($this);
        }

        return $this;
    }
}
