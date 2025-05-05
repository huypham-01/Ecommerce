<?php

namespace App\Repositories;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;

/**
 * Class UserService
 * @package App\Services
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;
    public function __construct(
        User $model,
    ) {
        $this->model = $model;
    }
    // public function pagination(
    //     array $coloum = ['*'],
    //     array $condition = [],
    //     int $perpage = 10,
    //     array $extend =[],
    //     array $orderBy = ['id', 'DESC'],     
    //     array $join = [],
    //     array $relations = [],
    //     array $rawQuery = [],
    // ) {
    //     $query = $this->model->select($coloum)->where(function($query) use ($condition){
    //         if(isset($condition['keyword']) && !empty($condition['keyword'])) {
    //             $query->where('name', 'LIKE', '%'.$condition['keyword'].'%')
    //                   ->orWhere('email', 'LIKE', '%'.$condition['keyword'].'%')
    //                   ->orWhere('address', 'LIKE', '%'.$condition['keyword'].'%')
    //                   ->orWhere('phone', 'LIKE', '%'.$condition['keyword'].'%');
    //         }
    //         if(isset($condition['publish']) && !empty($condition['publish']) && $condition['publish'] != 0) {
    //             $query->where('publish', '=', $condition['publish']);
    //         }
    //         return $query;
    //     })->with('user_catalogues');
    //     if(!empty($join)) {
    //         $query->join(...$join);
    //     }
    //     return $query->paginate($perpage)
    //                  ->withQueryString()
    //                  ->withPath(env('APP_URL').$extend['path']);
    // }
}
