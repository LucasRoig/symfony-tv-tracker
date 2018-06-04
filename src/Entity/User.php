<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @ORM\Table(name="app_users")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getPlainPassword () {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword ($plainPassword): void {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Show")
     * @ORM\JoinTable(name="user_watchlist")
     */
    private $watchlist;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Show")
     * @ORM\JoinTable(name="user_followlist")
     */
    private $follow_list;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Episode")
     * @ORM\JoinTable(name="user_history")
     */
    private $history;

    public function __construct()
    {
        $this->watchlist = new ArrayCollection();
        $this->follow_list = new ArrayCollection();
        $this->history = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize () {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize ($serialized) {
        list(
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles () {
        return array('ROLE_USER');
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt () {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername () {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials () {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Show[]
     */
    public function getWatchlist(): Collection
    {
        return $this->watchlist;
    }

    public function addWatchlist(Show $watchlist): self
    {
        if (!$this->watchlist->contains($watchlist)) {
            $this->watchlist[] = $watchlist;
        }

        return $this;
    }

    public function removeWatchlist(Show $watchlist): self
    {
        if ($this->watchlist->contains($watchlist)) {
            $this->watchlist->removeElement($watchlist);
        }

        return $this;
    }

    /**
     * @return Collection|Show[]
     */
    public function getFollowList(): Collection
    {
        return $this->follow_list;
    }

    public function addToFollowList(Show $followList): self
    {
        if (!$this->follow_list->contains($followList)) {
            $this->follow_list[] = $followList;
        }

        return $this;
    }

    public function removeFromFollowList(Show $followList): self
    {
        if ($this->follow_list->contains($followList)) {
            $this->follow_list->removeElement($followList);
        }

        return $this;
    }

    /**
     * @return Collection|Episode[]
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addToHistory(Episode $history): self
    {
        if (!$this->history->contains($history)) {
            $this->history[] = $history;
        }

        return $this;
    }

    public function removeFromHistory(Episode $history): self
    {
        if ($this->history->contains($history)) {
            $this->history->removeElement($history);
        }

        return $this;
    }

    public function getWatchedEpisodeCountForSeason($tmdbId, $seasonNumber){
        return $this->getHistory()->filter(function ($e) use ($tmdbId,$seasonNumber){
            return $e->getTvShow()->getTmdbId() == $tmdbId && $e->getSeasonNumber() == $seasonNumber;
        })->count();
    }

    public function countEpisodesWatchedForShow($show){
        return $this->getHistory()->filter(function ($e) use ($show){
            return $e->getTvShow()->getId() === $show->getId();
        })->count();
    }

    public function isEpisodeWatched($episode){
        return $this->getHistory()->contains($episode);
    }

    public function hasCompletedShow($show){
        return $this->getHistory()->filter(function ($e) use ($show){
            return $show === $e->getTvShow();
        })->count() == $show->getEpisodes()->count();
    }
}
