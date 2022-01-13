<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\Post;
use App\Entity\EntityManagerInterface;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostFormType;
use DateTime;

use Knp\Component\Pager\PaginatorInterface;
use App\ItemFilterType;

class HomeController extends AbstractController
{
    private $postRepository;
    private $userRepository;
    private $security;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository, Security $security)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->security->getUser());
            $post->setCreatedAt(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Le post a bien été ajouté.');
        }

        $lastPosts = $this->postRepository->getLastPosts();
        $lastUsers = $this->userRepository->getLastUsers();

        return $this->render('home.html.twig', [
            'lastPosts' => $lastPosts,
            'lastUsers' => $lastUsers,
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/posts", name="app_posts")
     */
    public function posts(Request $request, PaginatorInterface $paginator): Response
    {
        $appointmentsRepository = $this->postRepository;
        $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p');

        $query = $allAppointmentsQuery->getQuery();

        $appointments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        ); 

        return $this->render('default/index.html.twig', [
            'appointments' => $appointments
        ]);
    }
}
