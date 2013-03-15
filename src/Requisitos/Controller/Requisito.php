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
$app->get('/requisito-adicionar/{id}', function ($id) use ($app,$entityManager) {

    $projeto = $entityManager->find('Requisitos\Model\Projeto',$id);
    
    $requisitos = $entityManager->getRepository('Requisitos\Model\Requisito')->findBy( array( 'id_projeto' => $id ) );
    $tipos      = $entityManager->getRepository('Requisitos\Model\TipoRequisito')->findAll();
    $contatos   = $entityManager->getRepository('Requisitos\Model\Contato')->findBy( array( 'id_cliente' => $projeto->getIdCliente() ) );

    $data = array(
        'projeto'    => $projeto,
        'requisitos' => $requisitos,
        'tipos'      => $tipos,
        'contatos'   => $contatos
    );


    return $app['twig']->render('requisito-adicionar.html',$data);
});

// adicionar - POST
$app->post('/requisito-adicionar/{id}', function ($id, Request $request) use ($app,$entityManager) {
   
    $codigo                 = $request->get('codigo');
    $nome                   = $request->get('nome');
    $prioridade             = $request->get('prioridade');
    $estabilidade           = $request->get('estabilidade');
    $id_tipo_requisito      = $request->get('id_tipo_requisito');
    $impacto_arquitetura    = $request->get('impacto_arquitetura');
    $descricao              = $request->get('descricao');
    $data_inicio            = date('Y-m-d');
    $id_cliente_contato     = $request->get('id_cliente_contato');
    $id_projeto             = $id;
    
    $conn = $entityManager->getConnection();
    
    $dados = array(
        'codigo'                => $codigo,
        'nome'                  => $nome,
        'prioridade'            => $prioridade,
        'estabilidade'          => $estabilidade,
        'id_tipo_requisito'     => $id_tipo_requisito,
        'impacto_arquitetura'   => $impacto_arquitetura,
        'descricao'             => $descricao,
        'data_inicio'           => $data_inicio,
        'id_projeto'            => $id_projeto
    );

    if( !empty($id_cliente_contato) )
        $dados['id_cliente_contato'] = $id_cliente_contato;

    $conn->insert('requisito', $dados );

    // pega o id inserido
    $id_requisito = $conn->lastInsertId();

    // insere requisitos pais
    if( $request->get('requisito_pai') !== null ) {
        foreach ( $request->get('requisito_pai') as $requisito_pai ) {
            $conn->insert('requisito_pai', array(
                'requisito_pai'     => $requisito_pai,
                'requisito_filho'   => $id_requisito
            ));
        }
    }

    // redireciona para página do projeto
    return $app->redirect('../projeto-editar/'.$id);
});

// editar - GET
$app->get('/requisito-editar/{id}', function ($id) use ($app,$entityManager) {
    
    $requisito          = $entityManager->find('Requisitos\Model\Requisito',$id);

    $requisitos         = $entityManager->getRepository('Requisitos\Model\Requisito')->findBy( array( 'id_projeto' => $requisito->getIdProjeto() ) );
    $tipos              = $entityManager->getRepository('Requisitos\Model\TipoRequisito')->findAll();
    $requisitos_pais    = $entityManager->getRepository('Requisitos\Model\RequisitoPai')->findBy( array( 'requisito_filho' => $id ) );

    $projeto            = $entityManager->find('Requisitos\Model\Projeto',$requisito->getIdProjeto());

    $cliente            = $entityManager->find('Requisitos\Model\Cliente',$projeto->getIdCliente());
    $contatos           = $entityManager->getRepository('Requisitos\Model\Contato')->findBy( array( 'id_cliente' => $cliente->getId() ) );

    $projeto            = $entityManager->find('Requisitos\Model\Projeto',$requisito->getIdProjeto());

    $data = array(
        'projeto'           => $projeto,
        'requisito'         => $requisito,
        'requisitos'        => $requisitos,
        'requisitos_pais'   => $requisitos_pais,
        'tipos'             => $tipos,
        'contatos'          => $contatos
    );

    // projeto já finalizados não é possível alterar
    $data['readonly'] = ( $requisito->getDataFinal() !== null ? true : false);

    return $app['twig']->render('requisito-editar.html', $data);
});

// editar - POST
$app->post('/requisito-editar/{id}', function ($id,Request $request) use ($app,$entityManager) {
    
    // edição
    $requisito = $entityManager->find('Requisitos\Model\Requisito',$id);

    $requisito->codigo                 = $request->get('codigo');
    $requisito->nome                   = $request->get('nome');
    $requisito->prioridade             = $request->get('prioridade');
    $requisito->estabilidade           = $request->get('estabilidade');
    $requisito->id_tipo_requisito      = $request->get('id_tipo_requisito');
    $requisito->impacto_arquitetura    = $request->get('impacto_arquitetura');
    $requisito->descricao              = $request->get('descricao');
    $id_cliente_contato = $request->get('id_cliente_contato');
    $requisito->id_cliente_contato     = ( !empty( $id_cliente_contato ) ? $id_cliente_contato : NULL );

    $entityManager->merge($requisito);
    $entityManager->flush();
    $entityManager->clear();
    // fim edição

    // remove requisitos pais
    $requisitos_pais    = $entityManager->getRepository('Requisitos\Model\RequisitoPai')->findBy( array( 'requisito_filho' => $id ) );
    foreach ($requisitos_pais as $pai) {
        $req_pai = $entityManager->find('Requisitos\Model\RequisitoPai', $pai->getId() );

        $entityManager->remove($req_pai);
        $entityManager->flush();
        $entityManager->clear();
    }

    // insere requisitos pais
    if( $request->get('requisito_pai') !== null ) {
        $conn = $entityManager->getConnection();
        foreach ( $request->get('requisito_pai') as $requisito_pai ) {
            $conn->insert('requisito_pai', array(
                'requisito_pai'     => $requisito_pai,
                'requisito_filho'   => $id
            ));
        }
    }

    // redireciona para página do projeto
    return $app->redirect('../requisito-editar/'.$id);
});


// reabrir - GET
$app->get('/requisito-reabrir/{id}', function ($id) use ($app,$entityManager) {
   
    $requisito = $entityManager->find('Requisitos\Model\Requisito', $id);

    if( !is_null($requisito) ) {
        $requisito->setDataFinal( NULL );

        $entityManager->merge($requisito);
        $entityManager->flush();
    }

    // redireciona para página de projetos
    return $app->redirect('../requisito-editar/'.$id);
});


// finalizar - GET
$app->get('/requisito-finalizar/{id}', function ($id) use ($app,$entityManager) {
   
    $requisito = $entityManager->find('Requisitos\Model\Requisito', $id);
    $id_projeto = $requisito->getIdProjeto();

    if( !is_null($requisito) ) {
        $requisito->setDataFinal( date('Y-m-d') );

        $entityManager->merge($requisito);
        $entityManager->flush();
    }

    // redireciona para página de projetos
    return $app->redirect('../projeto-editar/'.$id_projeto);
});


// deletar - GET
$app->get('/requisito-deletar/{id}', function ($id) use ($app,$entityManager) {
    
    $requisito = $entityManager->find('Requisitos\Model\Requisito', $id);
    $id_projeto = $requisito->getIdProjeto();

    if( !is_null($requisito) ) {
        $entityManager->remove($requisito);
        $entityManager->flush();
    }

    // redireciona para página de projetos
    return $app->redirect('../projeto-editar/'.$id_projeto);

});