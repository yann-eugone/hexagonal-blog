<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Security\Voter;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Security\Voter\CommentVoter as DomainCommentVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    /**
     * @var DomainCommentVoter
     */
    private $voter;

    /**
     * @param DomainCommentVoter $voter
     */
    public function __construct(DomainCommentVoter $voter)
    {
        $this->voter = $voter;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if (in_array($attribute, ['reply', 'update', 'delete'])
            && $subject instanceof Comment
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
        /** @var $subject Comment */

        $user = $token->getUser();

        if (null !== $user && !$user instanceof Author) {
            return false;
        }

        switch ($attribute) {
            case 'reply':
                return $this->voter->allowedToReply($subject, $user);
            case 'update':
                return $this->voter->allowedToUpdate($subject, $user);
            case 'delete':
                return $this->voter->allowedToDelete($subject, $user);
        }

        return true;
    }
}
