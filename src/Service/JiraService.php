<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\IssueField;

use JiraRestApi\User\UserService;

class JiraService
{
    private UserService $userService;
    private IssueField $issueField;
    private IssueService $issueService;
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->issueField = new IssueField();
        $this->userService = new UserService();
        $this->issueService = new IssueService();
    }

    public function createTicket(User $user,string $name, string $description, string $priority, string $url, ?string $collection)
    {
//        $this->getBoard($user);
        $this->createUser($user);

        $this->issueField->setProjectKey('TEST')
            ->setSummary($name)
            ->setDescription($description)
            ->setPriorityNameAsString($priority)
            ->addCustomField('customfield_10041', $collection)
            ->addCustomField('customfield_10042', $url)
            ->addCustomField('customfield_10015', date('Y-m-d'))
            ->setIssueTypeAsString('Task')
            ->setReporterAccountId($user->getJiraAccountId());

        $issue = $this->issueService->create($this->issueField);
dd($issue);

        return $issue;
    }

    public function createUser(User $user)
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

    public function getBoard(User $user)
    {
        $jql = 'reporter = "' . $user->getJiraAccountId() . '"';
        $issueService = new IssueService();

        $ret = $issueService->search($jql);
        $issues = $ret->getIssues();

        dd($issues);
    }
}
