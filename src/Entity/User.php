<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[UniqueEntity(
    fields: "email",
    message: "L'email renseigné existe déjà..."
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    /**
    * @Assert\Length(min=3,max=50)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    /**
     * @Assert\Length(min=3,max=50)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    /**
     * @Assert\Email(message="Email non valide.")
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    /**
     * @Assert\Length(min=6,max=50)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class, orphanRemoval: true)]
    private $articles;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les deux mots de passe doivent être identiques")                  //vérifie que le mdp tapé est bien égal à la confirmation du mdp
     */
    private $passwordConfirm;

    /**
     * @return mixed
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * @param mixed $passwordConfirm
     */
    public function setPasswordConfirm($passwordConfirm): void
    {
        $this->passwordConfirm = $passwordConfirm;
    }



    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->createdAt= new \DateTime();        //date de la publication de façon auto
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function __toString() {                          //permet de convertir en string
        return $this->firstname.' '.$this->lastname;        // . = concaténer nom de famille avec prénom
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
    public function getSalt(){

    }
    public function eraseCredentials(){

    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }
}
