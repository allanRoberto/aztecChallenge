<?php

  namespace UseCases;  

  final class DeleteProductUseCase {
    private $productRepository;

    public function __construct($productRepository) {
      $this->productRepository = $productRepository;
    }

    public function execute(string $id) {

      //Verificar se existe um produto com esse ID;
      $foundProduct = $this->productRepository->findById($id);

      if(!$foundProduct) throw new \Exception('Produto não encontrado');
      
        $this->productRepository->delete($foundProduct['id']);
      
    }
  }