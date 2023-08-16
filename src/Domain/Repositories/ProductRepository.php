<?php
  namespace Repositories;

  interface ProductRepository {
    public function create($product) : void;
    public function update(string $id, $name);
    public function delete(string $id);
    public function findAll();
    public function findById(string $id);
  }