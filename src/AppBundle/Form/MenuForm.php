<?php


namespace AppBundle\Form;


use AppBundle\Entity\Meal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('meal', EntityType::class, array(
            // looks for choices from this entity
            'class' => Meal::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'name',
            'choice_value' => 'id',

            // used to render a select box, check boxes or radios
            'multiple' => false,
            'expanded' => false,
            'required'   => false,
            'label' => ''
        ))->add('id', HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Menu'
        ]);
    }
}