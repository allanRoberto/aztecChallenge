<?php

  namespace UseCases;
  use Entities\ShoppingList;
  

  final class RemoveItemShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute(string $idShoppingList, string $idProduct, int $qty) {

      $idItem = uniqid();

      $foundItem = $this->shoppingListRepository->checkExistsItem($idShoppingList, $idProduct);
      if($foundItem) {
        $updateQty = $foundItem[0]['qty'] - $qty; 

        if($updateQty <= 0) {
          $this->shoppingListRepository->removeItem($foundItem[0]['id'], $foundItem[0]['shopping_list_id']);
        }else{
          $this->shoppingListRepository->updateItem($foundItem[0]['id'],$updateQty);
        }
      }else{
        $this->shoppingListRepository->addItem($idItem, $idShoppingList, $idProduct, $qty);
      }
    }
  }