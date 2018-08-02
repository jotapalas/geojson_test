<?php

namespace App\Controller\Activities;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowAction
{
    /**
     * @Route("/activities/show", name="show_activities")
     */
    public function __invoke()
    {
        return new Response(
            '
            <html>
                <body>
                    Show
                </body>
            </html>
            '
        );
    }
}

