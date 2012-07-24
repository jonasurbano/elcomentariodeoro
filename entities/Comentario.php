<?php

/**
 * @Entity(repositoryClass="ComentarioRepositorio") 
 * @Table(name="comentarios")
*/
class Comentario {
    
    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @var int
    **/
    private $id;
    /**
     * @Column(type="string") 
     * @var string
    **/	
    private $comentario;
    /** 
     * @Column(type="integer") 
     * @var int
    **/
    private $votos;
    /** 
     * @Column(type="datetime") 
     **/
    private $fecha;
    /** 
     * @ManyToOne(targetEntity="Jugador", inversedBy="comentarios") 
     * @var Jugador
    **/
    private $escritor;
    /** 
     * @ManyToOne(targetEntity="Partido", inversedBy="comentarios") 
     * @var Partido
    **/
    private $partido;

    public function __construct($comentario,$escritor,$partido) {
        $this->comentario = $comentario;
        $this->setEscritor($escritor);
        $this->setPartido($partido);
        $this->fecha = new DateTime();
        $this->votos = 0;
    }
    
    public function setEscritor($escritor)
    {
        $escritor->comentarioAsignado($this);
        $this->escritor = $escritor;
    }

    public function setPartido($partido) {
        $partido->comentarioAsignado($this);
        $this->partido = $partido;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getComentario() {
        return $this->comentario;
    }
    
    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function getVotos() {
        return $this->votos;
    }
    
    public function setVotos($votos) {
        $this->votos = $votos;
    }
    
    public function getFecha() {
        return $this->fecha;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
    
    public function getEscritor() {
        return $this->escritor;
    }

    public function getPartido() {
        return $this->partido;
    }

    public function masUnVoto() {
        $this->votos++;
        $this->escritor->masUnVotoComentario();
    }

    public function menosUnVoto() {
        $this->votos--;
        $this->escritor->menosUnVotoComentario();
    }
    
}

?>