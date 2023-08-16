<?php
namespace Repositories\Implementation;

use Entities\Product;
use Repositories\ProductRepository;

/**
 * Implementation of the product repository using SQLite.
 */
final class SQLiteProductRepository implements ProductRepository
{
    /**
     * @var \PDO Instance of PDO for database connection.
     */
    private $repository;

    /**
     * Constructor of the class.
     *
     * @param \PDO $repository PDO instance.
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates a new product.
     *
     * @param Product $product The product entity to be created.
     * @throws \Exception If an error occurs while creating the product.
     */
    public function create($product): void
    {
      try {
        $id = $product->getId();
        $createdAt = $product->createdAt->format("Y-m-d H:i:s");
        $updatedAt = $product->updatedAt->format("Y-m-d H:i:s");

        $sql =
            "INSERT INTO products (id, name, createdAt, updatedAt) VALUES (:id, :name, :createdAt, :updatedAt)";
        $stmt = $this->repository->prepare($sql);

        $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
        $stmt->bindParam(":name", $product->name, \PDO::PARAM_STR);
        $stmt->bindParam(":createdAt", $createdAt, \PDO::PARAM_STR);
        $stmt->bindParam(":updatedAt", $updatedAt, \PDO::PARAM_STR);
        $stmt->execute();
      }catch (\Exception $error) {
        throw new \Exception("Ocorreu um erro ao processar essa ação, tente novamente mais tarde!");
      }
        
    }

    /**
     * Updates a product.
     *
     * @param string $id ID of the product to update.
     * @param string $name New name of the product.
     * @throws \Exception If an error occurs while creating the product.
     */

    public function update($id, $name)
    {
        try {
            $updatedAt = new \DateTime();
            $updatedAt = $updatedAt->format("Y-m-d H:i:s");

            $sql =
                "UPDATE products SET name = :name, updatedAt = :updatedAt WHERE id = :id";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->bindParam(":name", $name, \PDO::PARAM_STR);
            $stmt->bindParam(":updatedAt", $updatedAt, \PDO::PARAM_STR);

            $stmt->execute();
        } catch (\Exception $error) {
        throw new \Exception("Ocorreu um erro ao processar essa ação, tente novamente mais tarde!");
      }
    }

    /**
     * Deletes a shopping list.
     *
     * @param string $id ID of the product to delete.
     * @return void
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);

            $stmt->execute();
        } catch (\Exception $error) {
        throw new \Exception("Ocorreu um erro ao processar essa ação, tente novamente mais tarde!");
      }
    }

    /**
     * Retrieves a product by its ID.
     *
     * @param string $id ID of the product to retrieve.
     * @return mixed|null product data or null if not found.
     */
    public function findById(string $id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll();
            return $result[0];
        } catch (\Exception $error) {
        throw new \Exception("Ocorreu um erro ao processar essa ação, tente novamente mais tarde!");
      }
    }

    /**
     * Retrieves all products.
     *
     * @return array Array of products.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM products";

        $stmt = $this->repository->query($sql);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }
}
