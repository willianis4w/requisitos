<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/****
    Cliente
****/

// get
$app->get('/cliente', function () use ($app,$entityManager) {

    $user = $app['session']->get('user');

    $clientes = $entityManager->getRepository('Requisitos\Model\Cliente');

    $clientes = $clientes->findBy( array('id_usuario' => $user['id']) );

    $data = array(
        'clientes' => $clientes
    );
    return $app['twig']->render('cliente.html', $data);
});


// adicionar - GET
$app->get('/cliente-adicionar', function () use ($app) {

    return $app['twig']->render('cliente-adicionar.html');
});


// adicionar - POST
$app->post('/cliente-adicionar', function (Request $request) use ($app,$entityManager) {

    $user = $app['session']->get('user');
    
    $nome = $request->get('nome');

    $conn = $entityManager->getConnection();
        
    $conn->insert('cliente', array(
        'nome'      => $nome,
        'id_usuario' => $user['id']
    ));

    // pega o id inserido e
    $id = $conn->lastInsertId();
    
    // redireciona para página do cliente adicionado
    return $app->redirect('cliente-editar/'.$id);
});


// editar - GET
$app->get('/cliente-editar/{id}', function ($id) use ($app,$entityManager) {

    $cliente = $entityManager->find('Requisitos\Model\Cliente',$id);
    
    $contatos = $entityManager->getRepository('Requisitos\Model\Contato')->findBy( array('id_cliente'=> $id) );

    $data = array(
        'cliente' => $cliente,
        'contatos' => $contatos,
        'result' => ''
    );

    return $app['twig']->render('cliente-editar.html', $data);
});


// editar - POST
$app->post('/cliente-editar/{id}', function ($id,Request $request) use ($app,$entityManager) {

    // edita o cliente
    $cliente = $entityManager->find('Requisitos\Model\Cliente',$id);

    $cliente->setNome( $request->get('nome') );

    $entityManager->merge($cliente);
    $entityManager->flush();

    $contatos = $entityManager->getRepository('Requisitos\Model\Contato')->findBy( array('id_cliente'=> $id) );

    $data = array(
        'cliente' => $cliente,
        'contatos' => $contatos,
        'result' => 'success'
    );

    return $app['twig']->render('cliente-editar.html', $data);
});


// deletar
$app->get('/cliente-deletar/{id}', function ($id) use ($app,$entityManager) {
    
    $cliente = $entityManager->find('Requisitos\Model\Cliente', $id);

    if( !is_null($cliente) ) {
        $entityManager->remove($cliente);
        $entityManager->flush();
    }
    
    // redireciona para o cliente novamente
    return $app->redirect('../cliente');
});