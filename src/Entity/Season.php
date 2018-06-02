<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeasonRepository")
 */
class Season
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $air_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poster_path;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $overview;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmdb_show_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $season_number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Show", inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $show_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $episode_count;

    public function getId()
    {
        return $this->id;
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

    public function getAirDate(): ?\DateTimeInterface
    {
        return $this->air_date;
    }

    public function setAirDate(\DateTimeInterface $air_date): self
    {
        if($this->getAirDate() == null || $this->getAirDate()->getTimestamp() != $air_date->getTimestamp()) {
            $this->air_date = $air_date;
        }
        return $this;
    }

    public function getPosterPath(): ?string
    {
        return $this->poster_path;
    }

    public function setPosterPath(?string $poster_path): self
    {
        $this->poster_path = $poster_path;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getTmdbShowId(): ?int
    {
        return $this->tmdb_show_id;
    }

    public function setTmdbShowId(int $tmdb_show_id): self
    {
        $this->tmdb_show_id = $tmdb_show_id;

        return $this;
    }

    public function getSeasonNumber(): ?int
    {
        return $this->season_number;
    }

    public function setSeasonNumber(int $season_number): self
    {
        $this->season_number = $season_number;

        return $this;
    }

    public function getShow(): ?Show
    {
        return $this->show_id;
    }

    public function setShow(?Show $show_id): self
    {
        $this->show_id = $show_id;

        return $this;
    }

    public function getEpisodeCount(): ?int
    {
        return $this->episode_count;
    }

    public function setEpisodeCount(int $episode_count): self
    {
        $this->episode_count = $episode_count;

        return $this;
    }
}
