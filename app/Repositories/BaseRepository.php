<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Faker\Provider\Base;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    public function __construct(Model $model) {
        $this->model = $model;
    }
    public function pagination(
        array $coloum = ['*'],
        array $condition = [],
        int $perpage = 10,
        array $extend =[],
        array $orderBy = ['id', 'DESC'],     
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
    ) {
        $query = $this->model->select($coloum);
        return $query
            ->keyword($condition['keyword'] ?? null)
            ->publish($condition['publish'] ?? null)
            ->relationCount($relations ?? null)
            ->CustomWhere($condition['where'] ?? null)
            ->CustomWhereRaw($rawQuery['whereRaw'] ?? null)
            ->CustomJoin($join ?? null)
            ->CustomGroupBy($extend['groupBy'] ?? null)
            ->CustomOrderBy($orderBy ?? null)
            ->paginate($perpage)
            ->withQueryString()
            ->withPath(env('APP_URL').$extend['path']);
    }
    public function create(array $payload = []) {
        $model = $this->model->create($payload);
        return $model->fresh();
    }
    public function createBatch(array $payload = []) {
        return $this->model->insert($payload);
    }
    public function update(int $id = 0, array $payload = []) {
        $model = $this->findById($id);
        $model->fill($payload);
        $model->save();
        return $model;
    }
    //insert system
    public function updateOrInsert(array $condition = [], array $payload = []) {
        return $this->model->updateOrInsert($condition, $payload);
    }
    public function updateByWhereIn(string $whereInField = '', array $whereIn = [], array $payload = []) {
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }
    public function updateByWhere($condition = [], array $payload = []) {
        $query = $this->model->newQuery();
        foreach($condition as $key => $value) {
            $query->where($value[0], $value[1], $value[2]);
        }
        return $query->update($payload);
    }
    public function delete(int $id = 0) {
        return $this->findById($id)->delete();
    }
    public function forceDelete(int $id = 0) {
        return $this->findById($id)->forceDelete();
    }
    public function forceDeleteByCondition(array $condition = []) {
        $query = $this->model->newQuery();
        foreach($condition as $key => $value) {
            $query->where($value[0], $value[1], $value[2]);
        }
        return $query->forceDelete();
    }
    public function all(array $relation = []) {
        return $this->model->with($relation)->get();
    }
    public function findById(
        int $modelId,
        array $column = ['*'],
        array $relation = []
    ) {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }
    public function findByCondition(
        $condition = [], 
        $flag = FALSE, 
        $relation = [], 
        array $orderBy = ['id', 'DESC'], 
        array $param = []) 
    {
        $query = $this->model->newQuery();
        foreach($condition as $key => $value) {
            $query->where($value[0], $value[1], $value[2]);
        }
        if(isset($param['whereIn'])) {
            $query->whereIn($param['whereInField'], $param['whereIn']);
        }
        $query->with($relation);
        $query->orderBy($orderBy[0], $orderBy[1]);
        return ($flag == false) ? $query->first() : $query->get();
    }
    public function createPivot($model, array $payload = [], string $relation = '') {
        return $model->{$relation}()->attach($model->id, $payload);
    }
    public function findWidgetItem(array $condition = [], int $language_id = 1, string $alias = '') {
        return $this->model->with([
            'languages' => function ($query) use ($language_id) {
                $query->where('language_id', $language_id);
            }
        ])->whereHas('languages', function ($query) use ($condition, $alias) {
            foreach($condition as $key => $value) {
                $query->where($alias.'.'.$value[0], $value[1], $value[2]);
            }
        })->get();
    }
}
