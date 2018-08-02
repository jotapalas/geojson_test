<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IndexAction extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function __invoke()
    {
        return $this->render('index.html.twig');
    }
}
