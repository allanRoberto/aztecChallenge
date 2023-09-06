<?php

namespace Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Repositories\Implementation\SQLiteShoppingListRepository;
use App\Infra\Persistence\SQLite\SQLiteConnection;

use UseCases\GetAllShoppingListUseCase;
use UseCases\CreateShoppingListUseCase;
use UseCases\UpdateShoppingListUseCase;
use UseCases\DeleteShoppingListUseCase;
use UseCases\DuplicateShoppingListUseCase;
use UseCases\AddItemShoppingListUseCase;
use UseCases\RemoveItemShoppingListUseCase;
use UseCases\DeleteItemShoppingListUseCase;

class ShoppingListController
{
    private $db;
    public function __construct()
    {
        $this->db = (new SQLiteConnection())->connect();
    }

    /**
     * @OA\Tag(
     *     name="shopping-list",
     *     description="Operações relacionadas ao cadastro de listas de compras"
     * )
     * @OA\Get(
     *     path="/shopping-list/all",
     *      tags={"shopping-list"},
     *     summary="Exibe todas as listas de compras",
     *     @OA\Response(response="200", description="Exibe as listas de compras cadastradas no banco de dados")
     * )
     */
    public function findAll(Request $request, Response $response, $args)
    {
        $getLists = new GetAllShoppingListUseCase(
            new SQLiteShoppingListRepository($this->db)
        );

        $data = $getLists->execute();
        $response->getBody()->write(json_encode($data));

        return $response->withHeader("Content-Type", "application/json");
    }

    public function findById()
    {
    }

    public function searchByName()
    {
    }

    /**
     * @OA\Post(
     *     path="/shopping-list/",
     *      tags={"shopping-list"},
     *     summary="Cria uma nova lista de compras",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Supermercado"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Já existe uma lista de compras com essa descrição!"
     *      )
     * )
     */
    public function create(Request $request, Response $response, $args)
    {
        try {
            $data = $request->getParsedBody();
            $createShoppingList = new CreateShoppingListUseCase(
                new SQLiteShoppingListRepository($this->db)
            );
            $createShoppingList->execute($data["name"]);
            $message = ["message" => "Lista criada com sucesso"];
            return $response->withJson($message, 201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $responseBody = ["error" => $errorMessage];
            return $response->withJson($responseBody, 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/shopping-list/{id}",
     *      tags={"shopping-list"},
     *     summary="Atualiza uma lista de compras",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da lista"
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          @OA\Property(property="name", type="string", example="Supermercado"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Já existe uma lista de compras com essa descrição!"
     *      )
     * )
     */
    public function update(Request $request, Response $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $id = $args["id"];
            $name = $data["name"];
            $updateShoppinglist = new UpdateShoppingListUseCase(
                new SQLiteShoppingListRepository($this->db)
            );
            $updateShoppinglist->execute($id, $name);
            $message = ["message" => "Lista atualizada com sucesso"];
            return $response->withJson($message, 201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $responseBody = ["error" => $errorMessage];
            return $response->withJson($responseBody, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/shopping-list/{id}",
     *      tags={"shopping-list"},
     *     summary="Exclui uma lista de compras",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da lista"
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Ocorreu um erro ao realizar a ação tente novamente mais tarde!"
     *      )
     * )
     */
    public function delete(Request $request, Response $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $id = $args["id"];

            $deleteShoppinglist = new DeleteShoppingListUseCase(
                new SQLiteShoppingListRepository($this->db)
            );
            $deleteShoppinglist->execute($id);
            $message = ["message" => "Lista excluída com sucesso"];
            return $response->withJson($message, 201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $responseBody = ["error" => $errorMessage];
            return $response->withJson($responseBody, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/shopping-list/{id}/duplicate",
     *      tags={"shopping-list"},
     *     summary="Duplica uma lista de compras",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da lista"
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Ocorreu um erro ao realizar a ação tente novamente mais tarde!"
     *      )
     * )
     */
    public function duplicate(Request $request, Response $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $id = $args["id"];

            $addItemShoppinglist = new DuplicateShoppingListUseCase(
                new SQLiteShoppingListRepository($this->db)
            );
            $result = $addItemShoppinglist->execute($id);
            $message = ["message" => "Lista duplicada com sucesso"];
            return $response->withJson($message, 201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $responseBody = ["error" => $errorMessage];
            return $response->withJson($responseBody, 500);
        }
    }

     /**
     * @OA\Post(
     *     path="/shopping-list/{id}/add-item",
     *      tags={"shopping-list"},
     *     summary="Adiciona um item, ou aumenta a quantidade dele na lista de compras",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da lista"
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          @OA\Property(property="idProduct", type="string", example=""),
     *          @OA\Property(property="qty", type="int", example="2"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Ocorreu um erro ao realizar a ação tente novamente mais tarde!"
     *      )
     * )
     */
    public function addItem(Request $request, Response $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $id = $args["id"];
            $idProduct = $data["idProduct"];
            $qty = $data["qty"];

            $addItemShoppinglist = new AddItemShoppingListUseCase(
                new SQLiteShoppingListRepository($this->db)
            );
            $result = $addItemShoppinglist->execute($id, $idProduct, $qty);
            $message = ["message" => "Item atualizado com sucesso"];
            return $response->withJson($message, 201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $responseBody = ["error" => $errorMessage];
            return $response->withJson($responseBody, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/shopping-list/{id}/remove-item",
     *      tags={"shopping-list"},
     *     summary="Exclui um item, ou diminui a quantidade dele na lista de compras",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da lista"
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          @OA\Property(property="idProduct", type="string", example=""),
     *          @OA\Property(property="qty", type="int", example="2"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Ocorreu um erro ao realizar a ação tente novamente mais tarde!"
     *      )
     * )
     */
    public function removeItem(Request $request, Response $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $id = $args["id"];
            $idProduct = $data["idProduct"];
            $qty = $data["qty"];

            $removeItemShoppinglist = new RemoveItemShoppingListUseCase(
                new SQLiteShoppingListRepository($this->db)
            );
            $result = $removeItemShoppinglist->execute($id, $idProduct, $qty);
            $message = ["message" => "Item atualizado com sucesso"];
            return $response->withStatus(201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $responseBody = ["error" => $errorMessage];
            return $response->withJson($responseBody, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/shopping-list/{id}/remove-item",
     *      tags={"shopping-list"},
     *     summary="Exclui um item na lista de compras",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da lista"
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          @OA\Property(property="idItem", type="string", example=""),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Ocorreu um erro ao realizar a ação tente novamente mais tarde!"
     *      )
     * )
     */
    public function deleteItem(Request $request, Response $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $id = $args["id"];
            $idItem = $data["idItem"];

            $addItemShoppinglist = new DeleteItemShoppingListUseCase(
                new SQLiteShoppingListRepository($this->db)
            );
            $result = $deleteItemShoppinglist->execute($id, $idItem);
            $message = ["message" => "Item excluído com sucesso"];
            return $response->withJson($message, 201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $responseBody = ["error" => $errorMessage];
            return $response->withJson($responseBody, 500);
        }
    }
}
