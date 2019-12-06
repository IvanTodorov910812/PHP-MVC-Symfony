<?php

namespace AppBundle\Form;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

/**
 * Class ProfileType
 * @package AppBundle\Form
 *
 */
class ProfileType extends AbstractType
{
    /** @var Security $security */
    private $security;

    /**
     * ProfileType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $currentUser */
        $currentUser = $options['user'];

        $builder
            ->add('fullName', TextType::class, [
                'label' => 'FullName',
                'attr' => [
                    'placeholder' => 'FullName',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ]
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($currentUser) {
            /** @var User $user */
            $user = $event->getData();
            $form = $event->getForm();
            if (!$user || null === $user->getId()) {
                $form->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'Password mismatch.',
                        'first_options' => [
                            'label' => 'Password',
                            'attr' => [
                                'placeholder' => 'Password',
                                'class' => 'form-control'
                            ],
                        ],
                        'second_options' => [
                            'label' => 'Password repeat',
                            'attr' => [
                                'placeholder' => 'Password repeat',
                                'class' => 'form-control'
                            ]
                        ],
                    ]
                );
            } else {
                $form->add('old_password', PasswordType::class, [
                    'mapped' => false,
                    'label' => 'Old password',
                    'attr' => [
                        'placeholder' => 'Old Password',
                        'class' => 'form-control'
                    ]
                ])
                    ->add('new_password', PasswordType::class, [
                        'mapped' => false,
                        'label' => 'New Password',
                        'attr' => [
                            'placeholder' => 'New Password',
                            'class' => 'form-control'
                        ],
                        'constraints' => [

                        ]
                    ]);
            }

            if ($this->security->isGranted(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']) && $user->getId() !== $currentUser->getId()) {
                $form->add('profileRoles', EntityType::class, [
                    'label' => 'Roles',
                    'class' => Role::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('r')
                            ->where('r.name != :name')
                            ->setParameter('name', 'ROLE_SUPER_ADMIN')
                            ->orderBy('r.position', 'ASC');
                    },
                    'choice_label' => 'description',
                    'multiple' => true,
                    'expanded' => true,
                    'attr' => ['class' => 'checkbox_container']
                ]);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => null,
            'validation_groups' => function (FormInterface $form) {
                /** @var User $user */
                $user = $form->getData();
//                $oldPassword = $form->get('old_password')->getData();
//                $newPassword = $form->get('new_password')->getData();
                if (!$user || null === $user->getId()) {
                    return ['registration'];
                }
                return [];
            },
        ]);
    }
}
