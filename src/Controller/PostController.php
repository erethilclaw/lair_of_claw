<?php

namespace App\Controller;

use App\Form\PostFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController {

	public function new(){
		$form = $this->createForm(PostFormType::class);

		return $this->render('post/newPost.html.twig', [
			'postForm' => $form->createView(),
		]);
	}
}