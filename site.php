<?php

//ROTA PAGINA INICIAL

use \Hcode\Page;

$app->get('/', function() {
    
	
	$page = new Page();

	$page->setTpl("index");

});


?>