<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('coverimg', null, [
                'label' => 'Image'
            ])
            ->add('description', null, [
                'label' => 'Introduction'
            ])
            ->add('content', null, [
                'label' => 'Descrption'
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'divisor' => 1,
                'scale' => 0
            ])
            ->add('room', null, [
                'label' => 'Pieces'
            ])
            ->add('online', CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
                'label' => 'En ligne?'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
