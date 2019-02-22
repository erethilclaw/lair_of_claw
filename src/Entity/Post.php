<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ApiResource(
 *     itemOperations={
 *     "get",
 *     "put"={
 *     "acces_control"="is_granted('IS_AUTHENTICATHED_FULLY') and object.getAuthor() == user"
 *          }
 *      },
 *     collectionOperations={
 *     "get",
 *     "post"={
 *     "acces_control"="is_granted('IS_AUTHENTICATHED_FULLY')"
 *          }
 *      }
 * )
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     * @Assert\NotBlank()
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\NotBlank()
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
	 */
    private $comments;

	public function __construct() {
         		$this->createAt = new \DateTime('now');
				$this->comments = new ArrayCollection();
         	}


	public function getId(): ?int
             {
                 return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

	public function getComments(): Collection {
		return $this->comments;
	}

	public function __toString() {
    return $this->slug;
	}
}
