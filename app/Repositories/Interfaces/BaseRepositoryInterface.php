<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
        
{
    public function all(array $relation);

    public function pagination(
        array $coloum = ['*'],
        array $condition = [],
        int $perpage = 10,
        array $extend =[],
        array $orderBy = ['id', 'DESC'],     
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
    );
    public function create(array $payload);
    public function createBatch(array $payload = []);
    public function update(int $id = 0, array $payload = []);
    public function updateOrInsert(array $condition = [], array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function forceDeleteByCondition(array $condition = []);
    public function findById(int $modelId, array $column = [],array $relation = []);
    public function findByCondition($condition = [], $flag = FALSE, $relation = [], array $orderBy = [], array $param = []);
    public function updateByWhereIn(string $whereInField = '', array $whereIn = []);
    public function createPivot($model, array $payload = [], string $relation = '');
    public function updateByWhere($condition = [], array $payload = []);
    
    
}
