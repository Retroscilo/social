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
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $appointmentsRepository = $this->postRepository;

        // Find all the data on the Appointments table, filter your query as you need
        $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
            ->getQuery();

        // Paginate the results of the query
        $appointments = $paginator->paginate(
            // Doctrine Query, not results
            $allAppointmentsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        // Render the twig view
        return $this->render('default/index.html.twig', [
            'appointments' => $appointments
        ]);
    }
}
