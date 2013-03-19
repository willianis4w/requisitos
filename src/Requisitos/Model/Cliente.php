<?php

namespace Requisitos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cliente")
 */
class Cliente
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200)
     *
     * @var string
     */
    private $nome;

    /**
     * @ORM\Column(name="id_usuario")
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="id", onDelete="CASCADE")
     * @var int
     */
    private $id_usuario;

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


    // id_usuario
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }
    
    public function setIdUsuario($id_usuario)
    {
        return $this->id_usuario = (int) $id_usuario;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}