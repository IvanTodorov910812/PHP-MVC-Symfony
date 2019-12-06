<?php

namespace AppBundle\Form;

use AppBundle\Entity\Sale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
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
            ->add('quantity', NumberType::class, [
                'grouping' => 'true',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('price', MoneyType::class, [
                'grouping' => 'true',
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
            ->add('clientName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('clientAddress', TextType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
             ])
            ->add('clientPhone', TelType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
            'sale' => null,
            'validation_groups' => function (FormInterface $form) {
                /** @var Sale $sale */
                $sale = $form->getData();
//                $oldPassword = $form->get('old_password')->getData();
//                $newPassword = $form->get('new_password')->getData();
                if (!$sale || null === $sale->getId()) {
                    return ['registration'];
                }
                return [];
            },
        ]);
    }

}
