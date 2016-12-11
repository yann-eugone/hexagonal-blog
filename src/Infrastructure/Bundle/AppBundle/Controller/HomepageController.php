<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Domain\Blog\Repository\PostCounterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function displayAction()
    {
        $countPost = $this->getPostCounterRepository()->count();

        return $this->render(
            'homepage.html.twig',
            [
                'count' => [
                    'post' => $countPost,
                ],
            ]
        );
    }

    /**
     * @return PostCounterRepository
     */
    private function getPostCounterRepository()
    {
        return $this->get('repository.counter.post');
    }
}
