<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Twig\Extension;

use Acme\Infrastructure\Bundle\AppBundle\Security\FirewallConfig;
use Symfony\Component\Routing\RouterInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class SecurityExtension extends Twig_Extension
{
    /**
     * @var FirewallConfig
     */
    private $firewallConfig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param FirewallConfig  $firewallConfig
     * @param RouterInterface $router
     */
    public function __construct(FirewallConfig $firewallConfig, RouterInterface $router)
    {
        $this->firewallConfig = $firewallConfig;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'imerys_security';
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('login_path', [$this, 'getLoginPath']),
            new Twig_SimpleFunction('login_url', [$this, 'getLoginUrl']),
            new Twig_SimpleFunction('authenticate_path', [$this, 'getAuthenticatePath']),
            new Twig_SimpleFunction('authenticate_url', [$this, 'getAuthenticateUrl']),
        ];
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getLoginPath($key)
    {
        return $this->getLogin($key, RouterInterface::ABSOLUTE_PATH);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getLoginUrl($key)
    {
        return $this->getLogin($key, RouterInterface::ABSOLUTE_URL);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getAuthenticatePath($key)
    {
        return $this->getAuthenticate($key, RouterInterface::ABSOLUTE_PATH);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getAuthenticateUrl($key)
    {
        return $this->getAuthenticate($key, RouterInterface::ABSOLUTE_URL);
    }

    /**
     * @param string $key
     * @param int    $referenceType
     *
     * @return string
     */
    private function getLogin($key, $referenceType)
    {
        return $this->router->generate(
            $this->firewallConfig->getLoginRoute($key),
            [],
            $referenceType
        );
    }

    /**
     * @param string $key
     * @param int    $referenceType
     *
     * @return string
     */
    private function getAuthenticate($key, $referenceType)
    {
        return $this->router->generate(
            $this->firewallConfig->getLoginCheckRoute($key),
            [],
            $referenceType
        );
    }
}
