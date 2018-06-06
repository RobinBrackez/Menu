<?php


namespace AppBundle\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="menu")
 */
class Menu
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int $id
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @var DateTime $date
     */
    private $date;


    /**
     * @ORM\ManyToMany(targetEntity="Meal")
     * @ORM\JoinTable(name="meal_menu")
     * @var Meal[] $meals;
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
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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