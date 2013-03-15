<?php

require_once 'Requisitos/Controller/Cadastre.php';

require_once 'Requisitos/Controller/Login.php';

require_once 'Requisitos/Controller/Home.php';

require_once 'Requisitos/Controller/Perfil.php';

require_once 'Requisitos/Controller/Projeto.php';

require_once 'Requisitos/Controller/Requisito.php';

require_once 'Requisitos/Controller/Cliente.php';

require_once 'Requisitos/Controller/Contato.php';

require_once 'Requisitos/Controller/Error.php';

// Verifica se o usuario esta logado ou não


$app->before(function() use ( $app ){

	if( ( $app['path_url']  != $app['path'].'/login' ) && ( $app['path_url']  != $app['path'].'/cadastre' ) ){
		if ( ( null === $user = $app['session']->get('user') ) || TRUE !== $app['session']->get('auth') ) {
        	return $app->redirect('/willian/requisitos/web/login');
    	}
	}	
	
});