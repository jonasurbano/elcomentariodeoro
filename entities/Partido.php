<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="PartidoRepositorio")
 * @Table(name="partidos")
 */
class Partido {

    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     * */
    private $id;

    /**
     * @Column(type="string")
     * @var string
     * */
    private $club1;

    /**
     * @Column(type="string")
     * @var string
     * */
    private $club2;

    /**
     * @Column(type="string")
     * @var string
     * */
    private $resultado;

    /**
     * @OneToMany(targetEntity="Comentario", mappedBy="partido")
     * @var Comentario[]
     * */
    private $comentarios = null;

    /**
     * @ManyToOne(targetEntity="Jornada", inversedBy="partidos")
     * @var Jornada
     * */
    private $jornada;

    function __construct() {
        $this->comentarios = new ArrayCollection();
    }

    public function setJornada($jornada) {
        $jornada->partidoAsignado($this);
        $this->jornada = $jornada;
    }

    public function comentarioAsignado($comentario) {
        $this->comentarios[] = $comentario;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getClub1() {
        return $this->club1;
    }

    public function setClub1($club) {
        $this->club1 = $club;
    }

    public function getClub2() {
        return $this->club2;
    }

    public function setClub2($club) {
        $this->club2 = $club;
    }

    public function getJornada() {
        return $this->jornada;
    }

    public function getComentarios() {
        return $this->comentarios;
    }

    public function setComentarios($comentarios) {
        $this->comentarios = $partidos;
    }

    public function getResultado() {
        return $this->resultado;
    }

    public function setResultado($resultado) {
        if ($resultado == '1' || $resultado == 'x' || $resultado == '2')
            $this->resultado = $resultado;
        if ($resultado == 'X') $this->resultado = 'x';
    }

    /**
     * Determina si el pronóstico equivale al resultado.
     * @param string $pronostico '1' | '2' | 'x' | 'X'.
     * @return boolean
     */
    public function pronosticoAcertado($pronostico) {
        if (!$pronostico) return false;
        if ($pronostico != '1' && $pronostico != 'x'
            && $pronostico != '2' && $pronostico != 'X')
            return false;
        return $this->resultado == $pronostico;
    }
}

?>
