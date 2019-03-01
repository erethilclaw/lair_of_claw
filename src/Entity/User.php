<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "acces_control"="is_granted('IS_AUTHENTICATHED_FULLY')",
 *               "normalization_context"={
 *			        "groups"={"get"}
 *              }
 *          },
 *          "put"={
 *              "acces_control"="is_granted('IS_AUTHENTICATHED_FULLY') and object == user",
 *              "denormalization_context"={
 *                  "groups"={"put"}
 *              },
 *              "normalization_context"={
 *			        "groups"={"get"}
 *              }
 *          }
 *     },
 *     collectionOperations={
 *          "post"={
 *              "denormalization_context"={
 *                  "groups"={"post"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"get"}
 *              }
 *          }
 *      }
 * )
 * @UniqueEntity(fields={"alias"})
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"post","put"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max="25",min="6")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[0-9]).{7,}/",
     *     message="Password need at least, mayus, number"
     * )
     * @Groups({"put","post"})
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *     message="password don't match"
     * )
     *  @Groups({"put","post"})
     */
    private $retypedPassword;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"get","post","put","get-comment-with-author","get-post-with-author"})
     * @Assert\NotBlank()
     */
    private $alias;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author")
	 * @Groups({"get","post"})
	 *
	 */
    private $posts;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
	 * @Groups({"get"})
	 */
    private $comments;

	/**
	 * User constructor.
	 */
	public function __construct() {
		$this->posts = new ArrayCollection();
		$this->comments = new ArrayCollection();
	}


	public function getId(): ?int
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

	/**
	 * @return Collection
	 */
	public function getPosts(): Collection
	{
		return $this->posts;
	}

	/**
	 * @return Collection
	 */
	public function getComments(): Collection
	{
		return $this->comments;
	}


	public function getRetypedPassword() {
		return $this->retypedPassword;
	}


	public function setRetypedPassword( $retypedPassword ) {
		$this->retypedPassword = $retypedPassword;
	}
}
