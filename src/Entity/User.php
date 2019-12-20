<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     * @var ArrayCollection
     */
    private $posts;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private $pwd;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }


    public function getId(): ?int
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): self
    {
        $this->pwd = $pwd;

        return $this;
    }

    public function getFullName(){
        $fullName = "";
        if(! empty($this->firstname)){
            $fullName = $this->firstname. " ";
        }

        return $fullName . strtoupper($this->name);
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        //doit retourner un tableau de tout les roles que le user peut faire
        return ['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'];
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->pwd;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        //Pour le moment on retourne null car on ne va pas l'utiliser
        //permet d'ajouter un bout de sequence aléatoire
        //si user a meme mdp alors n'auront pas le meme grain de sel
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }
}
