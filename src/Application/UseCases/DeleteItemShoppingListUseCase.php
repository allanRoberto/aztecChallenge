<?php

  namespace UseCases;
  use Entities\ShoppingList;
  

  final class DeleteItemShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute(string $idShoppingList, string $idItem) {

      $foundItem = $this->shoppingListRepository->checkExistsItemById($idShoppingList, $idItem);

      if($foundItem) {
        $this->shoppingListRepository->deleteItem($idShoppingList, $idItem);
      }
    }
  }