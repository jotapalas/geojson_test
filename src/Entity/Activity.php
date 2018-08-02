<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     */
    private $category;
    
    /**
     * @ORM\Column(type="string")
     */
    private $location;
    
    /**
     * @ORM\Column(type="string")
     */
    private $district;
    
    /**
     * @ORM\Column(type="float")
     */
    private $hoursSpent;
    
    /**
     * @ORM\Column(type="float")
     */
    private $longitude;
    
    /**
     * @ORM\Column(type="float")
     */
    private $latitude;
    
    /**
     * Constructor for Activity entity
     */
    public function __construct (
        string  $name,
        string  $category,
        string  $location,
        string  $district,
        float   $hoursSpent,
        float   $longitude,
        float   $latitude 
    ) {
        $this->name = $name;
        $this->category = $category;
        $this->location = $location;
        $this->district = $district;
        $this->hoursSpent = $hoursSpent;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }
    
    public function getName() : string 
    { 
        return $this->name;
    }
    
    public function getCategory() : string
    {
        return $this->category;
    }
    
    public function getLocation() : string
    {
        return $this->location;
    }
    
    public function getDistrict() : string
    {
        return $this->district;
    }
    
    public function getHoursSpent() : float
    {
        return $this->hoursSpent;
    }
    
    public function getLongitude() : float
    {
        return $this->longitude;
    }
    
    public function getLatitude() : float
    {
        return $this->latitude;
    }
    
    /**
     * Returns a GeoJSON object as array for the activity
     */
    public function getGeoJsonArray() : array
    {
        return [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [$this->longitude, $this->latitude]
            ],
            'properties' => [
                'name' => $this->name,
                'hours_spent' => $this->hoursSpent,
                'category' => $this->category,
                'location' => $this->location,
                'district' => $this->district,
            ]
        ];
    }
    
    
    /**
     * Returns a GeoJSON object as string for the activity
     */
    public function getGeoJson() : string
    {
        return json_encode($this->getGeoJsonArray());
    }
    
}
