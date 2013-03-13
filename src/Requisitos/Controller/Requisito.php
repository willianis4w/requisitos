<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/****
    Requisito
****/

// adicionar - GET
$app->get('/requisito-adicionar', function () use ($app) {
    return $app['twig']->render('requisito-adicionar.html');
});

// adicionar - POST
$app->post('/requisito-adicionar', function () use ($app) {
    // adicionar requisito
    echo 'Post - adicionar requisito';exit;

    // redireciona para página do projeto
    return $app->redirect('projeto-editar/1');
});

// editar - GET
$app->get('/requisito-editar/{id}', function () use ($app) {
    $data = array();
    return $app['twig']->render('requisito-editar.html', $data);
});

// editar - POST
$app->post('/requisito-editar/{id}', function () use ($app) {
    // editar requisito
    echo 'Post - editar requisito';

    // redireciona para página do projeto
    return $app->redirect('../projeto-editar/1');
});

// finalizar - GET
$app->get('/requisito-finalizar/{id}', function () use ($app) {
    // finaliza o requisito
    echo "GET - finalizar requisito"; exit;

    // redireciona para página do projeto
    return $app->redirect('../projeto-editar/1');
});

// deletar - GET
$app->get('/requisito-deletar/{id}', function () use ($app) {
    // deleta o contato
    echo "GET - deletar requisito"; exit;

    // redireciona para página do projeto
    return $app->redirect('../projeto-editar/1');
});