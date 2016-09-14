<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Form\Type\Security;

use Acme\Infrastructure\Bundle\AppBundle\Security\FirewallConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginType extends AbstractType
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var FirewallConfig
     */
    private $firewallConfig;

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param FirewallConfig      $firewallConfig
     */
    public function __construct(AuthenticationUtils $authenticationUtils, FirewallConfig $firewallConfig)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->firewallConfig = $firewallConfig;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $username = $this->firewallConfig->getLoginUsernameParameter($options['firewall']);
        $password = $this->firewallConfig->getLoginPasswordParameter($options['firewall']);
        $remember = $this->firewallConfig->getRememberMeParameter($options['firewall']);

        $builder
            ->add($username, TextType::class)
            ->add($password, PasswordType::class)
            ->add($remember, CheckboxType::class, ['required' => false])
        ;

        /* Note: since the Security component's form login listener intercepts
         * the POST request, this form will never really be bound to the
         * request; however, we can match the expected behavior by checking the
         * session for an authentication error and last username.
         */
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($username) {
                $error = $this->authenticationUtils->getLastAuthenticationError();

                if ($error) {
                    $event->getForm()->addError(new FormError($error->getMessage()));
                }

                $event->setData(
                    array_replace(
                        (array)$event->getData(),
                        [
                            $username => $this->authenticationUtils->getLastUsername(),
                        ]
                    )
                );
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /* Note: the form's csrf_token_id must correspond to that for the form login
         * listener in order for the CSRF token to validate successfully.
         */

        $resolver
            ->setDefault('csrf_token_id', 'authenticate')
            ->setRequired(['firewall'])
            ->setAllowedTypes('firewall', ['string'])
            ->setAllowedValues('firewall', function ($value) {
                try {
                    $this->firewallConfig->getLoginUsernameParameter($value);
                    $this->firewallConfig->getLoginPasswordParameter($value);
                    $this->firewallConfig->getRememberMeParameter($value);
                } catch (\Exception $e) {
                    return false;
                }

                return true;
            })
        ;
    }
}
