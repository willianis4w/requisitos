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
$app->get('/login', function () use ($app) {
    $data = array(
        'error' => '' // error login
    );
    return $app['twig']->render('login.html', $data);
});

// login - POST
$app->post('/login', function (Request $request) use ($app,$entityManager) {
    // valida o login do usuario
    $email = $request->get('email');
    $senha = md5( $request->get('senha') );

    $dados = array(
        'email' => $email,
        'senha' => $senha
    );
    
    $usuario = $entityManager->getRepository('Requisitos\Model\Usuario')->findBy( $dados );

    if( !empty($usuario) ) {
        // registra session
        echo $usuario[0]->getEmail();

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

$app->match('/logout', function() use ($app) {
    $app['session']->set('auth',false);
    $data = array(
        'error' => FALSE // error login
    );

    // redireciona login
    return $app['twig']->render('login.html', $data);
});