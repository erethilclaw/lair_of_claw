<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController {

	public function listPost(PostRepository $post_repository, $page = 1, Request $request){
		$limit = $request->get('limit', 10);
		$posts = $post_repository->findAllPostByCreateDate();

		return $this->json(
			[
				'page'=> $page,
				'limit'=> $limit,
				'data' => array_map(function (Post $post) {
					return $this->generateUrl('editPost',['slug'=>$post->getSlug()]);
				}, $posts)
			]
		);
	}
	/**
	 * @Route("/blog", name="webPost", methods={"GET"})
	 */
	public function listPostFront(PostRepository $post_repository, $page = 1, Request $request){
		$limit = $request->get('limit', 10);
		$posts = $post_repository->findAllPostByPublishDate();

		return $this->json(
			[
				'page'=> $page,
				'limit'=> $limit,
				'data' => $posts,
			]
		);
	}

	/**
	 * @Route("/admin/post/{slug}", name="viewPost", methods={"GET"})
	 */
	public function viewPost(Post $post){
		return $this->json($post);
	}

	/**
	 * @Route("admin/newPost", name="newPost", methods={"POST"})
	 */
	public function newPost( Request $request){
		$serializar = $this->get('serializer');
		$post = $serializar->deserialize($request->getContent(), Post::class,'json');

		$em = $this->getDoctrine()->getManager();
		$em->persist($post);
		$em->flush();

		return $this->json($post);
	}


	public function editPost($slug, EntityManagerInterface $em, Request $request){
		$post = $this->searchPostBySlug($slug);
		$form = $this->createForm(PostFormType::class, $post);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$post = $form->getData();
			$em->persist($post);
			$em->flush();
			$this->addFlash('success', 'Article Edited!!');

			return $this->redirectToRoute('listPost');
		}

		return $this->render('post/editPost.html.twig', [
			'postForm' => $form->createView(),
		]);
	}

	/**
	 * @Route("/admin/post/{slug}", name="deletePost", methods={"DELETE"})
	 */
	public function deletePost(Post $post){
		$em = $this->getDoctrine()->getManager();
        $em->remove($post);
		$em->flush();

		return new JsonResponse(null,Response::HTTP_NO_CONTENT);
	}

}