<?php

namespace AppBundle\Form;

use AppBundle\Entity\Delivery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('barcode', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('measure', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' =>
                [
                    '' => null,
                    'Gram' => 'Gram',
                    'kg' => 'kg',
                    'Count' => 'Count',
                    'Pack' => 'Pack'
                ]
            ])
            ->add('price', MoneyType::class, [
                'grouping' => 'true',
                'attr' => [
                    'class' => 'form-control',
                ]])
            ->add('bestToDate', DateType::class, [
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'empty_data' => '',
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
            'delivery' => null,
            'validation_groups' => function (FormInterface $form) {
                /** @var Delivery $delivery */
                $delivery = $form->getData();
//                $oldPassword = $form->get('old_password')->getData();
//                $newPassword = $form->get('new_password')->getData();
                if (!$delivery || null === $delivery->getId()) {
                    return ['registration'];
                }
                return [];
            },
        ]);
    }

}
