<?php

namespace Requisitos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 */
class Usuario
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
     * @ORM\Column(type="string", length=32)
     *
     * @var string
     */
    private $senha;

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

    // senha
    public function getSenha()
    {
        return $this->senha;
    }
    
    public function setSenha($senha)
    {
        return $this->senha = filter_var($senha, FILTER_SANITIZE_STRING);
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}