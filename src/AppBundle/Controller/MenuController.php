<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use DateInterval;
use DatePeriod;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{
    /**
     * @Route("/menu/index")
     * @Route("/menu")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->createMenusForDates();
        $mealsForm = $this->getMealsForm();

        /** @var Menu[] $menus */
        $menus = $this->getMenuRepository()->findAll();

        return $this->render('menu/index.html.twig', array(
            'menus' => $menus,
            'mealForm' => $mealsForm
        ));
    }


// if you have multiple entity managers, use the registry to fetch them
    public function editAction()
    {
        $menu = $this->getMenuRepository()
            ->find($productId);

        if (!$menu) {
            throw $this->createNotFoundException(
                'No menu found for id '.$productId
            );
        }
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getMenuRepository()
    {
        return $this->getDoctrine()
            ->getRepository(Menu::class);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getMealsRepository()
    {
        return $this->getDoctrine()
            ->getRepository(Meal::class);
    }


    public function updateAction($menuId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $menu = $entityManager->getRepository(Menu::class)->find($menuId);

        if (!$menu) {
            throw $this->createNotFoundException(
                'No product found for id '.$menuId
            );
        }

        $menu->setName('New menu name!');
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/menu/new")
     */
    public function newAction(Request $request)
    {
        // creates a menu and gives it some dummy data for this example
        $menu = new Menu();
        $menu->setDate(new DateTime("now"));

        $form = $this->createFormBuilder($menu)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Menu'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $menu = $form->getData();

            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to your action: createAction(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($menu);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new product with id '.$menu->getId());
        }


        return $this->render('menu/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getMealsForm()
    {
        $mealsForm = $this->createFormBuilder()
            ->add('meals', EntityType::class, array(
                // looks for choices from this entity
                'class' => Meal::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('menu', HiddenType::class)
            ->setAction($this->generateUrl("menu-set-meal"))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->getForm();
        return $mealsForm;
    }

    /**
     * @Route("/menu/set-meal/", name="menu-set-meal")
     */
    public function setMeal(Request $request)
    {
        $mealForm = $this->getMealsForm();

        $mealForm->handleRequest($request);

        if ($mealForm->isSubmitted() && $mealForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $meal = $mealForm->getData();

            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to your action: createAction(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($meal);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new meal with id '. $meal->getId());
        }
    }

    /**
     * Returns every date between two dates as an array
     * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
     */
    public function getDateRange()
    {
        $startDate = new DateTime();
        $startDate->add(DateInterval::createFromDateString('yesterday'));

        $endDate = new DateTime();
        $endDate->add(new DateInterval("P10D"));

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($startDate, $interval, $endDate);

        $range = [];
        foreach ($dateRange as $date) {
            $range[$date->format("Y-m-d")] = $date;
        }

        return $range;
    }


    /**
     * @param $dates
     * @param $menus
     */
    protected function createMenusForDates()
    {
        $dates = $this->getDateRange();
        /** @var Menu[] $menus */
        $menus = $this->getMenuRepository()->findAll();
        foreach ($dates as $dateString => $date) {
            $found = false;
            foreach ($menus as $menu) {
                if ($menu->getDate()->format('Y-m-d') === $date->format('Y-m-d')) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // you can fetch the EntityManager via $this->getDoctrine()
                // or you can add an argument to your action: createAction(EntityManagerInterface $entityManager)
                $entityManager = $this->getDoctrine()->getManager();

                $menu = new Menu();
                $menu->setDate($date);
                // tells Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($menu);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
            }
        }
    }
}