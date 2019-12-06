<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Supplier;
use AppBundle\Form\SupplierType;
use AppBundle\Service\FormError\FormErrorServiceInterface;
use AppBundle\Service\Supplier\SupplierServiceInterface;
use AppBundle\Service\Role\RoleServiceInterface;
use Exception;
use Omines\DataTablesBundle\Column\NumberColumn;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;

class SupplierController extends Controller
{

    use DataTablesTrait;

    /** @var FormErrorServiceInterface $formErrorService */
    private $formErrorService;

    /** @var SupplierServiceInterface $supplierService */
    private $supplierService;

    /** @var RoleServiceInterface $roleService */
    private $roleService;

    /**
     * UserController constructor.
     * @param FormErrorServiceInterface $formErrorService
     * @param SupplierServiceInterface $supplierService
     * @param RoleServiceInterface $roleService
     */
    public function __construct(FormErrorServiceInterface $formErrorService, SupplierServiceInterface $supplierService, RoleServiceInterface $roleService)
    {
        $this->formErrorService = $formErrorService;
        $this->supplierService = $supplierService;
        $this->roleService = $roleService;
    }
    /**
     *
     *
     * @Route("/suppliers/", name="allSuppliers", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function allSuppliers(Request $request)
    {
        // https://omines.github.io/datatables-bundle/
        $table = $this->createDataTable([
            'stateSave' => true,
            'pageLength' => 10,
            'autoWidth' => true,
            'searching' => true,
        ])
            ->add('id', TextColumn::class, ['label' => '#'])
            ->add('name', TextColumn::class, ['label' => 'Name'])
            ->add('description', TextColumn::class, ['label' => 'About'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('telefon', NumberColumn::class, ['label' => 'Phone'])
            ->add('city', TextColumn::class, ['label' => 'City'])
            ->add('street', TextColumn::class, ['label' => 'Street'])
            ->add('createdOn', DateTimeColumn::class, [
                'searchable' => false, // Important - make datetime col. non-searchable!
                'format' => 'd.M.Y',
                'label' => 'registeredAt',
            ])
            ->add('buttons', TextColumn::class, [
                'label' => 'Modify',
                'searchable' => false,
                'className' => 'text-center',
                'render' => function ($value, $supplier) {
                    /** @var Supplier $supplier */
                    return '<a href="' . $this->generateUrl('supplier_edit', ['supplier' => $supplier->getId()]) . '" class="btn btn-info" title="Modify"><i class="fa fa-cog"></i></a>';
                }
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Supplier::class
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('supplier/Allsuppliers.html.twig', [
            'datatable' => $table

        ]);

    }

    /**
     * @Route("/suppliers/edit/{supplier}", name="supplier_edit", methods={"GET", "POST"}, requirements={"supplier": "\d+"})
     * @param Request $request
     * @param Supplier $supplier
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editAction(Request $request, Supplier $supplier)
    {
//        if ($supplier->getId() === $this->getUser()->getId()) {
//            return $this->redirectToRoute('supplier_edit');
//        }
        /** @var Supplier $supplier */
        $supplier->getId();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->supplierService->editSupplier($supplier);
            $this->addFlash('success', 'Supplier was succesfully updated.');

            return $this->redirectToRoute('allSuppliers');
        }

        return $this->render('supplier/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/supplier/new", name="newSupplier", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->supplierService->newSupplier($supplier);
                $this->addFlash('success', 'Supplier was succesfully created.');

                return $this->redirectToRoute('supplier_edit', ['supplier' => $supplier->getId()]);

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());

                return $this->render('supplier/addNewSupplier.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('supplier/addNewSupplier.twig', [
            'form' => $form->createView()

        ]);

    }

    /**
     * @Route("/supplier/delete/{supplier}", name="supplierDelete", requirements={"supplier": "\d+"})
     * @param Supplier $supplier
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteAction(Request $request, Supplier $supplier)
    {
        $supplier->getId();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->supplierService->deleteSupplier($supplier);
            $this->addFlash('success', 'Supplier was succesfully deleted pernament.');

            return $this->redirectToRoute('allSuppliers');
        }

        return $this->render('supplier/delete.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView()
        ]);

    }

}
