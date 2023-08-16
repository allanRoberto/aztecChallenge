<?php

  namespace UseCases;  

  final class DeleteProductUseCase {
    private $productRepository;

    public function __construct($productRepository) {
      $this->productRepository = $productRepository;
    }

    public function execute(string $id) {

      //Verificar se existe uma lista com esse ID;
      $foundProduct = $this->productRepository->findById($id);


      if(!$foundproduct) 
        throw Error('Lista de compras não encontrada!');
      
      $this->productRepository->delete($foundProduct['id']);
    }
  }