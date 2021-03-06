<?php

namespace App\Controller;

use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Repository\UserRepository;

class LoginController extends AbstractController
{
    private $userRepository;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/deleteAccount", name="account_delete")
     */
    public function deleteAccount(): Response
    {
        $currentUserId = $this->getUser()->getId();
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();

        $em = $this->getDoctrine()->getManager();
        $user = $this->userRepository->find($currentUserId);
        $posts = $user->getPosts();
        foreach($posts as $post)
        {
            $em->remove($post);
        }
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
