<?php

namespace Requisitos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="requisito")
 */
class Requisito
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @var integer
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $nome;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('baixa', 'media', 'alta')") 
     * 
     * @var string
     */
    private $prioridade;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('baixa', 'media', 'alta')") 
     * 
     * @var string
     */
    private $estabilidade;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('baixa', 'media', 'alta')") 
     * 
     * @var string
     */
    private $impacto_arquitetura;

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
     * @ORM\Column(name="id_tipo_requisito")
     * @ORM\ManyToOne(targetEntity="Requisito")
     * @ORM\JoinColumn(name="id")
     * @var int
     */
    private $id_tipo_requisito;

    /**
     * @ORM\Column(name="id_projeto")
     * @ORM\ManyToOne(targetEntity="Projeto")
     * @ORM\JoinColumn(name="id", onDelete="CASCADE")
     * @var int
     */
    private $id_projeto;

    /**
     * @ORM\Column(name="id_cliente_contato")
     * @ORM\ManyToOne(targetEntity="Contato")
     * @ORM\JoinColumn(name="id")
     * @var int
     */
    private $id_cliente_contato;

    
    // methods

    public function __construct()
    {
       
    }

    // getter
    public function __get($name)
    {
        if(property_exists($this, $name)){
            return $this->$name;
        }
    }

    // setter
    public function __set($name, $value)
    {
        $this->$name = $value;    
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

}