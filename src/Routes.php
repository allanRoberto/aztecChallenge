<?php 
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *    title="API AZTEC CHALLENGE",
 *    version="0.1",
 *    description="Api desenvolvida para atender o desafio proposto pela empresa AZTEC",
 *    contact={
 *     "email": "allan.rsti@gmail.com",
 *     "name": "Allan Roberto Silva"
 *    },
 * )
 */
class OpenApi {}

  use Controllers\ShoppingListController;
  use Controllers\ProductController;
  use Slim\Routing\RouteCollectorProxy;



  $app->group('/shopping-list', function(RouteCollectorProxy $group){
    $group->get('/all', ShoppingListController::class.':findAll');
    $group->get('/{name}', ShoppingListController::class.':searchByName');
    $group->post('/', ShoppingListController::class.':create');
    $group->put('/{id}', ShoppingListController::class.':update');
    $group->delete('/{id}', ShoppingListController::class.':delete');
    $group->post('/{id}/duplicate', ShoppingListController::class.':duplicate');
    $group->post('/{id}/add-item', ShoppingListController::class.':addItem');
    $group->post('/{id}/remove-item', ShoppingListController::class.':removeItem');
    $group->post('/{id}/delete-item', ShoppingListController::class.':deleteItem');
  });

  $app->group("/product", function(RouteCollectorProxy $group){
    /**
     * @OA\Get(
     *   path="/products/all",
     *   summary="Get a list of products",
     *   @OA\Response(response="200", description="List of products")
     * )
     */
    $group->get('/all', ProductController::class.':findAll');
    $group->get('/{name}', ProductController::class.':searchByName');
    $group->post('/', ProductController::class.':create');
    $group->put('/{id}', ProductController::class.':update');
    $group->delete('/{id}', ProductController::class.':delete');
  });

  $app->get('/docs', function ($request, $response, array $args) {
    $swaggerUi = file_get_contents(dirname(__DIR__).'/public/docs/dist/index.html');
    return $response->write($swaggerUi);
  });

  $app->get('/{file}', function ($request,$response, $args) {
    $filePath = dirname(__DIR__) . '/public/docs/dist/' . $args['file'];

    if (!file_exists($filePath)) {
        return $response->withStatus(404, 'File Not Found');
    }

    switch (pathinfo($filePath, PATHINFO_EXTENSION)) {
        case 'css':
            $mimeType = 'text/css';
            break;

        case 'js':
            $mimeType = 'application/javascript';
            break;


        default:
            $mimeType = 'text/html';
    }

    $newResponse = $response->withHeader('Content-Type', $mimeType . '; charset=UTF-8');

    $newResponse->getBody()->write(file_get_contents($filePath));
    
    return $newResponse;
});

 
  
  