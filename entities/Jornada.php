<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="JornadaRepositorio") 
 * @Table(name="jornadas")
*/
class Jornada
{
    
    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @var int
    **/
    private $id;
    /**
     * @Column(type="datetime")
     */
    private $fechaTope;
    /**
     * @Column(type="datetime")
     * Día en que aparecen los resultados de la jornada, es decir,
     * el día después al último partido. No de esta jornada.
     */
    private $fechaResultados;
    /** 
     * @OneToMany(targetEntity="Partido", mappedBy="jornada")
     * @var Partido[]
    **/
    private $partidos = null;

    function __construct() {
    	$this->partidos = new ArrayCollection();
    }

    public function partidoAsignado($partido)
    {
        $this->partidos[] = $partido;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getFechaTope() {
        return $this->fechaTope;
    }
    
    public function setFechaTope($fechaTope) {
        $this->fechaTope = $fechaTope;
    }
    
    public function getFechaResultados() {
        return $this->fechaResultados;
    }
    
    public function setFechaResultados($fechaResultados) {
        $this->fechaResultados = $fechaResultados;
    }
    
    public function getPartidos() {
        return $this->partidos;
    }
    
    public function setPartidos($partidos) {
        $this->partidos = $partidos;
    }
}

?>