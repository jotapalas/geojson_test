<?php

namespace App\Controller\Activities;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoadAction
{
    /**
     * @Route("/activities/load", name="load_activities")
     */
    public function __invoke()
    {
        return new Response(
            '
            <html>
                <body>
                    Load
                </body>
            </html>
            '
        );
    }
}

