<?php

namespace App\Repositories;
use App\Models\Slide;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\SlideRepositoryInterface;

/**
 * Class SlideService
 * @package App\Services
 */
class SlideRepository extends BaseRepository implements SlideRepositoryInterface
{
    protected $model;
    public function __construct(
        Slide $model,
    ) {
        $this->model = $model;
    }
    
}
