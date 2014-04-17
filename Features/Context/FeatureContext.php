<?php

namespace Bigfoot\Bundle\UserBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\Step;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\ResponseTextException;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Sanpi\Behatch\Context\BehatchContext;

use Sandbox\FrontBundle\Features\Context\InheritedFeatureContext;

/**
 * FeatureContext
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $entityManager;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->useContext('behatch', new BehatchContext($parameters));
        $this->useContext('common_context', new InheritedFeatureContext($parameters));

        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getEntityManager()
    {
        if ($this->entityManager == null) {
            $this->entityManager = $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
        }

        return $this->entityManager;
    }

    /**
     * @Then /^I go to reset password page with "([^"]*)" and a "([^"]*)"$/
     */
    public function iAmOnResetPasswordPageWithAndA($email, $token)
    {
        $user = $this->getEntityManager()->getRepository('BigfootUserBundle:User')->findOneByEmail($email);

        if ($token == 'expired-token') {
            $tokenTtl = $this->kernel->getContainer()->getParameter('bigfoot_user.resetting.token_ttl');
            $date     = new \DateTime();
            $date->modify('-1 day');

            $user->setPasswordRequestedAt($date);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }

        $token = $user->getConfirmationToken();

        return array(
            new Step\Given('I am on "/admin/reset-password/'.$token.'"'),
        );
    }
}