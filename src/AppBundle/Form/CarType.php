<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', EntityType::class, [
                'required' => true,
                'choice_value' => 'id',
                'placeholder' => '',
                'choice_label' => 'name',
                'class' => 'AppBundle:Brand'
            ])
            ->add('model', EntityType::class, [
                'required' => true,
                'choice_attr' => function ($val, $key, $index) {
                    return [
                        'data-brand' => $val->getBrandId()->getId()
                    ];
                },
                'choice_value' => 'id',
                'placeholder' => '',
                'choice_label' => 'name',
                'class' => 'AppBundle:Model'
            ])
            ->add('engine', EntityType::class, [
                'required' => true,
                'placeholder' => '',
                'choice_value' => 'id',
                'choice_label' => 'type',
                'class' => 'AppBundle:Engine'
            ])
            ->add('color', EntityType::class, [
                'required' => true,
                'placeholder' => '',
                'choice_value' => 'id',
                'choice_label' => 'name',
                'class' => 'AppBundle:Color'
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'maxlength' => 9,
                    'minlength' => 2,
                    'placeholder' => 'Price..'
                ],
                'divisor' => 1
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Image'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Car'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_car';
    }


}