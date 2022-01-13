<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UsersController extends AbstractController
{
    private $userRepository;
    private $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }


    /**
     * @Route("/users", name="app_users")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $userRepository = $this->userRepository;
        $allUsersQuery = $userRepository->createQueryBuilder('p');

        $query = $allUsersQuery->getQuery();

        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        ); 

        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }
}
