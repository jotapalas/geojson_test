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
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var ActivityRepository $activityRepository */
        $activityRepository = $entityManager->getRepository(Activity::class);
        
        $message = '';
        
        if(false === $activityRepository->isEmpty())
        { //This is a basic loader only for initial loads, so this is to avoid many loads
            $message = 'Activities already loaded';
        } else {
            $json = file_get_contents(__DIR__.'/../../../resources/madrid.json');
            $json = json_decode($json);
            
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
            
            $message = 'Activities loaded succesfully!';
        }
        
        return $this->render('activities/load.html.twig', [
            'message' => $message
        ]);
    }
}

