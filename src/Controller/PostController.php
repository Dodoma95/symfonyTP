<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 * Class PostController
 * @package App\Controller
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post-list")
     * @param PostRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PostRepository $repository, PaginatorInterface $paginator, Request $request)
    {

        $postList = $paginator->paginate(
            $repository->getAllPosts(),
            $request->query->getInt('page', 1),
            10
        );

        dump($postList);

        $params = $this->getTwigParametersWithAside(
            [
                'postList' => $postList, 'pagetitle' => ''
            ]
        );
        return $this->render('post/index.html.twig', $params);
    }

    /**
     * @Route("/by-user/{id}", name="post-by-user")
     * @param User $user
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param PostRepository $repository
     * @return Response
     */
    public function showByAuthor(User $user, Request $request, PaginatorInterface $paginator, PostRepository $repository){
        $postList = $paginator->paginate(
            $repository->getAllByUser($user),
            $request->query->getInt('page', 1),
            10
        );

        $params = $this->getTwigParametersWithAside(
            ['postList' => $postList, 'pageTitle' => "de l'utilisateur : ". $user->getFullName()]
        );

        return $this->render('post/index.html.twig', $params);
    }

    /**
     * @param $data
     * @return array
     */
    private function getTwigParametersWithAside($data){
        $asideData =[
            'userList' => $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll()
        ];

        return array_merge($data, $asideData);
    }

    /**
     * @Route("/post/new", name="post-create")
     * @Route("/post/edit/{id}", name="post-edit")
     * @param Request $request
     * @param null $id
     * @return Response
     */
    public function createOrEditArticle(Request $request, $id=null){

        // Création du formulaire
        if($id == null){
            $post = new Post();
            //$book->setPublisher(new Publisher());
        } else {
            $post = $this   ->getDoctrine()
                ->getRepository(Post::class)
                ->find($id);
        }

        $form = $this->createForm(PostType::class, $post);

        //recupere les données postées et l'injecte dans le formulaire
        //l'objet associé donc book est aussi hydraté
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("post-list");
        }

        return $this->render("post/new.html.twig", [
            "postForm" => $form->createView()
        ]);
    }

    /**
     * @Route("/post/delete/{id}", name="post-delete")
     * @param $id
     * @return RedirectResponse
     */
    public function deletePost($id){

        $repository = $this->getDoctrine()->getRepository(Post::class);
        $post = $repository->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        if($post){
            $entityManager->remove($post);

            $entityManager->flush();
        }

        return $this->redirectToRoute("post-list");
    }

    /**
     * @Route("/post/{id}", name="post-details")
     * @param Post $post
     * @return Response
     */
    public function details(Post $post){

        return $this->render('post/details.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/new", name="post-new")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param null $id
     * @return Response
     */
    public function addOrEdit(Request $request, $id=null){
        $post = new Post();
        $post->setUser($this->getUser());
        //Equivalent de @IsGranted dans les annotations
        //$this->denyAccessUnlessGranted('ROLE-AUTHOR');

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', "Votre post a été ajouté");

            return $this->redirectToRoute('post-list');
        }

        return $this->render('post/form.html.twig', [
            'postForm' => $form->createView()
        ]);
    }
}
