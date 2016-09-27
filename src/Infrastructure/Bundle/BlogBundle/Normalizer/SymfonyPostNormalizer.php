<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Normalizer;

use Acme\Application\Blog\Normalizer\PostNormalizer;
use Acme\Domain\Blog\Model\Post;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SymfonyPostNormalizer implements PostNormalizer
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
    public function normalizeToEvent(Post $post)
    {
        return $this->normalizer->normalize($post, 'json', ['groups' => ['event_bus']]);
    }
}
