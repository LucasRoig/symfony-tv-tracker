<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShowRepository")
 * @ORM\Table(name="tv_shows")
 */
class Show
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backdrop_path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poster_path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $first_air_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $overview;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmdb_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Season", mappedBy="show_id", orphanRemoval=true)
     */
    private $seasons;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Episode", mappedBy="tv_show", orphanRemoval=true)
     */
    private $episodes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $in_production;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_air_date;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->episodes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBackdropPath(): ?string
    {
        return $this->backdrop_path;
    }

    public function setBackdropPath(?string $backdrop_path): self
    {
        $this->backdrop_path = $backdrop_path;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstAirDate(): ?\DateTimeInterface
    {
        return $this->first_air_date;
    }

    public function setFirstAirDate(\DateTimeInterface $first_air_date): self
    {
        if($this->getFirstAirDate() == null || $this->getFirstAirDate()->getTimestamp() != $first_air_date->getTimestamp()) {
            $this->first_air_date = $first_air_date;
        }
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

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdb_id;
    }

    public function setTmdbId(int $tmdb_id): self
    {
        $this->tmdb_id = $tmdb_id;

        return $this;
    }

    /**
     * @return Collection|Season[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setShow($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->contains($season)) {
            $this->seasons->removeElement($season);
            // set the owning side to null (unless already changed)
            if ($season->getShow() === $this) {
                $season->setShow(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Episode[]
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->setTvShow($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->contains($episode)) {
            $this->episodes->removeElement($episode);
            // set the owning side to null (unless already changed)
            if ($episode->getTvShow() === $this) {
                $episode->setTvShow(null);
            }
        }

        return $this;
    }

    public function getSeasonsCount(){
        return $this->getSeasons()->count();
    }

    public function getSeasonByNumber($seasonNumber){
        foreach ($this->getSeasons() as $season){
            if ($season->getSeasonNumber() == $seasonNumber) return $season;
        }
        return null;
    }

    public function getUnairedEpisodes(){
        $yesterday = new \DateTime();
        $yesterday->add(\DateInterval::createFromDateString('yesterday'));
        return $this->episodes->filter(function (Episode $e) use ($yesterday){
            return $e->getAirDate() > $yesterday;
        });
    }

    public function getNextAiredEpisode(){
        $unairedEpisodes = $this->getUnairedEpisodes();
        if ($unairedEpisodes->isEmpty()){
            return null;
        }

        $nextEpisode = $unairedEpisodes->first();
        foreach ($unairedEpisodes as $e){
            if($nextEpisode->getAirDate() > $e->getAirDate()){
                $nextEpisode = $e;
            }
        }

        return $nextEpisode;
    }

    public function isHot(){
        $nextEpisode = $this->getNextAiredEpisode();
        if ($nextEpisode == null) return false;
        $airDate = Carbon::instance($nextEpisode->getAirDate());

        return $airDate->diffInDays(Carbon::now()) < 15;
    }

    public function getInProduction(): ?bool
    {
        return $this->in_production;
    }

    public function setInProduction(bool $in_production): self
    {
        $this->in_production = $in_production;

        return $this;
    }

    public function getLastAirDate(): ?\DateTimeInterface
    {
        return $this->last_air_date;
    }

    public function setLastAirDate(\DateTimeInterface $last_air_date): self
    {
        $this->last_air_date = $last_air_date;

        return $this;
    }
}
