<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/****
    Login
****/

// login - GET
$app->get('/esqueceu', function () use ($app) {
    $dados = array(
        'error' => ''
    );
    
    return $app['twig']->render('esqueceu.html',$dados);
});

// login - POST
$app->post('/esqueceu', function (Request $request) use ($app,$entityManager) {
    $dados = array(
        'email' => $email,
        'senha' => $senha
    );
    
    $usuario = $entityManager->getRepository('Requisitos\Model\Usuario')->findBy( $dados );

    if( !empty($usuario) ) {
        // registra session
        
        $user = array(
            'id' => $usuario[0]->getId(),
            'nome' => $usuario[0]->getNome(),
            'email' => $usuario[0]->getEmail()
        );

        $app['session']->set('user', $user );
        $app['session']->set('auth',true);

        return $app->redirect('projeto');
    }
    else {
        $data = array(
            'error' => TRUE // error login
        );
    }

    return $app['twig']->render('login.html', $data);
});