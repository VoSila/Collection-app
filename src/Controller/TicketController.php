<?php

namespace App\Controller;

use App\Service\JiraService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TicketController extends AbstractController
{
    public function __construct(
        private readonly JiraService $jiraService
    )
    {
    }

    #[Route('/create-ticket', name: 'create_ticket')]
    public function createTicket(Request $request): Response
    {
        $user = $this->getUser();

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $priority = $request->request->get('priority');
        $url = $request->server->get('HTTP_REFERER');
        $collection = $request->request->get('collection');

        $issue = $this->jiraService->createTicket($user, $name, $description, $priority, $url, $collection);
//dd($issue);
        return $this->render('main/index.html.twig', [
            'issue' => $issue
        ]);
    }
}
