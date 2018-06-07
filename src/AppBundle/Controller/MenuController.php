<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use AppBundle\Form\MenuForm;
use AppBundle\Service\MenuService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
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
        /** @var MenuService $menuService */
        $menuService = $this->container->get("app.service.menu_service");
        $menuService->createMenusForDates();

        /** @var Menu[] $menus */
        $menus = $menuService->getActiveMenus();

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

}