<?php

namespace AppBundle\Form;

use AppBundle\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class)
            ->add('phone', TelType::class)
            ->add('address', TextType::class)
            ->add('email', EmailType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'contact' => null,
            'validation_groups' => function (FormInterface $form) {
                /** @var Contact $contact */
                $contact = $form->getData();
//                $oldPassword = $form->get('old_password')->getData();
//                $newPassword = $form->get('new_password')->getData();
                if (!$contact || null === $contact->getId()) {
                    return ['registration'];
                }
                return [];
            },
        ]);
    }

}
