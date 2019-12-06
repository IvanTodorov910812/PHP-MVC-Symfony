<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sale;
use AppBundle\Form\SaleType;
use AppBundle\Service\FormError\FormErrorServiceInterface;
use AppBundle\Service\Sale\SaleServiceInterface;
use Doctrine\DBAL\Connection;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaleController extends Controller
{
    use DataTablesTrait;

    /** @var FormErrorServiceInterface $formErrorService */
    private $formErrorService;

    /** @var SaleServiceInterface $saleService */
    private $saleService;

    /**
     * DeliveryController constructor.
     * @param FormErrorServiceInterface $formErrorService
     * @param SaleServiceInterface $saleService
     */
    public function __construct(FormErrorServiceInterface $formErrorService, SaleServiceInterface $saleService)
    {
        $this->formErrorService = $formErrorService;
        $this->saleService = $saleService;
    }

    /**
     *
     *
     * @Route("/sales/", name="allSales", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function allSales(Request $request)
    {
        // https://omines.github.io/datatables-bundle/
        $table = $this->createDataTable([
            'stateSave' => true,
            'pageLength' => 10,
            'autoWidth' => true,
            'searching' => true,
        ])
            ->add('id', TextColumn::class, ['label' => '#'])
            ->add('productName', TextColumn::class, ['label' => 'Product'])
            ->add('measure', TextColumn::class, ['label' => 'Measure'])
            ->add('quantity', TextColumn::class, ['label' => 'Quantity'])
            ->add('price', TextColumn::class, ['label' => 'Price'])
            ->add('totalSum', TextColumn::class, ['label' => 'Total[netto]'])
            ->add('tax', TextColumn::class, ['label' => 'Tax(10%)'])
            ->add('clientName', TextColumn::class, ['label' => 'Client'])
            ->add('soldOutOn', DateTimeColumn::class, [
                'searchable' => false, // Important - make datetime col. non-searchable!
                'format' => 'd.M.Y ',
                'label' => 'Sold Out On',
            ])
            ->add('buttons', TextColumn::class, [
                'label' => 'Bill',
                'searchable' => false,
                'className' => 'text-center',
                'render' => function ($value, $sale) {
                    /** @var Sale $sale */
                    return '<a href="' . $this->generateUrl('viewBillForSale', ['id' => $sale->getId()]) . '" class="btn btn-info" title="Show"><i class="fa fa-info-circle"></i></a>';
                }
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Sale::class
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('sales/allSales.html.twig', [
            'datatable' => $table

        ]);

    }

    /**
     * @Route("/sales/viewBill/{id}", name="viewBillForSale", methods={"GET", "POST"}, requirements={"id": "\d+"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function viewBillsForSale(Request $request, $id){

        $sale = $this
            ->getDoctrine()
            ->getRepository(Sale::class)
            ->find($id);
//        var_dump($sale);exit;
        return $this->render("sales/view/view.html.twig",
            ['sale' => $sale]);
    }

//    /**
//     * @Route("/sales/edit/{sale}", name="sale_edit", methods={"GET", "POST"}, requirements={"sale": "\d+"})
//     * @param Request $request
//     * @param Sale $sale
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @throws \Exception
//     */
//    public function editAction(Request $request, Sale $sale)
//    {
////        if ($supplier->getId() === $this->getUser()->getId()) {
////            return $this->redirectToRoute('supplier_edit');
////        }
//        /** @var Sale $sale */
//        $sale->getId();
//        $form = $this->createForm(SaleType::class, $sale);
//        $form->handleRequest($request);
//
//        $this->formErrorService->checkErrors($form);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->saleService->editSale($sale);
//            $this->addFlash('success', 'Sale was succesfully added pernament.');
//
//            return $this->redirectToRoute('allSales');
//        }
//
//        return $this->render('delivery/edit.html.twig', [
//            'sale' => $sale,
//            'form' => $form->createView()
//        ]);
//    }

    /**
     * Autocomplete for the ProductName field with JMS_serializer BUNDLE & jQuery Autocomplete
     *
     * @Route("/salesProductName", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function searchByProductName(Request $request){

        $term = $request->get('term');
        /** @var Connection $db */
        $db = $this->getDoctrine()->getConnection();
        $soldProducts = $db->fetchAll('SELECT productName
                                            FROM sales
                                          WHERE productName LIKE :term
                                          LIMIT 5', ['term' => $term.'%']);

        return $this->jsonResponse($soldProducts);
    }

    // JMS Bundle for transform the data in JSON format
    private  function jsonResponse($data) : Response
    {
        $serializator = $this->get('jms_serializer');
        return new Response($serializator->serialize($data, 'json'), '200');
    }


    /**
     * @Route("/sale/new", name="newSale", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $sale = new Sale();
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->saleService->newSale($sale);
                $this->addFlash('success', 'Sale was succesfully created.');

                return $this->redirectToRoute('allSales');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());

                return $this->render('sales/newSale.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('sales/newSale.html.twig', [
            'form' => $form->createView()

        ]);

    }
}
