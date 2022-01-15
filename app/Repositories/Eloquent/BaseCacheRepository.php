<?php
namespace App\Repositories\Eloquent;

use Illuminate\Cache\CacheManager;
use App\Interfaces\EloquentRepositoryInterface;
use App\Notifications\SendNotification;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class BaseCacheRepository implements EloquentRepositoryInterface
{
    protected $repo;

    protected $cache;

    const TTL = 1440; # 1 day

    public function __construct(CacheManager $cache, BaseRepository $repo) {
        $this->repo = $repo;
        $this->cache = $cache;
    }

   /**
     * @return Collection
     */
    public function all()
    {
        $data = Cache::remember('users', self::TTL, function () {
            return $this->model->all();
        });
        return $this->success("All", $data);
    }

    /**
     * @return Collection
     */
    public function all_delete()
    {
        $data = Cache::remember('users_delete', self::TTL, function () {
            return $this->model->onlyTrashed()->get();
        });
        return $this->success("All", $data);
    }

    /**
     * @param $id
     * @return ResponseApi_Trait
     */
    public function find($id)
    {
        try {
            $data = $this->model->find($id);
            if (!$data) {
                return $this->error("No element with ID $id", 404);
            }

            return $this->success("Detail", $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Auth::user()->notify(new SendNotification(Auth::user()->name, ' BaseRepository/find: ' . $e->getMessage()));
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create_or_update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'unique_attribute' => ['required'],
            'update_attribute' => ['required'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->error($errors, 500);
        }
        $unique_attribute = json_decode($request->unique_attribute, true);
        $update_attribute = json_decode($request->update_attribute, true);
        return $this->model->updateOrCreate($unique_attribute, $update_attribute);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'where_attribute' => ['required'],
            'update_attribute' => ['required'],
        ]);
       
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->error($errors, 500);
        }
        $where_attribute = json_decode($request->where_attribute, true);
        $update_attribute = json_decode($request->update_attribute, true);
        return $this->model->update($where_attribute, $update_attribute);
    }

    /**
     * @param $id
     * @return ResponseApi_Trait
     */
    public function find_delete($id)
    {

        $data_set = ['id' => $id];
        $validator = \Validator::make($data_set, [
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response($errors, 500);
        }
        try {
            $data = $this->model->onlyTrashed()->find($id);
            if (!$data) {
                return $this->error("No element with ID $id", 404);
            }
            return $this->success("Detail", $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Auth::user()->notify(new SendNotification(Auth::user()->name, ' BaseRepository/find_delete: ' . $e->getMessage()));
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function get_data(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                // 'filter' => ['required'],
                'row_perpage' => ['integer'],
                'sort' => ['in:asc,desc'],
                'page_num' => ['integer'],
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();

                return response($errors, 500);
            }

            $filter_array = \json_decode($request->filter, true);
            $row_perpage = ($request->row_perpage == null) ? 10 : $request->row_perpage;
            $page_num = ($request->page_num == null) ? '0' : $request->page_num;
            $sort = ($request->sort == null) ? 'asc' : $request->sort;

            if ($page_num == '0') {

                // dd( $filter_array[1]);
                $users = $this->model->where($filter_array)
                    ->orderBy('id', $sort)->get();

            } else {
                $users = $this->model->where($filter_array)
                    ->orderBy('id', $sort)
                    ->paginate($row_perpage, ['*'], 'page', $pageNumber);
            }
            return $this->success("Query Users", $users);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Auth::user()->notify(new SendNotification(Auth::user()->name, ' BaseRepository/get_data: ' . $e->getMessage()));
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function get_data_delete(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                // 'filter' => ['required'],
                'row_perpage' => ['integer'],
                'sort' => ['in:asc,desc'],
                'page_num' => ['integer'],
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();

                return response($errors, 500);
            }

            $filter_array = \json_decode($request->filter, true);
            $row_perpage = ($request->row_perpage == null) ? 10 : $request->row_perpage;
            $page_num = ($request->page_num == null) ? '0' : $request->page_num;
            $sort = ($request->sort == null) ? 'asc' : $request->sort;

            if ($page_num == '0') {
                $users = $this->model->onlyTrashed()->where($filter_array)->orderBy('id', $sort)->get();

            } else {
                $users = $this->model->onlyTrashed()->where($filter_array)->orderBy('id', $sort)->paginate($row_perpage, ['*'], 'page', $pageNumber);
            }
            return $this->success("Query Users", $users);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Auth::user()->notify(new SendNotification(Auth::user()->name, ' BaseRepository/get_data_delete: ' . $e->getMessage()));
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $id
     * @return Model
     */
    public function delete_data($id)
    {
        try {
            $this->model->destroy($id);
            return $this->success("Detail", null);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Auth::user()->notify(new SendNotification(Auth::user()->name, ' BaseRepository/delete_data: ' . $e->getMessage()));
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $id
     * @return Model
     */
    public function force_delete_data($id)
    {
        try {
            $data = $this->model->withTrashed()->find($id);
            if (!$data) {
                return $this->error("No element with ID $id", 404);
            }

            $this->model->withTrashed()->find($id)->forceDelete();
            return $this->success("Detail", null);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Auth::user()->notify(new SendNotification(Auth::user()->name, ' BaseRepository/force_delete_data: ' . $e->getMessage()));
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}