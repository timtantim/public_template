<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @return Model
     */
    public function all();

    /**
     * @return Model
     */
    public function all_delete();


    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * @param array $attributes
     * @return Model
     */
    public function create_or_update(Request $request);

      /**
     * @param array $attributes
     * @return Model
     */
    public function update(Request $request);//array $where_attributes,array $update_attributes

    /**
     * @param $id
     * @return Model
     */
    public function find($id);

    /**
     * @param $id
     * @return ResponseApi_Trait
     */
    public function find_delete($id);
    /**
     * @param Request
     * @return ResponseApi_Trait
     */
    public function get_data(Request $request);

    /**
     * @param Request
     * @return Model
     */
    public function get_data_delete(Request $request);

    /**
     * @param $id
     * @return Model
     */
    public function delete_data($id);

    /**
     * @param $id
     * @return Model
     */
    public function force_delete_data($id);

}
