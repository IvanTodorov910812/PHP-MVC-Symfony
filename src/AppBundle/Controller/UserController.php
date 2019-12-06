<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;
use AppBundle\Service\FormError\FormErrorServiceInterface;
use AppBundle\Service\Profile\ProfileServiceInterface;
use AppBundle\Service\Role\RoleServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{

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
     * @Route("/mailbox", name="profile_editUpdate", methods={"GET", "POST"})
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

        return $this->render('profile/editUpdate.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
