<?php

  namespace UseCases;  

  final class DeleteShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute(string $id) {

      //Verificar se existe uma lista com esse ID;
      $foundShoppingList = $this->shoppingListRepository->findById($id);

      if(!$foundShoppingList) throw new \Exception('Lista de compras não encontrada');
        
      
      $this->shoppingListRepository->delete($foundShoppingList['id']);
    }
  }