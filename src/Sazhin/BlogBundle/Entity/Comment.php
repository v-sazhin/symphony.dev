<?php

namespace Sazhin\BlogBundle\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="comments")
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="Sazhin\BlogBundle\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="comment", type="string", length=64)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Sazhin\BlogBundle\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *@ORM\ManyToOne(targetEntity="Sazhin\BlogBundle\Entity\Post", inversedBy="comments")
     * @JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Sazhin\BlogBundle\Entity\Comment")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Sazhin\BlogBundle\Entity\Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Sazhin\BlogBundle\Entity\Comment", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    public function getId()
    {
        return $this->id;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(Comment $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setUser(\Sazhin\BlogBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    public function setPost(\Sazhin\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

}