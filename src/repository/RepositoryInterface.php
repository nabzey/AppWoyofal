<?php
namespace App\Repository;

interface RepositoryInterface
{
    public static function getInstance(): static;
    public function find($id);
    public function save($entity): bool;
    public function delete($id): bool;
}
