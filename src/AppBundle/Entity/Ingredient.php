<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="ingredient")
 */
class Ingredient
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int $id
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=100)
     * @var string $name
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Meal")
     * @var Meal[] $meals
     */
    private $meals;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Meal[]
     */
    public function getMeals()
    {
        return $this->meals;
    }

    /**
     * @param Meal[] $meals
     */
    public function setMeals($meals)
    {
        $this->meals = $meals;
    }



}