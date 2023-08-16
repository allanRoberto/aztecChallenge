<?php 
require  'vendor/autoload.php';
use Slim\Factory\AppFactory;

$app = AppFactory::create();

require __DIR__.'/src/Routes.php';

$openapi = \OpenApi\Generator::scan([__DIR__.'/src']); 
file_put_contents('public/docs/dist/openapi.json', $openapi->toJson());

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

use App\Infra\Persistence\SQLite\SetupDatabase;

try {
  (new SetupDatabase())->setup();
}catch(Exception $error) {
  var_dump($error);
}



$app->run();