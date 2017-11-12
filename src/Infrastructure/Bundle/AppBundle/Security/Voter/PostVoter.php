<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Security\Voter;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Security\Voter\PostVoter as DomainPostVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    /**
     * @var DomainPostVoter
     */
    private $voter;

    /**
     * @param DomainPostVoter $voter
     */
    public function __construct(DomainPostVoter $voter)
    {
        $this->voter = $voter;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if ('create_post' === $attribute
            && null === $subject
        ) {
            return true;
        }

        if (in_array($attribute, ['update', 'delete', 'comment', 'favorite', 'unfavorite'])
            && $subject instanceof Post
        ) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var $subject Post|null */

        $user = $token->getUser();

        if (null !== $user && !$user instanceof Author) {
            return false;
        }

        switch ($attribute) {
            case 'create':
                return $this->voter->allowedToCreate($user);
            case 'update':
                return $this->voter->allowedToUpdate($subject, $user);
            case 'delete':
                return $this->voter->allowedToDelete($subject, $user);
            case 'comment':
                return $this->voter->allowedToComment($subject, $user);
            case 'favorite':
                return $this->voter->allowedToFavorite($subject, $user);
            case 'unfavorite':
                return $this->voter->allowedToUnfavorite($subject, $user);
        }

        return true;
    }
}
