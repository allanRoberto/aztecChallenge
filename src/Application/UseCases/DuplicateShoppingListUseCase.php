<?php

  namespace UseCases;
  use Entities\ShoppingList;
  

  final class DuplicateShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute($id) {
      
      $shoppingListFound = $this->shoppingListRepository->checkExistsShoppingListById($id);
      if(!$shoppingListFound) {
        throw new \Exception('Não foi encontrada nenhuma lista com esse ID');
      }

      $name = $shoppingListFound[0]['name'] . ' cópia';
      
      
      $shoppingList = new ShoppingList($name);
      $idShoppingList = $shoppingList->getId();
      $this->shoppingListRepository->create($shoppingList);

      $items = $this->shoppingListRepository->getItems($shoppingListFound[0]['id']);

      if($items) {
        foreach($items as $item){
          $itemId = uniqid();
          $this->shoppingListRepository->addItem($itemId, $idShoppingList, $item['product_id'], $item['qty']);
        }
      }



    }
  }

