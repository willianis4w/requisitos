<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/****
    Perfil
****/

// editar - GET
$app->get('/perfil/{id}', function ($id) use ($app,$entityManager) {

    $usuario = $entityManager->find('Requisitos\Model\Usuario', $id);

    $data = array(
        'usuario' => $usuario,
        'result' => ''
    );

    return $app['twig']->render('perfil.html', $data);
});

// editar - POST
$app->post('/perfil/{id}', function ($id,Request $request) use ($app,$entityManager) {

    $usuario = $entityManager->find('Requisitos\Model\Usuario', $id);

    $usuario->setNome($request->get('nome'));
    $usuario->setEmail($request->get('email'));

    $senha = $request->get('senha');
    if( !empty( $senha ) )
         $usuario->setSenha( md5($senha) );

    $entityManager->merge($usuario);
    $entityManager->flush();

    // atualiza as info
    $data = array(
        'usuario' => $usuario,
        'result' => 'success'
    );
    return $app['twig']->render('perfil.html', $data);
});