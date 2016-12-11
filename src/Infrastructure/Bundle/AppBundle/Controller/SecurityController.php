<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Infrastructure\Bundle\AppBundle\Form\Type\Security\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     * @Method("GET")
     *
     * @return Response
     */
    public function loginAction()
    {
        $form = $this->getFormFactory()->createNamed(null, LoginType::class, null, ['firewall' => 'main']);

        return $this->render(
            'security/login.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/authenticate", name="security_authenticate")
     */
    public function authenticateAction()
    {
        // Intercepted by Symfony's firewall
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        // Intercepted by Symfony's firewall
    }

    /**
     * @return FormFactoryInterface
     */
    private function getFormFactory()
    {
        return $this->get('form.factory');
    }
}
