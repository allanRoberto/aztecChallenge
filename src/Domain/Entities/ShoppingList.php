<?php 
  namespace Entities;

  class ShoppingList {
    private $id;
    public $name;
    public $items;
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

    public function addItem($item) {
      $this->items.push($item);
    }
  }