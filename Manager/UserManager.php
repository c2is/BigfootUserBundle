<?php

namespace Bigfoot\Bundle\UserBundle\Manager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\UserBundle\Entity\User;
use Bigfoot\Bundle\UserBundle\Mailer\UserMailer;
use Bigfoot\Bundle\CoreBundle\Generator\TokenGenerator;
use Bigfoot\Bundle\ContextBundle\Service\ContextService as Context;

/**
 * UserManager
 */
class UserManager
{
    private $entityManager;
    private $encoderFactory;
    private $userChecker;
    private $securityContext;
    private $session;
    private $context;
    private $userMailer;
    private $tokenGenerator;
    private $request;

    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory, UserCheckerInterface $userChecker, SecurityContextInterface $securityContext, SessionInterface $session, Context $context, UserMailer $userMailer, TokenGenerator $tokenGenerator)
    {
        $this->entityManager   = $entityManager;
        $this->encoderFactory  = $encoderFactory;
        $this->userChecker     = $userChecker;
        $this->securityContext = $securityContext;
        $this->session         = $session;
        $this->context         = $context;
        $this->userMailer      = $userMailer;
        $this->tokenGenerator  = $tokenGenerator;
    }

    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    public function createUser()
    {
        return new User();
    }

    public function loginUser($firewallName, User $user)
    {
        $this->userChecker->checkPostAuth($user);

        $token = $this->createToken($firewallName, $user);

        $this->securityContext->setToken($token);
    }

    public function updateUser(User $user)
    {
        $this->applyLocale($user);
        $this->updatePassword($user);
        $this->refreshUser($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }

    public function refreshUser(User $user)
    {
        $context = $this->entityManager->getRepository('BigfootContextBundle:Context')->findOneByEntityIdEntityClass($user->getId(), 'Bigfoot\Bundle\UserBundle\Entity\User');

        if ($context) {
            $this->session->set('bigfoot/context/allowed_contexts', $context->getContextValues());
        }
    }

    public function updatePassword(User $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    public function applyLocale($user)
    {
        if ($user && $user instanceof User && $user->getLocale()) {
            $this->request->getSession()->set('_locale', $user->getLocale());
        }
    }

    public function generateToken(User $user)
    {
        $token  = $this->tokenGenerator->generateToken();
        $status = true;

        try {
            $this->userMailer->sendForgotPasswordMail($user, $token);
        } catch (Exception $e) {
            $status  = false;
            $message = "Couldn't send email, please contact an admin.";
        }

        if ($status == true) {
            $message = "Email sent check your inbox.";

            $user->setConfirmationToken($token);
            $user->setPasswordRequestedAt(new \DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return array(
            'status'  => $status,
            'message' => $message,
        );
    }

    public function createPassword(User $user)
    {
        $token  = $this->tokenGenerator->generateToken();
        $status = true;

        try {
            $this->userMailer->sendCreatePasswordMail($user, $token);
        } catch (Exception $e) {
            $status  = false;
            $message = "Couldn't send email, please contact an admin.";
        }

        if ($status == true) {
            $message = "Email sent check your inbox.";

            $user->setConfirmationToken($token);
            $user->setPasswordRequestedAt(new \DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return array(
            'status'  => $status,
            'message' => $message,
        );
    }

    protected function getEncoder(User $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    protected function createToken($firewall, User $user)
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }
}
