<?php

namespace App\Controller\Activities;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\FileLocator;
use App\Entity\Activity;

class LoadAction extends Controller
{
    /**
     * @Route("/activities/load", name="load_activities")
     */
    public function __invoke()
    {
        $json = file_get_contents(__DIR__.'/../../../resources/madrid.json');
        $json = json_decode($json);
        
        $entityManager = $this->getDoctrine()->getManager();
        
        foreach ($json as $activity) {
            $activityInstance = new Activity (
                $activity->name,
                $activity->category,
                $activity->location,
                $activity->district,
                $activity->hours_spent,
                $activity->latlng[1],
                $activity->latlng[0]
            );
            
            $entityManager->persist($activityInstance);
        }
        
        $entityManager->flush();
        
        return $this->render('activities/load.html.twig');
    }
}

