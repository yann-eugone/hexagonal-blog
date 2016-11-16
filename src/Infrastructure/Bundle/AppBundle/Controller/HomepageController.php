<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Domain\Blog\Repository\PostCounterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
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
        return $this->get('repository.post'); //todo use alias
    }
}