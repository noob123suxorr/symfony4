<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;

/**
 * Model
 *
 * @ORM\Table(name="models",uniqueConstraints={@UniqueConstraint(name="model_name", columns={"name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModelRepository")
 */
class Model
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
     * @ORM\Column(name="name", type="string", length=80)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 80)
     * @Assert\Regex("/[a-zA-Z ]+/")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="models")
     * @ORM\JoinColumn(name="brandId", referencedColumnName="id")
     */
    private $brandId;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Car", mappedBy="model")
     */
    private $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
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
     * @return Model
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

    /**
     * Get brand_id
     *
     * @return string
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * Set brand_id
     *
     * @param string $brand_id
     */
    public function setBrandId($brand_id)
    {
        $this->brandId = $brand_id;
    }
}

