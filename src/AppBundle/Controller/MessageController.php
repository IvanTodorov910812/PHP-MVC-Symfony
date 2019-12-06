<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Form\MessageType;
use AppBundle\Service\FormError\FormErrorServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class MessageController
 * @package AppBundle\Controller
 */
class MessageController extends Controller
{

    use DataTablesTrait;

    /** @var FormErrorServiceInterface $formErrorService */
    private $formErrorService;

    /** @var MessageServiceInterface $messageService */
    private $messageService;

    /**
     * @param FormErrorServiceInterface $formErrorService
     * @param MessageServiceInterface $messageService
     */
    public function __construct(FormErrorServiceInterface $formErrorService, MessageServiceInterface $messageService)
    {
        $this->formErrorService = $formErrorService;
        $this->messageService = $messageService;
    }

    /**
     * @Route("/{id}/message", name="user_message", methods={"GET", "POST"}, requirements={"id": "\d+"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addMessageAction(Request $request, $id)
    {
//        $articleIdFromRequest = substr($_SERVER['HTTP_REFERER'],
//            strrpos($_SERVER['HTTP_REFERER'], '/') + 1);

        $currentUser = $this->getUser();

        $recipient = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $message
                ->setSender($currentUser)
                ->setRecipient($recipient)
                ->setIsReader(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent successfully!");

            return $this->redirectToRoute("allUsers");
        }

        return $this->render('user/send_message.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/mailbox", name="user_mailbox")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailBox(Request $request){

        $currentUserId = $this->getUser()->getId();

        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($currentUserId);

        $messages = $this
            ->getDoctrine()
            ->getRepository(Message::class)
            //findBy(array $criteria,array $orderBy)
            ->findBy(['recipient' => $user],['dateAdded' => 'DESC']);

        return $this->render("user/mailbox.html.twig",
            ['messages' => $messages]);
    }

    /**
     * @Route("/mailbox/message/{id}", name="user_current_message")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messageAction(Request $request, $id){

        $message = $this
            ->getDoctrine()
            ->getRepository(Message::class)
            ->find($id);

        $message->setIsReader(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $sendMessage = new Message();
        $form = $this->createForm(MessageType::class, $sendMessage);
        $form->handleRequest($request);

//        var_dump($message);
//        exit;

        if($form->isSubmitted()){
            $sendMessage
                ->setSender($this->getUser())
                ->setRecipient($message->getSender())
                ->setIsReader(false);

            $em->persist($sendMessage);
            $em->flush();

            $this->addFlash("message", "Message sent successfully!");

            return $this->redirectToRoute("user_current_message", ['id' => $id]);
        }


        return $this->render("user/message.html.twig",
            ['message' => $message, 'form' => $form->createView()]);
    }

}
