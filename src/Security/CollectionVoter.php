<?php

namespace App\Security;

use App\Entity\ItemCollection;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function Symfony\Component\Translation\t;

class CollectionVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    public function __construct(private Security $security)
    {
    }
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof ItemCollection) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var ItemCollection $collection */
        $collection = $subject;

        return match($attribute) {
            self::VIEW => $this->canView($collection, $user),
            self::EDIT => $this->canEdit($collection, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(ItemCollection $collection, User $user): bool
    {
        if ($this->canEdit($collection, $user ) && $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        return true;

//        dd($collection);
//        return !$collection->isPrivate();
    }

    private function canEdit(ItemCollection $collection, User $user): bool
    {
        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        return $user === $collection->getUser();
    }
}
