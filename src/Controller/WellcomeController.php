<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WellcomeController extends AbstractController {

	public function wellcome() {
		return $this->render('wellcome/wellcome.html.twig');
	}

}