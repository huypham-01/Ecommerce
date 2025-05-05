<?php

namespace App\Services;
use App\Services\Interfaces\PostServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;
/**
 * Class UserCatalogueService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $routerRepository;
    public function __construct(
        PostRepository $postRepository,
        RouterRepository $routerRepository
    ) {
        $this->postRepository = $postRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'PostController';
    }
    public function paginate($request, $languageId) {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId]
        ];
        $paePage = $request->integer('perpage');
        $post = $this->postRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'post/index'],
            [
                'posts.id', 'DESC'
            ],
            [
                ['post_language as tb2', 'tb2.post_id', '=', 'posts.id'],
                ['post_catalogue_post as tb3', 'posts.id', '=', 'tb3.post_id'],
            ],
            ['post_catalogues'],
            $this->whereRaw($request, $languageId)
        );
        return $post;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            $post = $this->createPost($request);
            if($post->id > 0) {
                $this->updateLanguageForPost($post, $request, $languageId);
                $this->updateCatalogueForPost($post, $request);
                $this->createRouter($post, $request, $this->controllerName, $languageId);
            }

            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function update($id, $request, $languageId) {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->findById($id);
            if($this->uploadPost($post, $request)) {
                $this->updateLanguageForPost($post, $request, $languageId);
                
                $this->updateCatalogueForPost($post, $request);
                $this->updateRouter($post, $request, $this->controllerName, $languageId);
            }
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'app\Http\Controller\Forntend\PostController'],
            ]);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            //echo $e->getMessage();die();
            return false;
        }
    }
    //
    private function createPost($request) {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($request);
        $post = $this->postRepository->create($payload);
        return $post;
    }
    private function uploadPost($post, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $flag = $this->postRepository->update($post->id, $payload);
    }


    private function updateLanguageForPost($post, $request, $languageId) {
        $payload = $request->only($this->payloadLaguage());
        $payload = $this->formatLanguagePayload($payload, $post->id, $languageId);
        
        $post->languages()->detach([$languageId, $post->id]);
        $aa =  $this->postRepository->createPivot(
            $post,
            $payload, 
            'languages'
        );
        return $aa;
    }
    private function updateCatalogueForPost($post, $request) {
        $post->post_catalogues()->sync($this->catalogue($request));
    }
    private function formatLanguagePayload($payload, $postId, $languageId) {
        $payload['language_id'] = $languageId;
        $payload['post_id'] = $postId;
        $payload['canonical'] = Str::slug($payload['canonical']);
        return $payload;
    }

 
    //
 

    private function catalogue($request) {
        if ($request->input('catalogue') != null) {
            return array_unique(array_merge($request->input('catalogue'), [$request->post_catalogue_id]));
        }
        return [$request->post_catalogue_id];
    }
    private function whereRaw($request, $languageId) {
        $rawCondition = [];
        if($request->integer('post_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id 
                        FROM post_catalogues 
                        JOIN post_catalogue_language ON post_catalogues.id = post_catalogue_language.post_catalogue_id
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?) 
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }
    private function paginteSelect() {
        return [
            'posts.id',
            'posts.publish',
            'posts.image',
            'posts.order',
            'tb2.name',
            'tb2.canonical',
        ];
    }
    private function payload() {
        return [
            'follow', 
            'publish', 
            'image',
            'album',
            'post_catalogue_id',
        ];
    }
    private function payloadLaguage() {
        return [
            'name', 
            'description', 
            'content',
            'meta_title',
            'meta_keyword', 
            'meta_description', 
            'canonical'
        ];
    }
}
