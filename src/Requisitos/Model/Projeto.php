<?php

namespace Requisitos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projeto")
 */
class Projeto
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $nome;

    /**
     * @ORM\Column(type="text")
     *
     * @var text 
     */
    private $descricao;

    /**
     * @ORM\Column(type="date")
     *
     * @var date
     */
    private $data_inicio;

    /**
     * @ORM\Column(type="date")
     *
     * @var date
     */
    private $data_final;

    /**
     * @ORM\Column(name="id_usuario")
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="id", onDelete="CASCADE")
     * @var int
     */
    private $id_usuario;

    /**
     * @ORM\Column(name="id_cliente")
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumn(name="id", onDelete="CASCADE")
     * @var int
     */
    private $id_cliente;

    public function __construct()
    {
       
    }

    // nome
    public function getNome()
    {
        return $this->nome;
    }
    
    public function setNome($nome)
    {
        return $this->nome = filter_var($nome, FILTER_SANITIZE_STRING);
    }

    // descricao
    public function getDescricao()
    {
        return $this->descricao;
    }
    
    public function setDescricao($descricao)
    {
        return $this->descricao = filter_var($descricao, FILTER_SANITIZE_STRING);
    }

    // data inicio
    public function getDataInicio()
    {
        return ( $this->data_inicio !== null ? date_format($this->data_inicio,'d/m/Y') : null );
    }
    
    public function setDataInicio($data_inicio)
    {
        if($data_inicio != null)
            $this->data_inicio = \DateTime::createFromFormat('Y-m-d', $data_inicio);   
        else
            $this->data_inicio = null;   
    }

    // data final
    public function getDataFinal()
    {
        return ( $this->data_final !== null ? date_format($this->data_final,'d/m/Y') : null );
    }
    
    public function setDataFinal($data_final)
    {
        if($data_final != null)
            $this->data_final = \DateTime::createFromFormat('Y-m-d', $data_final);   
        else
            $this->data_final = null;
    }

    // id_cliente
    public function getIdCliente()
    {
        return $this->id_cliente;
    }
    
    public function setIdCliente($id_cliente)
    {
        return $this->id_cliente = $id_cliente;
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}