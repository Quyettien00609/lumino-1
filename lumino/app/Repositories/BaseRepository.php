<?php

namespace App\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all($columns = ["*"])
    {
        return $this->model->get($columns);
    }

    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function findBy($field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    public function login($credentials)
    {
        Auth::attempt($credentials);

        return Auth::user();

    }

    public function getItem(array $var)
    {
        $query = $this->model->where($var);

        return $query->get();
    }

    public function searchByFields( array $fields =[], $keyword)
    {
        $query = $this->model->query();

        foreach ($fields as $field) {

                // Nếu trường tìm kiếm hợp lệ, thêm điều kiện tìm kiếm
                $query->orWhere($field, 'like', '%' . $keyword . '%');
        }

        $results = $query->get(); // Sử dụng get() để lấy tất cả kết quả

        return $results;
    }


    public function orderBy(array $orderBy = [])
    {
        foreach ($orderBy as $field => $direction) {
            if ($direction === 'asc') {
                return $this->model->orderBy($field, 'asc');
            } elseif ($direction === 'desc') {
                return $this->model->orderBy($field, 'desc');
            }

        }

    }

    public function filterByName(string $nameFilter = null)
    {
        // Lọc tên theo ký tự nếu có
        if (!empty($nameFilter)) {
            $query = $this->model->where('name', 'like', '%' . $nameFilter . '%');
        }

        return $query;
    }

    public function getAll(array $fields = [], $keyword, array $orderBy = [],$perPage=null)
    {
        $query = $this->model->query();
        foreach ($fields as $field) {
            $query->orWhere($field, 'like', '%' . $keyword . '%');
        }
        foreach ($orderBy as $field => $direction) {
            if ($direction == 'asc') {
                $query->orderBy($field, 'asc');
            } elseif ($direction == 'desc') {
                $query->orderBy($field, 'desc');
            }
        }
        return   $query->paginate($perPage);
    }
    public function page($limit){
        return $this->model->paginate($limit);
    }
}
