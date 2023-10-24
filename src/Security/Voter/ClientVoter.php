<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientVoter extends Voter
{
    public const DELETE = 'delete';
    public const VIEW_LIST = 'view_list';
    public const VIEW_DETAIL = 'view_detail';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE, self::VIEW_LIST, self::VIEW_DETAIL])) {
            return false;
        }

        // only vote on `Client` objects
        if (!$subject instanceof Client && $attribute !== self::VIEW_LIST) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        /** @var Client $client */
        $client = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($client, $user);
            case self::VIEW_LIST:
                return $this->canViewList($user);
            case self::VIEW_DETAIL:
                return $this->canViewDetail($client, $user);
        }

        return false;
    }

    private function canDelete(Client $client, User $user): bool
    {
        return $client->getUser() === $user;
    }

    private function canViewList(User $user): bool
    {
        return $user instanceof User;
    }

    private function canViewDetail(Client $client, User $user): bool
    {
        return $client->getUser() === $user;
    }
}
