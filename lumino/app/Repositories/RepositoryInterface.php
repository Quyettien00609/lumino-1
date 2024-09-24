<?php

namespace App\Repositories;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
interface RepositoryInterface
{
    public function all($columns = ["*"]);

    public function create($attributes = []);

    public function login($credentials);

    public function update($id, $attributes = []);

    public function delete($id);

    public function find($id, $columns = ['*']);

    public function findBy( $field, $value);

    public function orderBy( array $orderBy = []);


    public function filterByName( string $nameFilter = null);

    public function getAll(array $fields = [], $keyword, array $orderBy = [],$perPage=10);

    public function searchByFields( array $fields =[], $keyword);
}
