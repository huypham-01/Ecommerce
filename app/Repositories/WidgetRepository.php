<?php

namespace App\Repositories;
use App\Models\Widget;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\WidgetRepositoryInterface;

/**
 * Class WidgetService
 * @package App\Services
 */
class WidgetRepository extends BaseRepository implements WidgetRepositoryInterface
{
    protected $model;
    public function __construct(
        Widget $model,
    ) {
        $this->model = $model;
    }
}
