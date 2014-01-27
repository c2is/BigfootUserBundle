<?php

namespace Bigfoot\Bundle\UserBundle\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\UserBundle\Entity\BigfootUser;
use Bigfoot\Bundle\UserBundle\Mailer\UserMailer;
use Bigfoot\Bundle\CoreBundle\Generator\TokenGenerator;

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
    private $userMailer;
    private $tokenGenerator;

    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory, UserCheckerInterface $userChecker, SecurityContextInterface $securityContext, SessionInterface $session, UserMailer $userMailer, TokenGenerator $tokenGenerator)
    {
        $this->entityManager   = $entityManager;
        $this->encoderFactory  = $encoderFactory;
        $this->userChecker     = $userChecker;
        $this->securityContext = $securityContext;
        $this->session         = $session;
        $this->userMailer      = $userMailer;
        $this->tokenGenerator  = $tokenGenerator;
    }

    public function createUser()
    {
        return new BigfootUser();
    }

    public function loginUser($firewallName, BigfootUser $user)
    {
        $this->userChecker->checkPostAuth($user);

        $token = $this->createToken($firewallName, $user);

        $this->securityContext->setToken($token);
    }

    public function updateUser(BigfootUser $user)
    {
        $this->updatePassword($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }

    public function updatePassword(BigfootUser $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    public function generateToken(BigfootUser $user)
    {
        $token = $this->tokenGenerator->generateToken();
        $status = true;

        try {
            $this->userMailer->sendForgotPasswordMail($user, $token);
        } catch (Exception $e) {
            $status   = false;
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

    protected function getEncoder(BigfootUser $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    protected function createToken($firewall, BigfootUser $user)
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }
}
