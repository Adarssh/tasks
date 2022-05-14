<?php
namespace App\Repositories;

use App\Http\Resources\ExpenseCollection;


interface IRepository
{
    public function all(array $data);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): void;
}
