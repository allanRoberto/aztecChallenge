<?php
  namespace Repositories;
  use Entities\ShoppingList;

  interface ShoppingListRepository {
    public function create(ShoppingList $shoppingList) : void;
    public function update(string $id, $name);
    public function delete(string $id);
    public function findAll();
    public function findById(string $id);
    public function addItem(string $idItem, string $idShoppinglist, string $idProduct, int $qty);
    public function removeItem(string $id, string $idShoppinglist);
    public function updateItem(string $idProduct, int $qty);
    public function getItems(string $idShoppinglist);
    public function checkExistsItem(string $idShoppinglist, string $idProduct);
    public function checkExistsItemById(string $id, string $idShoppinglist);
    public function checkExistsShoppingListByName(string $name);
    public function checkExistsShoppingListById(string $id);
  }