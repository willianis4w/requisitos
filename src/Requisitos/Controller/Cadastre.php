<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/****
    Login
****/

// cadastre - POST
$app->post('/cadastre', function (Request $request) use ($app,$entityManager) {

    $nome   = $request->get('nome');
    $email  = $request->get('email');
    $senha  = md5( $request->get('senha') );

    $conn = $entityManager->getConnection();
        
    $conn->insert('usuario', array(
        'nome'  => $nome,
        'email' => $email,
        'senha' => $senha
    ));

    // pega o id inserido
    $id = $conn->lastInsertId();

    // registra session
    $user = array(
        'id'    => $id,
        'nome'  => $nome,
        'email' => $email
    );

    $app['session']->set('user', $user );
    $app['session']->set('auth',true);

    return $app->redirect('projeto');
    
});