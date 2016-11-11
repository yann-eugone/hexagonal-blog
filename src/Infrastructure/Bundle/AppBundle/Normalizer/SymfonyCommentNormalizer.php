<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Normalizer;

use Acme\Application\Blog\Normalizer\CommentNormalizer;
use Acme\Domain\Blog\Model\Comment;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SymfonyCommentNormalizer implements CommentNormalizer
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @inheritDoc
     */
    public function normalizeToEvent(Comment $comment)
    {
        return $this->normalizer->normalize($comment, 'json', ['groups' => ['event_bus']]);
    }
}
