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
     * @ORM\ManyToOne(targetEntity="Meal")
     * @var Meal $meal;
     */
    private $meal;

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
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * @param Meal[] $meal
     */
    public function setMeal($meal)
    {
        $this->meal = $meal;
    }


}