<?php

  namespace UseCases;
  use Entities\ShoppingList;
  

  final class CreateShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute($name) {
      
      $shoppingListFound = $this->shoppingListRepository->checkExistsShoppingListByName($name);
      if($shoppingListFound) {
        throw new \Exception('Já existe uma lista de compras com essa descrição!');
      }
      $shoppingList = new ShoppingList($name);



      $this->shoppingListRepository->create($shoppingList);
    }
  }