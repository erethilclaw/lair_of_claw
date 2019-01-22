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
			//toremove
			$post->setSlug('puta');
			$em->persist($post);
			$em->flush();

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
}