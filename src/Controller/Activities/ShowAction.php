<?php

namespace App\Controller\Activities;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ActivityRepository;
use App\Entity\Activity;

class ShowAction extends AbstractController
{
    /**
     * @Route("/activities/show", name="show_activities")
     */
    public function __invoke(Request $request)
    {
        /** @var ActivityRepository $activityRepository */
        $activityRepository = $this->getDoctrine()->getRepository(Activity::class);
    
        /** @var string $category */
        $category = $request->get('category', '');
        /** @var string $location */
        $location = $request->get('location', '');
        /** @var string $district */
        $district = $request->get('district', '');
        /** @var array $filters */
        $filters = $this->buildFilters([
            'category' => $category, 
            'location' => $location, 
            'district' => $district
        ]);
        
        /** @var array $activities */
        $activities = $activityRepository->findBy($filters);
        
        /** @var array $activitiesGeoJson */
        $activitiesGeoJson = [];
        foreach ($activities as $activity) {
            $activitiesGeoJson[] = $activity->getGeoJsonArray();
        }
        
        $jsonResponse = [
            'type' => 'FeatureCollection',
            'features' => $activitiesGeoJson            
        ];
        return new JsonResponse($jsonResponse);
    }
    
    private function buildFilters(array $rawFilters)
    {
        /** @var array $filters */
        $filters = [];
        
        foreach ($rawFilters as $key => $value) {
            if ($value && $value !== '') {
                $filters[$key] = $value;
            }
        }
        
        return $filters;
    }
}

