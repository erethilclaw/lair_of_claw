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

	public function listPost(PostRepository $post_repository){
		$posts = $post_repository->findAll();

		return $this->render('post/listPost.html.twig', [
			'posts'=>$posts,
		]);
	}

	public function viewPost(PostRepository $post_repository, $slug){
		$post = $post_repository->findOneBy([
			'slug'=>$slug
		]);
		return $this->render('post/viewPost.html.twig', [
			'post'=>$post,
		]);
	}

	public function editPost(PostRepository $post_repository, $slug, EntityManagerInterface $em, Request $request){
		$post = $post_repository->findOneBy([
			'slug'=>$slug
		]);
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

	public function deletePost(PostRepository $post_repository, $slug, EntityManagerInterface $em, Request $request){
		$post = $post_repository->findOneBy([
			'slug'=>$slug
		]);
		if (!$post) {
            throw $this->createNotFoundException("Destinatari no trobat");
        }
        $em->remove($post);
		$em->flush();
		$this->addFlash('success', 'Article deleted!');
		return $this->redirectToRoute('listPost');
	}
}