<?php

/**
 * @Entity @Entity(repositoryClass="PronosticoRepositorio") 
 * @Table(name="pronosticos")
*/
class Pronostico
{
        /**
        * @Id @Column(type="integer") @GeneratedValue 
        * @var int
        */
        private $id;
	/** 
	 * @Column(type="string")
	 * @var string
	**/
	private $resultado;
	/**
 	 * @Column(type="datetime")
	 */
	private $fecha;
 	/**
 	 * @OneToOne(targetEntity="Partido")
        * @var Partido
 	*/
	private $partido;
        /** 
        * @ManyToOne(targetEntity="Jugador", inversedBy="pronosticos") 
        * @var Jugador
        **/
	private $jugador;

        public function getId() {
            return $this->id;
        }
        
        public function setId($id) {
            $this->id = $id;
        }
        
        public function getResultado() {
            return $this->resultado;
        }
        
        public function setResultado($resultado) {
            $this->resultado = $resultado;
            $this->fecha = new DateTime();
        }
        
        public function getFecha() {
            return $this->fecha;
        }
        
        public function setFecha($fecha) {
            $this->fecha = $fecha;
        }
        
        public function getPartido() {
            return $this->partido;
        }
        
        public function setPartido($partido) {
            $this->partido = $partido;
        }
        
        public function getJugador() {
            return $this->jugador;
        }
        
	public function setJugador($jugador)
	{
            $jugador->pronosticoAsignado($this);
            $this->jugador = $jugador;
	}

        public function __construct($jugador,$partido,$resultado) {
            $this->setJugador($jugador);
            $this->partido = $partido;
            $this->resultado = $resultado;
            $this->fecha = new DateTime();
        }

}

?>