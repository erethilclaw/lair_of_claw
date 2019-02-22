<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
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
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
	 * @ORM\JoinColumn(nullable=false)
	 */
    private $post;

	public function __construct() {
         		$this->published = new \DateTime('now');
         	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

	/**
	 * @return User
	 */
    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

	public function getPost(): Post {
		return $this->post;
	}

	public function setPost(Post $post ) {
		$this->post = $post;

		return $this;
	}


}