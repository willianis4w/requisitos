<?php

require_once 'Requisitos/Controller/Home.php';

require_once 'Requisitos/Controller/Login.php';

require_once 'Requisitos/Controller/Perfil.php';

require_once 'Requisitos/Controller/Projeto.php';

require_once 'Requisitos/Controller/Requisito.php';

require_once 'Requisitos/Controller/Cliente.php';

require_once 'Requisitos/Controller/Contato.php';

require_once 'Requisitos/Controller/Error.php';

// Verifica se o usuario esta logado ou não
$app->before(function(  ) use ( $app ){

	if(  $_SERVER['REQUEST_URI'] != '/requisitos/web/login'){
		if ( ( null === $user = $app['session']->get('user') ) || TRUE !== $app['session']->get('auth') ) {
        	return $app->redirect('login');
    	}
	}
});