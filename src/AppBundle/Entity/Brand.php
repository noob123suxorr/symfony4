<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;

/**
 * Brand
 *
 * @ORM\Table(name="brands",uniqueConstraints={@UniqueConstraint(name="brand_name", columns={"name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BrandRepository")
 */
class Brand
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 50)
     * @Assert\Regex("/[a-zA-Z ]+/")
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Model", mappedBy="brandId")
     */
    private $models;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Car", mappedBy="brand")
     */
    private $cars;

    public function __construct()
    {
        $this->models = new ArrayCollection();
        $this->cars = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Brand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

