<?php
namespace App\Infra\Persistence\SQLite; 
use App\Config;

class SetupDatabase {

  private $pdo;

  public function __construct() {
      $this->pdo = new \PDO("sqlite:" . Config::PATH_DATABASE);
  }

  public function setup(){
  $this->pdo->exec('CREATE TABLE IF NOT EXISTS shopping_lists (
    id STRING PRIMARY KEY UNIQUE NOT NULL,
    name TEXT NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    )');
  
  $this->pdo->exec('CREATE TABLE IF NOT EXISTS items(
      id STRING PRIMARY KEY UNIQUE NOT NULL,
      shopping_list_id STRING NOT NULL,
      product_id STRING NOT NULL,
      qty INTEGER NOT NULL,
      FOREIGN KEY (shopping_list_id) REFERENCES shopping_lists(id),
      FOREIGN KEY (product_id) REFERENCES products(id)
    )');

  $this->pdo->exec('CREATE TABLE IF NOT EXISTS products (
    id STRING PRIMARY KEY UNIQUE NOT NULL,
    name TEXT NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    )');
  }
}
  

  