<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="meal")
 */
class Meal
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int $id
     */
    private $id;



    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="meal")
     */
    private $menus;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string $name
     */
    private $name;

    /**
     * @var Ingredient $ingredients
     */
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

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
     * @return Menu[]
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @param Menu[] $menus
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;
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
     * @return Ingredient
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param Ingredient $ingredients
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;
    }
}