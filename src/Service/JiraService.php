<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use JiraRestApi\User\UserService;
use JsonMapper_Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class JiraService
{
    private UserService $userService;
    private IssueField $issueField;
    private IssueService $issueService;
    private string $jiraBaseUrl;
    private string $projectKey;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security,
        private readonly PaginatorInterface     $paginator,
        string                                  $jiraBaseUrl,
        string                                  $projectKey
    )
    {
        $this->issueField = new IssueField();
        $this->userService = new UserService();
        $this->issueService = new IssueService();
        $this->jiraBaseUrl = $jiraBaseUrl;
        $this->projectKey = $projectKey;
    }

    /**
     * @throws JsonMapper_Exception
     * @throws JiraException
     */
    public function createTicket($user, string $name, string $description, string $priority, string $url, ?string $collection)
    {
        if (!$user->getJiraAccountId()) {
            $this->createUser($user);
        }

        $this->issueField->setProjectKey('TEST')
            ->setSummary($name)
            ->setDescription($description)
            ->setPriorityNameAsString($priority)
            ->addCustomField('customfield_10041', $collection)
            ->addCustomField('customfield_10042', $url)
            ->addCustomField('customfield_10015', date('Y-m-d'))
            ->setIssueTypeAsString('Task')
            ->setReporterAccountId($user->getJiraAccountId());

        return $this->issueService->create($this->issueField);
    }

    public function createUser(User $user): void
    {
        $userJira = $this->userService->create([
            'emailAddress' => $user->getEmail(),
            'products' => ['jira-core', 'jira-servicedesk', 'jira-software']
        ]);

        $accountId = $userJira->accountId;
        $user->setJiraAccountId($accountId);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws JsonMapper_Exception
     * @throws JiraException
     */
    public function getTickets($user): array
    {
        $jql = 'reporter = "' . $user->getJiraAccountId() . '"';
        $issueService = new IssueService();

        $ret = $issueService->search($jql);

        return $ret->getIssues();
    }


    public function generateJiraLink(string $accountId): string
    {
        $jqlQuery = '"reporter" = \'' . $accountId . '\'';

        $encodedJqlQuery = urlencode($jqlQuery);

        return $this->jiraBaseUrl . "/jira/core/projects/" . $this->projectKey . "/board?filter=" . $encodedJqlQuery;
    }

    public function checkUser()
    {
        $user = $this->security->getUser();

        if (!$user) {
            return false;
        } else {
            return $user->getId();
        }
    }

    public function getPagination(array $ticket, int $request): PaginationInterface
    {
        return $this->paginator->paginate(
            $ticket,
            $request,
            5
        );
    }
}
