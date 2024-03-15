<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use Hcode\PageAdmin;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});


$app->get('/cotec', function() { #nome da rota para acesso ao Admin
    
	$page = new PageAdmin();

	$page->setTpl("index");

});


$app->run();

 ?>