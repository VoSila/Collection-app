<?php

namespace App\Controller;

use App\Service\JiraService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/ticket')]
class TicketController extends AbstractController
{

    public function __construct(
        private readonly JiraService         $jiraService,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    #[Route('/', name: 'app_ticket')]
    public function index(Request $request): Response
    {
        $userId = $this->jiraService->checkUser();
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $tickets = $this->jiraService->getTickets($user);
        $pagination = $this->jiraService->getPagination($tickets, $request->query->getInt('page', 1));

        $url = $this->jiraService->generateJiraLink($user->getJiraAccountId());
        return $this->render('ticket/index.html.twig', [
            'pagination' => $pagination,
            'url' => $url
        ]);
    }

    #[Route('/create', name: 'app_ticket_create')]
    public function createTicket(Request $request): Response
    {
        $user = $this->getUser();

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $priority = $request->request->get('priority');
        $url = $request->server->get('HTTP_REFERER');
        $collection = $request->request->get('collection');

        try {
            $this->jiraService->createTicket($user, $name, $description, $priority, $url, $collection);
            $this->addFlash('success', $this->translator->trans('successCreateTicket'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('errorCreateTicket'));
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('homepage'));
    }
}
