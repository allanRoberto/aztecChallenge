<?php
namespace Repositories\Implementation;

use Entities\ShoppingList;
use Repositories\ShoppinglistRepository;

/**
 * Implementation of the shopping list repository using SQLite.
 */
final class SQLiteShoppingListRepository implements ShoppingListRepository
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
     * Creates a new shopping list.
     *
     * @param ShoppingList $shoppingList Shopping list object.
     * @return void
     */
    public function create(ShoppingList $shoppingList): void
    {
        try {
            $id = $shoppingList->getId();
            $createdAt = $shoppingList->createdAt->format("Y-m-d H:i:s");
            $updatedAt = $shoppingList->updatedAt->format("Y-m-d H:i:s");

            $sql =
                "INSERT INTO shopping_lists (id, name, createdAt, updatedAt) VALUES (:id, :name, :createdAt, :updatedAt)";
            $stmt = $this->repository->prepare($sql);

            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->bindParam(":name", $shoppingList->name, \PDO::PARAM_STR);
            $stmt->bindParam(":createdAt", $createdAt, \PDO::PARAM_STR);
            $stmt->bindParam(":updatedAt", $updatedAt, \PDO::PARAM_STR);

            $stmt->execute();
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Updates a shopping list.
     *
     * @param string $id ID of the shopping list to update.
     * @param string $name New name of the shopping list.
     * @return void
     */

    public function update($id, $name): void
    {
        try {
            $updatedAt = new \DateTime();
            $updatedAt = $updatedAt->format("Y-m-d H:i:s");

            $sql =
                "UPDATE shopping_lists SET name = :name, updatedAt = :updatedAt WHERE id = :id";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->bindParam(":name", $name, \PDO::PARAM_STR);
            $stmt->bindParam(":updatedAt", $updatedAt, \PDO::PARAM_STR);

            $stmt->execute();
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Deletes a shopping list.
     *
     * @param string $id ID of the shopping list to delete.
     * @return void
     */

    public function delete($id): void
    {
        try {
            $sql = "DELETE FROM shopping_lists WHERE id = :id";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);

            $stmt->execute();
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Retrieves a shopping list by its ID.
     *
     * @param string $id ID of the shopping list to retrieve.
     * @return mixed|null Shopping list data or null if not found.
     */
    public function findById(string $id)
    {
        try {
            $sql = "SELECT * FROM shopping_lists WHERE id = :id";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll();
            return $result[0];
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Retrieves all shopping lists with their associated items.
     *
     * @return array Array of shopping lists and their items.
     */
    public function findAll(): array
    {
        try {
        $sql = "SELECT sl.id AS list_id, sl.name AS list_name, p.id AS product_id, p.name AS product_name, i.id AS itemId, i.qty
         FROM shopping_lists AS sl
         LEFT JOIN items AS i ON sl.id = i.shopping_list_id
        LEFT JOIN products AS p ON i.product_id = p.id";
            $stmt = $this->repository->query($sql);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $lists = [];

            foreach ($results as $row) {
                $product_id = $row["product_id"];
                $listName = $row["list_name"];
                $listId = $row["list_id"];
                $itemName = $row["product_name"];
                $itemId = $row["itemId"];
                $itemQty = $row["qty"];

                if (!isset($lists[$listName])) {
                    $lists[$listName] = [
                        "id" => $listId,
                        "name" => $listName,
                        "items" => [],
                    ];
                }

                if ($itemName) {
                    $lists[$listName]["items"][] = [
                        "id" => $itemId,
                        'idProduct' => $product_id,
                        "name" => $itemName,
                        "qty" => $itemQty,
                    ];
                }
            }

            return array_values($lists);
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Adds an item to a shopping list.
     *
     * @param string $idItem ID of the item.
     * @param string $idShoppingList ID of the shopping list.
     * @param string $idProduct ID of the product.
     * @param int $qty Quantity of the item.
     * @throws Exception If an error occurs while adding the item.
     */
    public function addItem(string $idItem, string $idShoppinglist, string $idProduct, int $qty)
    { 
  
        try {
            $sql =
                "INSERT INTO items (id, shopping_list_id, product_id, qty) VALUES (:id, :shopping_list_id, :product_id, :qty)";

            $stmt = $this->repository->prepare($sql);

            $stmt->bindParam(":id", $idItem, \PDO::PARAM_STR);
            $stmt->bindParam(
                ":shopping_list_id",
                $idShoppinglist,
                \PDO::PARAM_STR
            );
            $stmt->bindParam(":product_id", $idProduct, \PDO::PARAM_STR);
            $stmt->bindParam(":qty", $qty, \PDO::PARAM_INT);

            $stmt->execute();
        } catch (\Exception $error) {
            throw new \Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Removes an item from a shopping list.
     *
     * @param string $id ID of the item.
     * @param string $idShoppingList ID of the shopping list.
     * @throws Exception If an error occurs while removing the item.
     */
    public function removeItem(string $id, string $idShoppingList)
    {
        try {
            $sql =
                "DELETE FROM items WHERE id = :id AND shopping_list_id = :shopping_list_id";

            $stmt = $this->repository->prepare($sql);

            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->bindParam(
                ":shopping_list_id",
                $idShoppinglist,
                \PDO::PARAM_STR
            );

            $stmt->execute();
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Updates the quantity of an item.
     *
     * @param string $id ID of the item.
     * @param int $qty New quantity of the item.
     * @throws Exception If an error occurs while updating the item.
     */
    public function updateItem(string $id, int $qty)
    {
        try {
            $sql = "UPDATE items SET qty = :qty  WHERE id = :id";

            $stmt = $this->repository->prepare($sql);

            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->bindParam(":qty", $qty, \PDO::PARAM_INT);

            $stmt->execute();
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Retrieves all items of a specific shopping list.
     *
     * @param string $idShoppingList ID of the shopping list.
     * @return array Array containing the fetched items.
     * @throws Exception If an error occurs while fetching the items.
     */
    public function getItems(string $idShoppinglist)
    {
        try {
            $sql =
                "SELECT * FROM items WHERE shopping_list_id = :shopping_list_id";

            $stmt = $this->repository->prepare($sql);

            $stmt->bindParam(
                ":shopping_list_id",
                $idShoppinglist,
                \PDO::PARAM_STR
            );

            $stmt->execute();

            $result = $stmt->fetchAll();

            return $result;
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Checks if an item with the given shopping list ID and product ID exists.
     *
     * @param string $idShoppinglist ID of the shopping list.
     * @param string $idProduct ID of the product.
     * @return array Fetched result or an empty array if not found.
     */

    public function checkExistsItem(
        string $idShoppinglist,
        string $idProduct
    ): array {
        try {
            $sql =
                "SELECT * FROM items WHERE shopping_list_id = :shopping_list_id AND product_id = :product_id LIMIT 1";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(
                ":shopping_list_id",
                $idShoppinglist,
                \PDO::PARAM_STR
            );
            $stmt->bindParam(":product_id", $idProduct, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Checks if an item with the given ID and shopping list ID exists.
     *
     * @param string $id ID of the item.
     * @param string $idShoppingList ID of the shopping list.
     * @return array Fetched result or an empty array if not found.
     */
    public function checkExistsItemById(string $id, string $idShoppingList)
    {
        try {
            $sql =
                "SELECT * FROM items WHERE id = :id AND shopping_list_id = :shopping_list_id LIMIT 1";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->bindParam(
                ":shopping_list_id",
                $idShoppingList,
                \PDO::PARAM_STR
            );
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }

    /**
     * Checks if a shopping list with the given name exists.
     *
     * @param string $name Name of the shopping list.
     * @return array Fetched result or an empty array if not found.
     */
    public function checkExistsShoppingListByName(string $name)
    {
        try {
            $sql = "SELECT * FROM shopping_lists WHERE name LIKE :name";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":name", $name, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }
    /**
     * Checks if a shopping list with the given ID exists.
     *
     * @param string $id ID of the shopping list.
     * @return array Fetched result or an empty array if not found.
     */
    public function checkExistsShoppingListById(string $id)
    {
        try {
            $sql = "SELECT * FROM shopping_lists WHERE id LIKE :id";
            $stmt = $this->repository->prepare($sql);
            $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (\Exception $error) {
            throw new Exception(
                "Ocorreu um erro ao realizar a ação, tente novamente mais tarde!"
            );
        }
    }
}
