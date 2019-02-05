<?php
namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\MessageService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @param MessageService $messageService
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, MessageService $messageService, PaginatorInterface $paginator)
    {
        return $this->render('guestbook/index.html.twig', [
            'pagination' => $messageService->search($request, $paginator)
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $message->setIp($request->getClientIp());
            $message->setUserAgent($request->headers->get('user-agent'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirect('/');
        }

        return $this->render('guestbook/message-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/view/{id}", name="view")
     * @param int $id
     * @param MessageService $messageService
     * @return Response
     */
    public function view(int $id, MessageService $messageService)
    {
        $message = $messageService->findById($id);

        return $this->render('guestbook/view.html.twig', [
            'message' => $message,
        ]);
    }
}