<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Meal;
use AppBundle\Entity\Menu;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MealController extends Controller
{

    /**
     * @Route("/meal/{id}/add/ingredient/{ingredientName}", name="meal_add_ingredient")
     * @param string $ingredientName
     * @param Meal $meal
     * @return Response
     */
    public function addIngredient(Meal $meal, $ingredientName)
    {
        return new Response("$ingredientName");
    }

    /**
     * @Route("/meal/add", name="meal_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addMeal(Request $request)
    {
        // creates a meal and gives it some dummy data for this example
        $meal = new Meal();

        $mealForm = $this->createFormBuilder($meal)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Meal'))
            ->getForm();

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

        return $this->render('meal/add.html.twig', array(
            'addMealForm' => $mealForm->createView(),
        ));
    }
}