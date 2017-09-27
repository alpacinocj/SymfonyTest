<?php

namespace Mary\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", options={"default": ""})
     */
    protected $salt;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    protected $age;

    /**
     * @ORM\Column(type="integer")
     */
    protected $created_time;

    /**
     * @ORM\Column(type="integer")
     */
    protected $updated_time;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = md5($password);

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @OneToOne(targetEntity="UserProfile", mappedBy="user")
     */
    private $profile;

    /**
     * @ManyToMany(targetEntity="Book", mappedBy="users")
     */
    private $books;

    /**
     * Set profile
     *
     * @param \Mary\WebBundle\Entity\UserProfile $profile
     * @return User
     */
    public function setProfile(\Mary\WebBundle\Entity\UserProfile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Mary\WebBundle\Entity\UserProfile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return User
     */
    public function setAge($age)
    {
        $this->age = abs($age);

        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Add books
     *
     * @param \Mary\WebBundle\Entity\Book $books
     * @return User
     */
    public function addBook(\Mary\WebBundle\Entity\Book $books)
    {
        $this->books[] = $books;

        return $this;
    }

    /**
     * Remove books
     *
     * @param \Mary\WebBundle\Entity\Book $books
     */
    public function removeBook(\Mary\WebBundle\Entity\Book $books)
    {
        $this->books->removeElement($books);
    }

    /**
     * Get books
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Set created_time
     *
     * @param integer $createdTime
     * @return User
     */
    public function setCreatedTime($createdTime)
    {
        $this->created_time = $createdTime;

        return $this;
    }

    /**
     * Get created_time
     *
     * @return integer 
     */
    public function getCreatedTime()
    {
        return $this->created_time;
    }

    /**
     * Set updated_time
     *
     * @param integer $updatedTime
     * @return User
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updated_time = $updatedTime;

        return $this;
    }

    /**
     * Get updated_time
     *
     * @return integer 
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }

    /**
     * 该方法在persist()之前调用
     * @ORM\PrePersist()
     */
    public function PrePersist()
    {
        // 设置创建时间
        if (empty($this->getCreatedTime())) {
            $this->setCreatedTime(time());
        }
        $this->setUpdatedTime(time());
        // 设置盐值 TODO
        $this->setSalt('');
    }

    /**
     * @ORM\PreUpdate()
     */
    public function PreUpdate()
    {
        // 重置更新时间
        $this->setUpdatedTime(time());
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
