<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ActivityRepository extends EntityRepository
{
    public function isEmpty() : bool
    {
        return empty($this->findAll());
    }   
}

