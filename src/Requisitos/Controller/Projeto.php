<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/****
    Projeto
****/

// get
$app->get('/projeto', function () use ($app,$entityManager) {

    $user = $app['session']->get('user');

    $qb = $entityManager->createQueryBuilder();

    /* arrumar o codigo abaixo pois está muito feio, tem um em baixo coisa linda */

    // abertos
    $qb->add('select', 'p')
       ->add('from', 'Requisitos\Model\Projeto p')
       ->where('p.data_final IS NULL AND p.id_usuario = ?2')
       ->add('orderBy', 'p.data_inicio DESC')
       ->setParameter(2, $user['id']);

    $projetos_abertos = $qb->getQuery()->getResult();

    foreach ($projetos_abertos as $key => $projeto) {
        $projetos_abertos[$key]->inicio = $projeto->getDataInicio();

        $cliente = $entityManager->find('Requisitos\Model\Cliente', $projeto->getIdCliente());
        $projetos_abertos[$key]->cliente = $cliente->getNome();
    }

    // fechados
    $qb->add('select', 'p')
       ->add('from', 'Requisitos\Model\Projeto p')
       ->where('p.data_final IS NOT NULL AND p.id_usuario = ?2')
       ->add('orderBy', 'p.data_final DESC')
       ->setParameter(2, $user['id']);

    $projetos_fechados = $qb->getQuery()->getResult();

    foreach ($projetos_fechados as $key => $projeto) {
        $projetos_fechados[$key]->inicio    = $projeto->getDataInicio();
        $projetos_fechados[$key]->final     = $projeto->getDataFinal();

        $cliente = $entityManager->find('Requisitos\Model\Cliente', $projeto->getIdCliente());
        $projetos_fechados[$key]->cliente = $cliente->getNome();
    }
    
    $data = array(
        'projetos_abertos'  => $projetos_abertos,
        'projetos_fechados' => $projetos_fechados,
		'result'            => ''
	);
    return $app['twig']->render('projeto.html', $data);
});

    
    // adicionar - GET
    $app->get('/projeto-adicionar', function () use ($app,$entityManager) {

        $clientes = $entityManager->getRepository('Requisitos\Model\Cliente')->findAll();

        $data = array(
            'clientes' => $clientes
        );

        return $app['twig']->render('projeto-adicionar.html',$data);
    });


    // adicionar - POST
    $app->post('/projeto-adicionar', function (Request $request) use ($app,$entityManager) {
        
        $user = $app['session']->get('user');
        
        // adicionar projeto
        $nome           = $request->get('nome');
        $descricao      = $request->get('descricao');
        $id_cliente     = $request->get('id_cliente');
        $data_inicio    = date('Y-m-d');

        $conn = $entityManager->getConnection();
        
        $conn->insert('projeto', array(
            'nome'      => $nome,
            'descricao' => $descricao,
            'data_inicio' => $data_inicio,
            'id_usuario' => $user['id'],
            'id_cliente'=> $id_cliente
        ));

        // pega o id inserido e
        $id = $conn->lastInsertId();
        
        // redireciona para página do projeto adicionado
        return $app->redirect('projeto-editar/'.$id);
    });


    // editar - GET
    $app->get('/projeto-editar/{id}', function ($id) use ($app,$entityManager) {

        $projeto = $entityManager->find('Requisitos\Model\Projeto',$id);
        $projeto->cliente = $projeto->getIdCliente();

        // requisitos
        $qb = $entityManager->createQueryBuilder();

        // abertos
        $qb->add('select', 'r')
           ->add('from', 'Requisitos\Model\Requisito r')
           ->where('r.data_final IS NULL AND r.id_projeto = ?1')
           ->add('orderBy', 'r.data_inicio DESC')
           ->setParameter(1, $id);

        $requisitos_abertos = $qb->getQuery()->getResult();

        // fechados
         $qb->add('select', 'r')
           ->add('from', 'Requisitos\Model\Requisito r')
           ->where('r.data_final IS NOT NULL AND r.id_projeto = ?1')
           ->add('orderBy', 'r.data_inicio DESC')
           ->setParameter(1, $id);

        $requisitos_fechados = $qb->getQuery()->getResult();

        // clientes
        $clientes = $entityManager->getRepository('Requisitos\Model\Cliente')->findAll();

        $data = array(
            'projeto'               => $projeto,
            'clientes'              => $clientes,
            'requisitos_abertos'    => $requisitos_abertos,
            'requisitos_fechados'   => $requisitos_fechados,
            'result'                => ''
        );

        // projeto já finalizados não é possível alterar
        $data['readonly'] = ( $projeto->getDataFinal() !== null ? true : false);

        return $app['twig']->render('projeto-editar.html', $data);
    });

    // editar - POST
    $app->post('/projeto-editar/{id}', function ($id,Request $request) use ($app,$entityManager) {

        // edição
        $nome           = $request->get('nome');
        $descricao      = $request->get('descricao');
        $id_cliente     = $request->get('id_cliente');

        $projeto = $entityManager->find('Requisitos\Model\Projeto',$id);

        $projeto->setNome( $nome );
        $projeto->setDescricao( $descricao );
        $projeto->setIdCliente( $id_cliente );

        $entityManager->merge($projeto);
        $entityManager->flush();
        // fim edição

        $projeto->cliente = $projeto->getIdCliente();

        $clientes = $entityManager->getRepository('Requisitos\Model\Cliente')->findAll();

        $data = array(
            'projeto'   => $projeto,
            'clientes'  => $clientes,
            'result'    => 'success',
            'readonly'  => FALSE
        );
        return $app['twig']->render('projeto-editar.html', $data);
    });


    // reabrir - GET
    $app->get('/projeto-reabrir/{id}', function ($id) use ($app,$entityManager) {
       
        $projeto = $entityManager->find('Requisitos\Model\Projeto', $id);

        if( !is_null($projeto) ) {
            $projeto->setDataFinal( NULL );

            $entityManager->merge($projeto);
            $entityManager->flush();
        }

        // redireciona para página de projetos
        return $app->redirect('../projeto');
    });

    // finalizar - GET
    $app->get('/projeto-finalizar/{id}', function ($id) use ($app,$entityManager) {
       
        $projeto = $entityManager->find('Requisitos\Model\Projeto', $id);

        if( !is_null($projeto) ) {
            $projeto->setDataFinal( date('Y-m-d') );

            $entityManager->merge($projeto);
            $entityManager->flush();
        }

        // redireciona para página de projetos
        return $app->redirect('../projeto');
    });
    

    // deletar - GET
    $app->get('/projeto-deletar/{id}', function ($id) use ($app,$entityManager) {
        
        $projeto = $entityManager->find('Requisitos\Model\Projeto', $id);

        if( !is_null($projeto) ) {
            $entityManager->remove($projeto);
            $entityManager->flush();
        }

        // redireciona para página de projetos
        return $app->redirect('../projeto');

    });