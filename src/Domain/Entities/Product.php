<?php 
  namespace Entities;

  class Product {
    private $id;
    public $name;
    public $createdAt;
    public $updatedAt;

    public function __construct($name){
      $this->id = uniqid();
      $this->name = $name;
      $this->createdAt = new \DateTime();
      $this->updatedAt = new \DateTime();
    }

    public function getId() {
      return $this->id;
    }
  }