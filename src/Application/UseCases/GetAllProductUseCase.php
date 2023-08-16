<?php
  namespace UseCases;

  final class GetAllProductUseCase {
    private $productRepository;

    public function __construct($productRepository) {
      $this->productRepository = $productRepository;
    }

    public function execute() {
      $results = $this->productRepository->findAll();
      return $results;
    }
  }