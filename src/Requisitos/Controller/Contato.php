<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/****
    Contato
****/


// adicionar - GET
$app->get('/contato-adicionar/{id}', function ($id) use ($app,$entityManager) {


    $cliente = $entityManager->find('Requisitos\Model\Cliente',$id);

    $data = array(
        'cliente' => $cliente
    );

    return $app['twig']->render('contato-adicionar.html',$data);
});


// adicionar - post
$app->post('/contato-adicionar/{id}', function ($id,Request $request) use ($app,$entityManager) {
    
    $nome       = $request->get('nome');
    $email      = $request->get('email');
    $funcao     = $request->get('funcao');
    $observacao = $request->get('observacao');

    $conn = $entityManager->getConnection();
        
    $conn->insert('cliente_contato', array(
        'nome'        => $nome,
        'email'       => $email,
        'funcao'      => $funcao,
        'observacao'  => $observacao,
        'id_cliente'  => $id
    ));

    // redireciona para o cliente novamente
    return $app->redirect('../cliente-editar/'.$id);
});


// editar - GET
$app->get('/contato-editar/{id}', function ($id) use ($app,$entityManager) {

    $contato = $entityManager->find('Requisitos\Model\Contato',$id);

    $cliente = $entityManager->find('Requisitos\Model\Cliente', $contato->getIdCliente());

    $data = array(
        'contato' => $contato,
        'cliente' => $cliente
    );

    return $app['twig']->render('contato-editar.html', $data);
});


// editar - POST
$app->post('/contato-editar/{id}', function ($id,Request $request) use ($app,$entityManager) {

    $nome       = $request->get('nome');
    $email      = $request->get('email');
    $funcao     = $request->get('funcao');
    $observacao = $request->get('observacao');

    // edita o contato
    $contato = $entityManager->find('Requisitos\Model\Contato',$id);

    $contato->setNome( $nome );
    $contato->setEmail( $email );
    $contato->setFuncao( $funcao );
    $contato->setObservacao( $observacao );

    $entityManager->merge($contato);
    $entityManager->flush();

    // redireciona para o cliente novamente
    return $app->redirect('../cliente-editar/'.$contato->getIdCliente());
});


// deletar
$app->get('/contato-deletar/{id}', function ($id) use ($app,$entityManager) {

    $contato = $entityManager->find('Requisitos\Model\Contato',$id);

    if( !is_null($contato) ) {
        $entityManager->remove($contato);
        $entityManager->flush();
    }
    
    // redireciona para o cliente novamente
    return $app->redirect('../cliente-editar/'.$contato->getIdCliente());
});