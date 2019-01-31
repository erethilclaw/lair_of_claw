<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController {

	public function newPost(EntityManagerInterface $em, Request $request){
		$form = $this->createForm(PostFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$post = $form->getData();
			$em->persist($post);
			$em->flush();
			$this->addFlash('success', 'Article Created! Knowledge is power!');

			return $this->redirectToRoute('listPost');
		}

		return $this->render('post/newPost.html.twig', [
			'postForm' => $form->createView(),
		]);
	}

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

	public function viewPost($slug){
		$post = $this->searchPostBySlug($slug);
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

	public function deletePost($slug, EntityManagerInterface $em){
		$post = $this->searchPostBySlug($slug);
		if (!$post) {
            throw $this->createNotFoundException("Destinatari no trobat");
        }
        $em->remove($post);
		$em->flush();
		$this->addFlash('success', 'Article deleted!');
		return $this->redirectToRoute('listPost');
	}

	public function searchPostBySlug($slug){
		$post_repository = $this->getDoctrine()->getRepository(Post::class);
		return $post = $post_repository->findOneBy([
			'slug'=>$slug
		]);
	}
}