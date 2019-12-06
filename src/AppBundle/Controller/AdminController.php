<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;
use AppBundle\Form\UserType;
use AppBundle\Service\FormError\FormErrorServiceInterface;
use AppBundle\Service\Profile\ProfileServiceInterface;
use AppBundle\Service\Role\RoleServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;

/**
 * Class AdminController
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
    use DataTablesTrait;

    /** @var FormErrorServiceInterface $formErrorService */
    private $formErrorService;

    /** @var ProfileServiceInterface $profileService */
    private $profileService;

    /** @var RoleServiceInterface $roleService */
    private $roleService;

    /**
     * UserController constructor.
     * @param FormErrorServiceInterface $formErrorService
     * @param ProfileServiceInterface $profileService
     * @param RoleServiceInterface $roleService
     */
    public function __construct(FormErrorServiceInterface $formErrorService, ProfileServiceInterface $profileService, RoleServiceInterface $roleService)
    {
        $this->formErrorService = $formErrorService;
        $this->profileService = $profileService;
        $this->roleService = $roleService;
    }

    /**
     * Lists all users.
     *
     * @Route("/users/", name="allUsers", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function allUsers(Request $request)
    {
        // https://omines.github.io/datatables-bundle/
        $table = $this->createDataTable([
            'stateSave' => true,
            'pageLength' => 10,
            'autoWidth' => true,
            'searching' => true,
        ])
            ->add('fullName', TextColumn::class, ['label' => 'FullName'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('roles', TextColumn::class, [
                'searchable' => false,
                'label' => 'Roles',
                'render' => function ($value, $user) {
                    $output = '<ul class="list-unstyled">';
                    /** @var User $user */
                    foreach ($user->getProfileRoles() as $role) {
                        $output .= '<li>' . $role->getDescription() . '</li>';
                    }
                    $output .= '</ul>';
                    return $output;
                }
            ])
            ->add('registeredAt', DateTimeColumn::class, [
                'searchable' => false,
                'format' => 'd.M.Y H:i:s',
                'label' => 'registeredAt',
            ])
            ->add('buttons', TextColumn::class, [
                'label' => 'Modify',
                'searchable' => false,
                'className' => 'text-center',
                'render' => function ($value, $user) {
                    /** @var User $user */
                    return '<a href="' . $this->generateUrl('user_edit', ['user' => $user->getId()]) . '" class="btn btn-info" title="Modify"><i class="fa fa-cog"></i></a>';
                }
            ])
            ->add('message', TextColumn::class, [
                'label' => 'Message',
                'searchable' => false,
                'className' => 'text-center',
                'render' => function ($value, $user) {
                    /** @var User $user */
                    return '<a href="' . $this->generateUrl('user_message', ['id' => $user->getId()]) . '" class="btn btn-info" title="Reply"><i class="fa fa-reply"></i></a>';
                }
            ])

            ->createAdapter(ORMAdapter::class, [
                'entity' => User::class
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/allUser.htm.twig', [
            'datatable' => $table
        ]);
    }

    /**
     * @Route("/{user}/edit", name="user_edit", methods={"GET", "POST"}, requirements={"user": "\d+"})
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editAction(Request $request, User $user)
    {
        if ($user->getId() === $this->getUser()->getId()) {
            return $this->redirectToRoute('profile_edit');
        }

        $form = $this->createForm(ProfileType::class, $user, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (true === $this->profileService->changePassword($form, $user)) {
                    $this->addFlash('success', 'Password was succesfully changed.');
                }
            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());

                return $this->render('user/edit.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
            }

            $this->profileService->editProfile($user);
            $this->addFlash('success', 'Profile was succesfully updated.');

            return $this->redirectToRoute('user_edit', ['user' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function registerAction(Request $request, TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRole = $this->roleService->findOneBy(['name' => 'ROLE_ADMIN']);
            $user->addRole($userRole);

            $token = new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                'main',
                $user->getRoles()
            );

            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            $this->addFlash('success', 'Succesfully registered');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/user/new", name="user_new", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(ProfileType::class, $user, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->profileService->newProfile($user);
                $this->addFlash('success', 'Profile was succesfully created');

                return $this->redirectToRoute('user_edit', ['user' => $user->getId()]);

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());

                return $this->render('user/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

}
