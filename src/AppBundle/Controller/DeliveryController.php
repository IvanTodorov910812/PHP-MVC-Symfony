<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Delivery;
use AppBundle\Entity\Document;
use AppBundle\Form\DeliveryType;
use AppBundle\Service\Delivery\DeliveryServiceInterface;
use AppBundle\Service\FormError\FormErrorServiceInterface;
use Doctrine\DBAL\Connection;
use Exception;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends Controller
{
    use DataTablesTrait;

    /** @var FormErrorServiceInterface $formErrorService */
    private $formErrorService;

    /** @var DeliveryServiceInterface $deliveryService */
    private $deliveryService;

    /**
     * DeliveryController constructor.
     * @param FormErrorServiceInterface $formErrorService
     * @param DeliveryServiceInterface $deliveryService
     */
    public function __construct(FormErrorServiceInterface $formErrorService, DeliveryServiceInterface $deliveryService)
    {
        $this->formErrorService = $formErrorService;
        $this->deliveryService = $deliveryService;
    }

    /**
     *
     *
     * @Route("/deliveries/", name="allDeliveries", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function allDeliveries(Request $request)
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
            ->add('barcode', TextColumn::class, ['label' => 'Barcode'])
            ->add('measure', TextColumn::class, ['label' => 'Measure'])
            ->add('quantity', TextColumn::class, ['label' => 'Quantity'])
            ->add('price', TextColumn::class, ['label' => 'Price'])
            ->add('totalCost', TextColumn::class, ['label' => 'Cost[total]'])
            ->add('dateAdded', DateTimeColumn::class, ['format' => 'd.M.Y','label' => 'IncomeDay'])
            ->add('bestToDate', DateTimeColumn::class, ['format' => 'd.M.Y', 'label' => 'Best to'])
//            ->add('dateAdded', DateTimeColumn::class, [
//                'searchable' => false, // Important - make datetime col. non-searchable!
//                'format' => 'd.M.Y ',
//                'label' => 'Income Day',
//            ])
            ->add('message', TextColumn::class, [
                'label' => 'View',
                'searchable' => false,
                'className' => 'text-center',
                'render' => function ($value, $delivery) {
                    /** @var Delivery $delivery */
                    return '<a href="' . $this->generateUrl('deliveryView', ['id' => $delivery->getId()]) . '" class="btn btn-info" title="Open"><i class="fa fa-info-circle"></i></a>';
                }
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Delivery::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('delivery/AllDeliveries.html.twig', [
            'datatable' => $table

        ]);

    }


    /**
     * @Route("/deliveries/viewDelivery/{id}", name="deliveryView", methods={"GET", "POST"}, requirements={"id": "\d+"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function viewDelivery(Request $request, $id){

        $delivery = $this
            ->getDoctrine()
            ->getRepository(Delivery::class)
            ->find($id);

        $documentURL = $this
            ->getDoctrine()
            ->getRepository(Document::class)
            ->findOneBy(['delivery' => $delivery]);

//        var_dump($documentURL);exit;
//        $documentURL->getFileUrl();

            return $this->redirect($documentURL->getFileUrl(), '302');

    }

//    /**
//     * @Route("/deliveries/edit/{delivery}", name="delivery_edit", methods={"GET", "POST"}, requirements={"delivery": "\d+"})
//     * @param Request $request
//     * @param Delivery $delivery
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @throws \Exception
//     */
//    public function editAction(Request $request, Delivery $delivery)
//    {
////        if ($supplier->getId() === $this->getUser()->getId()) {
////            return $this->redirectToRoute('supplier_edit');
////        }
//        /** @var Delivery $delivery */
//        $delivery->getId();
//        $form = $this->createForm(DeliveryType::class, $delivery);
//        $form->handleRequest($request);
//
//        $this->formErrorService->checkErrors($form);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->deliveryService->editDelivery($delivery);
//            $this->addFlash('success', 'Delivery was succesfully added pernament.');
//
//            return $this->redirectToRoute('allDeliveries');
//        }
//
//        return $this->render('delivery/edit.html.twig', [
//            'delivery' => $delivery,
//            'form' => $form->createView()
//        ]);
//    }

    /**
     * Autocomplete for the Barcode field with JMS_serializer BUNDLE & jQuery Autocomplete
     * @Route("/barcode", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function searchByBarcode(Request $request){

        $term = $request->get('term');
        /** @var Connection $db */
        $db = $this->getDoctrine()->getConnection();
        $barcodes = $db->fetchAll('SELECT barcode
                                            FROM deliveries
                                          WHERE barcode LIKE :term
                                          LIMIT 5', ['term' => $term.'%']);

        return $this->jsonResponse($barcodes);
    }

    // JMS Bundle for transform the data in JSON format
    private  function jsonResponse($data) : Response
    {
       $serializator = $this->get('jms_serializer');
       return new Response($serializator->serialize($data, 'json'), '200');
    }


    /**
     * @Route("/delivery/new", name="newDelivery", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $delivery = new Delivery();
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        $this->formErrorService->checkErrors($form);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->deliveryService->newDelivery($request, $delivery);
                $this->addFlash('success', 'Delivery was succesfully created.');

                return $this->redirectToRoute('allDeliveries');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());

                return $this->render('delivery/NewDelivery.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('delivery/NewDelivery.html.twig', [
            'form' => $form->createView()

        ]);

    }

}
