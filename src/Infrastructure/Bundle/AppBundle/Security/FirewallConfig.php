<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Security;

use RuntimeException;

class FirewallConfig
{
    /**
     * @var array
     */
    private $firewalls;

    /**
     * @param array $firewalls
     */
    public function __construct(array $firewalls)
    {
        $this->firewalls = $firewalls;
    }

    /**
     * Get the form_login.login_path of the firewall $key.
     *
     * @param string $key The firewall key
     *
     * @return string
     */
    public function getLoginRoute($key)
    {
        $this->assertFirewallExists($key);

        if (!isset($this->firewalls[$key]['form_login']['login_path'])) {
            throw new RuntimeException(sprintf('No login route configured for the "%s" firewall.', $key));
        }

        return $this->firewalls[$key]['form_login']['login_path'];
    }

    /**
     * Get the form_login.check_path of the firewall $key.
     *
     * @param string $key The firewall key
     *
     * @return string
     */
    public function getLoginCheckRoute($key)
    {
        $this->assertFirewallExists($key);

        if (!isset($this->firewalls[$key]['form_login']['check_path'])) {
            throw new RuntimeException(sprintf('No login check route configured for the "%s" firewall.', $key));
        }

        return $this->firewalls[$key]['form_login']['check_path'];
    }

    /**
     * Get the form_login.username_parameter of the firewall $key.
     *
     * @param string $key The firewall key
     *
     * @return string
     */
    public function getLoginUsernameParameter($key)
    {
        $this->assertFirewallExists($key);

        if (!isset($this->firewalls[$key]['form_login']['username_parameter'])) {
            throw new RuntimeException(sprintf('No username parameter configured for the "%s" firewall.', $key));
        }

        return $this->firewalls[$key]['form_login']['username_parameter'];
    }

    /**
     * Get the form_login.password_parameter of the firewall $key.
     *
     * @param string $key The firewall key
     *
     * @return string
     */
    public function getLoginPasswordParameter($key)
    {
        $this->assertFirewallExists($key);

        if (!isset($this->firewalls[$key]['form_login']['password_parameter'])) {
            throw new RuntimeException(sprintf('No password parameter configured for the "%s" firewall.', $key));
        }

        return $this->firewalls[$key]['form_login']['password_parameter'];
    }

    /**
     * Get the remember_me.remember_me_parameter of the firewall $key.
     *
     * @param string $key The firewall key
     *
     * @return string
     */
    public function getRememberMeParameter($key)
    {
        $this->assertFirewallExists($key);

        if (!isset($this->firewalls[$key]['remember_me']['remember_me_parameter'])) {
            throw new RuntimeException(sprintf('No remember me parameter configured for the "%s" firewall.', $key));
        }

        return $this->firewalls[$key]['remember_me']['remember_me_parameter'];
    }

    /**
     * Assert that the firewall $key is registered.
     *
     * @param string $key The firewall key
     */
    private function assertFirewallExists($key)
    {
        if (!isset($this->firewalls[$key])) {
            throw new RuntimeException(sprintf('There is no "%s" firewall configured.', $key));
        }
    }
}
