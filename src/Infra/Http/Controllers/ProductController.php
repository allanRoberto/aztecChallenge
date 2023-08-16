<?php
  namespace Controllers;
  use Psr\Http\Message\ResponseInterface as Response;
  use Psr\Http\Message\ServerRequestInterface as Request;

  use Repositories\Implementation\SQLiteProductRepository;
  use App\Infra\Persistence\SQLite\SQLiteConnection;

  use UseCases\GetAllProductUseCase;
  use UseCases\CreateProductUseCase;
  use UseCases\UpdateProductUseCase;
  use UseCases\DeleteProductUseCase;

  class ProductController 
  {
    private $db;
    public function __construct() {
     $this->db = (new SQLiteConnection())->connect();
    }

    /**
     * * @OA\Tag(
     *     name="product",
     *     description="Operações relacionadas ao produtos"
     * )
     * 
     * @OA\Get(
     *     path="/product/all",
     *      tags={"product"},
     *     summary="Exibe todos os produtos cadastrados",
     *     @OA\Response(response="200", description="Exibe os produtos cadastrados no banco de dados")
     * )
     */
    public function findAll(Request $request, Response $response, $args) {
      
      $getProducts = new GetAllProductUseCase(new SQLiteProductRepository($this->db));

      $data = $getProducts->execute();
      $response->getBody()->write(json_encode($data));
      
      return $response->withHeader('Content-Type', 'application/json');
    }

    /**
    * @OA\Post(
     *     path="/product/",
     *      tags={"product"},
     *     summary="Cria um novo produto",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Sabonete Dove"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Já existe uma produto com esse nome!"
     *      )
     * )
     */

    public function create(Request $request, Response $response, $args) {

      $data = $request->getParsedBody();
      $product = new CreateProductUseCase(new SQLiteProductRepository($this->db));
      $product->execute($data['name']);
      return $response->withStatus(201);
    }

    /**
    * @OA\Put(
     *     path="/product/{id}",
     *      tags={"product"},
     *     summary="Atualiza um novo produto",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da produto"
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Sabonete Dove"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     *      @OA\Response(
     *     response="400",
     *     description="Ocorreu um erro ao executar a ação, tente novamente mais tarde!"
     *      )
     * )
     */
    public function update(Request $request, Response $response, $args){

      $data = $request->getParsedBody();

      $id = $args['id'];
      $name = $data['name'];
      $updateProduct = new UpdateProductUseCase(new SQLiteProductRepository($this->db));
      $updateProduct->execute($id, $name);
            return $response->withStatus(201);


    }

    /**
    * @OA\Delete(
     *     path="/product/{id}",
     *      tags={"product"},
     *     summary="Excluir o produto",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID da produto"
     *     ),
     *      
     *     @OA\Response(response="201", description="Nenhum dado retornado"),
     * @OA\Response(
     *     response="400",
     *     description="Ocorreu um erro ao executar a ação, tente novamente mais tarde!"
     *      )
     * )
     */
    public function delete(Request $request, Response $response, $args){

      $data = $request->getParsedBody();

      $id = $args['id'];

      $deleteProduct = new DeleteProductUseCase(new SQLiteProductRepository($this->db));
      $deleteProduct->execute($id);
            return $response->withStatus(201);


    }

  }

