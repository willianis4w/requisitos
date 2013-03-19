<?php

namespace Requisitos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cliente_contato")
 */
class Contato
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @var string
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @var string
     */
    private $funcao;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $observacao;

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

    // email
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        return $this->email = filter_var($email, FILTER_SANITIZE_STRING);
    }

    // funcao
    public function getFuncao()
    {
        return $this->funcao;
    }
    
    public function setFuncao($funcao)
    {
        return $this->funcao = filter_var($funcao, FILTER_SANITIZE_STRING);
    }

    // observacao
    public function getObservacao()
    {
        return $this->observacao;
    }
    
    public function setObservacao($observacao)
    {
        return $this->observacao = filter_var($observacao, FILTER_SANITIZE_STRING);
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