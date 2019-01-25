<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('title', TextType::class)
			->add('slug')
			->add('publishedAt',null, [
				'widget' => 'single_text'
			])
			->add('content', TextareaType::class)
			;
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults([
			'data_class'=> Post::class,
		]);
	}
}