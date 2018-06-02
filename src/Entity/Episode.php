<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EpisodeRepository")
 */
class Episode
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
    private $air_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $episode_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $overview;

    /**
     * @ORM\Column(type="integer")
     */
    private $season_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $still_path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Show", inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tv_show;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    public function getId()
    {
        return $this->id;
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

    public function getEpisodeNumber(): ?int
    {
        return $this->episode_number;
    }

    public function setEpisodeNumber(int $episode_number): self
    {
        $this->episode_number = $episode_number;

        return $this;
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

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): self
    {
        $this->overview = $overview;

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

    public function getStillPath(): ?string
    {
        return $this->still_path;
    }

    public function setStillPath(?string $still_path): self
    {
        $this->still_path = $still_path;

        return $this;
    }

    public function getTvShow(): ?Show
    {
        return $this->tv_show;
    }

    public function setTvShow(?Show $tv_show): self
    {
        $this->tv_show = $tv_show;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }
}
