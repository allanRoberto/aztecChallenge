<?php

  namespace UseCases;  

  final class UpdateShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute(string $id, string $name) {

      //Verificar se existe uma lista com esse ID;
      $foundShoppingList = $this->shoppingListRepository->findById($id);


      if(!$foundShoppingList) 
        throw new \Exception('Lista de compras não encontrada!');
      
      if($name === $foundShoppingList['name'])
        throw new \Exception('O nome da lista de compras não foi alterado!');
    
      $this->shoppingListRepository->update($foundShoppingList['id'], $name);
    }
  }