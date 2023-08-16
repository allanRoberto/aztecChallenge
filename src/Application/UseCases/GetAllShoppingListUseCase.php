<?php
  namespace UseCases;

  final class GetAllShoppingListUseCase {
    private $shoppingListRepository;

    public function __construct($shoppingListRepository) {
      $this->shoppingListRepository = $shoppingListRepository;
    }

    public function execute() {
      $results = $this->shoppingListRepository->findAll();
      return $results;
    }
  }