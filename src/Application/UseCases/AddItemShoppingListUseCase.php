<?php

  namespace UseCases;
  use Entities\ShoppingList;
  

  final class AddItemShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute(string $idShoppinglist, string $idProduct, int $qty) {

      $idItem = uniqid();

      

      $foundItem = $this->shoppingListRepository->checkExistsItem($idShoppinglist, $idProduct);

      if($foundItem) {
        $updateQty = $foundItem[0]['qty'] + $qty; 
        $this->shoppingListRepository->updateItem($foundItem[0]['id'],$updateQty);
      }else{
        $this->shoppingListRepository->addItem($idItem, $idShoppinglist, $idProduct, $qty);
      }
    }
  }