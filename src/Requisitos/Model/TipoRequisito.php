<?php

namespace Requisitos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tipo_requisito")
 */
class TipoRequisito
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
     * @var string
     */
    private $descricao;

    public function __construct()
    {
       
    }

    // id
    public function getId()
    {
        return $this->id;
    }

    // nome
    public function getNome()
    {
        return $this->nome;
    }

    // descricao
    public function getDescricao()
    {
        return $this->descricao;
    }

    // setter
    public function __set($name, $value)
    {
        $this->$name = $value;    
    }

}