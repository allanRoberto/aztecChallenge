<?php

  namespace UseCases;
  use Entities\Product;
  

  final class CreateProductUseCase {
    private $productRepository;

    public function __construct($productRepository) {
      $this->productRepository = $productRepository;
    }

    public function execute($name) {
      $product = new Product($name);
      $this->productRepository->create($product);
    }
  }