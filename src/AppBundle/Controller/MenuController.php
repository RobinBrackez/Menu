<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use AppBundle\Form\MenuForm;
use AppBundle\Repository\IngredientRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
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

        /** @var Menu[] $menus */
        $menus = $this->getMenuRepository()->findAll();

        $menuData = [];

        foreach ($menus as $menu) {
            $menuDataObject['form'] = $this->createForm(MenuForm::class, $menu, [
                'action' => $this->generateUrl("menu-set-meal", ['menuId' => $menu->getId()]),
                'method' => 'POST',
            ]);
            $menuDataObject['menu'] = $menu;

            $menuData[] = $menuDataObject;
        }

        return $this->render('menu/index.html.twig', array(
            'menuData' => $menuData
        ));
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

    /**
     * @Route("/menu/{menuId}/set-meal/", name="menu-set-meal", methods={"POST"})
     */
    public function setMeal(Request $request, $menuId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Menu $menu */
        $menu = $em->getRepository('AppBundle:Menu')->find($menuId);

        if (!$menu){
            return $this->redirect('/');
        }

        $form = $this->createForm(MenuForm::class, $menu);
        // only handles data on POST
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

            $session = new Session();
            $session->getFlashBag()->add('success', 'Menu updated. You now eat ' . $menu->getMeal()->getName());
            return $this->redirect('/');
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