<?php


namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add("fact", ChoiceType::class, [
            'choices' => [
                'Yes' => true,
                'No'  => false
            ]
        ]);
    }

    public function newAction(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Ingredient']);
    }
}