<?php

  namespace UseCases;  

  final class UpdateProductUseCase {
    private $productRepository;

    public function __construct($productRepository) {
      $this->productRepository = $productRepository;
    }

    public function execute(string $id, string $name) {

      //Verificar se existe uma lista com esse ID;
      $foundProduct = $this->productRepository->findById($id);


      if(!$foundProduct) 
        throw Error('Lista de compras não encontrada!');
      
      if($name === $foundProduct['name'])
        throw Error('O nome da lista de compras não foi alterado!');
    
      $this->productRepository->update($foundProduct['id'], $name);
    }
  }