<?php

namespace Mary\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="UserProfileRepository")
 * @ORM\Table(name="user_profile")
 */
class UserProfile
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $mobile;


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
     * Set mobile
     *
     * @param integer $mobile
     * @return UserProfile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return integer 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @OneToOne(targetEntity="User", inversedBy="profile")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Set user
     *
     * @param \Mary\WebBundle\Entity\User $user
     * @return UserProfile
     */
    public function setUser(\Mary\WebBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Mary\WebBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
