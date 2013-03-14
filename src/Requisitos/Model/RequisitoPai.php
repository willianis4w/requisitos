<?php

namespace Requisitos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="requisito_pai")
 */
class RequisitoPai
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="requisito_pai")
     * @ORM\ManyToOne(targetEntity="Requisito")
     * @ORM\JoinColumn(name="id")
     * @var int
     */
    private $requisito_pai;

    /**
     * @ORM\Column(name="requisito_filho")
     * @ORM\ManyToOne(targetEntity="Requisito")
     * @ORM\JoinColumn(name="id")
     * @var int
     */
    private $requisito_filho;

    public function __construct()
    {
       
    }

    // id
    public function getId()
    {
        return $this->id;
    }

    // requisito_pai
    public function getRequisitoPai()
    {
        return $this->requisito_pai;
    }

    // requisito_filho
    public function getRequisitoFilho()
    {
        return $this->requisito_filho;
    }

    // setter
    public function __set($name, $value)
    {
        $this->$name = $value;    
    }

}