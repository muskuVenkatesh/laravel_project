<?php

namespace App\Repositories;

use App\Models\HomeworkCircular;
use App\Interfaces\homeWorkCircularInterface;

class HomeWorkCircularRepository implements homeWorkCircularInterface
{
    protected $model;

    public function __construct(HomeworkCircular $homeworkcircular)
    {
        $this->homeworkcircular = $homeworkcircular;
    }

    public function createCircular(array $data)
    {
        return $this->homeworkcircular->createHomeworkCircular($data);
    }
}
