# Back-end Developer - CARTO
Juan Antonio Palacios

## Introduction
This is an app developed following the specifications provided by CARTO. The structure is very simple as I made it for test purposes. There is only **one entity** in the database for storing *Activities*, but actually the best thing to do would have been at least **three entities:** *Activities*, on a many-to-one relationship with *Districts*, on a many-to-one relationship with *Cities*. Ideally, I would set up some **enum entities** for *Categories* and *Locations*.

The app is developed using **Symfony 4**, a PHP framework with a lot of useful libraries, such as ORM for entity management or Route for easy routing. Documentation: https://symfony.com/doc/current/index.html#gsc.tab=0

## App structure
The app has a simple GUI that leads to three different endpoints: *Load activities, show activities* and *recommend activities.* Every one of this endpoints is developed in a different PHP class at **src/Controller/Activities**.

### Load activities
This endpoint do as it says: it loads the activities given in the JSON file to the database. It's specifically designed for an initial *one-time* upload, so it won't load any activity if the database is not empty.

### Show activities
Given some filters (category, location, district), this endpoint returns the activities matching all of them (it returns an **exact match** with *AND* filters), in GeoJSON format. If there is no filter, it returns all activities.

### Recommend me an activity
It returns the perfect activity for the time range given. It only supports one day searches, but the feature could be expanded with some modifications on the *compareHours* method, if we assume that the time range is **continuous** (from Monday, 17:00, to Tuesday, 12:00). This method would have to take into account the day, something like:

```
    /**
     *  Returns difference between a and b, in hours
     */
    private function compareHours(string $dayA, string $dayB, string $a, string $b) : int
    {
        $diff = 0;
        $diffDays = $dayA - $dayB; //assuming there is a method that returns something like 'tu' - 'mo' = 1
        if ($diffDays = 0) { //same day
          $diff = (strtotime($b) - strtotime($a))/3600;
        } else if ($diffDays > 0) {
          $diff = ((strtotime('23:59') - strtotime($a)) + ((strtotime($b) - strtotime('00:00')))/3600;
        } 
        ...
        return $diff;
    }
```
## Installation guide
There is no installation needed! I've uploaded the full app to **Heroku**, so you can check it out if you follow this link: https://whispering-falls-17664.herokuapp.com/. Also, the app is using **JawsDB** as MySQL database.

## Extensions
We could extend the functionality of the app in several ways. Here are some thougts about it:

### Do not recommend an outdoors activity on a rainy day
There are some weather APIs we could call to check out the weather before the query to the database, and add a new filter to the *where* clause. For example, here: https://openweathermap.org/api

### Support getting information about activities in multiple cities
With a new entity for *Cities*, it would be easy to store activities in multiple cities. Then, we could pass an array of cities to the endpoint and perform the search in a *WHERE City IN (...)* query easily.

### Extend the recommendation API to fill the given time range with multiple activities
We will need to store the activities that already filled the time range in an array and keep track on the remaining range in another variable. Then, we could iterate over and over until the time range is full of activities.
