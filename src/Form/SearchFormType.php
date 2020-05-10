<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q',TextType::class,([
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'aria-describedby' => 'basic-addon1',
                    'class' => 'form-control'
                ]
            ]))
            ->add('min',MoneyType::class,([
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix minimum..',
                ]
            ]))
            ->add('max',MoneyType::class,([
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix maximum..',
                ]
            ]))
            ->add('room',ChoiceType::class,([
                'label' => false,
                'placeholder' => '...',
                'multiple'=>true,
                'expanded'=>true,
                'required' => false,
                'choices' => [
                    '1 piece' => 1,
                    '2 pieces' => 2,
                    '3 pieces' => 3,
                    '4 pieces' => 4,
                    '5 pieces' => 5
                ]
            ]))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
