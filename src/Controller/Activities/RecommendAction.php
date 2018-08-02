<?php

namespace App\Controller\Activities;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Activity;
use App\Repository\ActivityRepository;


class RecommendAction extends AbstractController
{

    /** @var string $day */
    private $day;
    /** @var string $start */
    private $start;
    /** @var string $end */
    private $end;
    
    /**
     * @Route("/activities/recommend", name="recommend_activity")
     */
    public function __invoke(Request $request)
    {
        /** @var string $category */
        $category = $request->get('category', '');
        
        $this->day = $request->get('day', '');
        $this->start = $request->get('start', '');
        $this->end = $request->get('end', '');
        
        /** @var ActivityRepository $activityRepository */
        $activityRepository = $this->getDoctrine()->getRepository(Activity::class);
        /** @var array $activities */
        $activities = $activityRepository->findBy([
            //where
            'category' => $category
        ],
        [
            //orderBy
            'hoursSpent' => 'DESC'
        ]);
        
        /** @var array $validActivities */
        $validActivities = array_filter(
            $activities,
            [$this, 'isValidActivity']
        );
        
        /** @var array $recommendedActivity */
        $recommendedActivity = !empty($validActivities)
                             ? $validActivities[0]->getGeoJsonArray() //as I sorted them before, I can do this
                             : [];
    
        return new JsonResponse($recommendedActivity);
    }
    
    private function isValidActivity(Activity $activity)
    {
        /** @var array $openingHours */
        $openingHours = $activity->getOpeningHours();
        
        /** @var array $filteredByDay */
        $filteredByDay = array_key_exists($this->day, $openingHours) 
                         && !empty($openingHours[$this->day])
                       ? $openingHours[$this->day]
                       : [];
        
        $isValid = !empty($filteredByDay);
        
        if ($isValid) {
            $hoursSpent = $activity->getHoursSpent();
           
            $isValid = $hoursSpent <= $this->compareHours($this->start, $this->end);
           
            if ($isValid){
                foreach ($filteredByDay as $hours) {
                    $hours = explode('-', $hours);
                    $start = $hours[0];
                    $end = $hours[1];
                    
                    $isValid =
                        (
                            //if I get there after opening, it can't close before hours spent
                            $this->compareHours($start, $this->start) >= 0
                            && $this->compareHours($this->start, $end) >= $hoursSpent
                        ) || (
                            //if I get there before opening, I can't go before hours spent
                            $this->compareHours($this->start, $start) >= 0
                            && $this->compareHours($start, $this->end) >= $hoursSpent
                        );
                    ;
                    if ($isValid) {
                        break;
                    }
                }
            } 
        }
        
        return $isValid;
    }
    
    /**
     *  Returns difference between a and b, in hours
     */
    private function compareHours(string $a, string $b) : int
    {
        return (strtotime($b) - strtotime($a))/3600;
    } 
}
