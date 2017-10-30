<?php

namespace Sazhin\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Post
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Sazhin\BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sazhin\BlogBundle\Entity\User", inversedBy="posts")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     */
    private $categories;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min="10",
     *     max="500",
     *     minMessage="Заголовок должен содержать не менее 10 символов",
     *     maxMessage="Заголовок слишком длинный, максимальное значение символов - 500"
     * )
     *
     * @ORM\Column(name="title", type="string", length=500)
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="200",
     *     max="1500",
     *     minMessage="Краткое описание не может быть короче 200 символов",
     *     maxMessage="Краткость - сестра таланта! Максимальное кол-во символов - 1500"
     * )
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="100",
     *     minMessage="Краткое описание не может быть короче 100 символов",
     *     max="500",
     *     maxMessage="Краткость - сестра таланта! Максимальное количество сиволов - 500"
     * )
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /** @ORM\Column(type="json_array", nullable=true) */
    private $currentPlace;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     */
    private $comments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Triggered on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->user = $this->getUser();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Triggered on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \Sazhin\BlogBundle\Entity\Category $category
     *
     * @return Post
     */
    public function addCategory(\Sazhin\BlogBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Sazhin\BlogBundle\Entity\Category $category
     */
    public function removeCategory(\Sazhin\BlogBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set user
     *
     * @param \Sazhin\BlogBundle\Entity\User $user
     *
     * @return Post
     */
    public function setUser(\Sazhin\BlogBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sazhin\BlogBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getTimeCreated()
    {
        return $this->createdAt->format('Y-m-d h:i:s ');
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return ArrayCollection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $currentPlace
     */
    public function setCurrentPlace($currentPlace)
    {
        $this->currentPlace = $currentPlace;
    }

    /**
     * @return mixed
     */
    public function getCurrentPlace()
    {
        return $this->currentPlace;
    }
}
