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
			$post = new Post();
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

	public function listPost(PostRepository $post_repository){
		$posts = $post_repository->findAll();

		return $this->render('post/listPost.html.twig', [
			'posts'=>$posts,
		]);
	}

	public function viewPost($slug){
		$post = $this->getDoctrine()->getRepository(Post::class)->findBy([
			'slug'=>$slug
		]);
		return $this->render('post/viewPost.html.twig', [
			'posts'=>$post,
		]);
	}
}