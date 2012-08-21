<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="JugadorRepositorio")
 * @Table(name="jugadores")
*/
class Jugador
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     * */
    private $id;

    /**
     * @Column(type="integer")
     * @var int
     * */
    private $sumaPronosticos;

    /**
     * @Column(type="integer")
     * @var int
     * */
    private $sumaComentarios;

    /**
     * @Column(type="datetime")
     * */
    private $ultimaConexion;

    /**
     * @Column(type="string")
     * @var string
     * */
    private $idFacebook;

    /**
     * @Column(type="string")
     * @var string
     */
    private $sigueClub;

    /**
     * @Column(type="integer")
     * @var int
     */
    private $sumaPuntosSemanal;

    /**
     * @OneToMany(targetEntity="Pronostico", mappedBy="jugador")
     * @var Pronostico[]
     * */
    private $pronosticos = null;

    /**
     * @OneToMany(targetEntity="Comentario", mappedBy="escritor")
     * @var Comentario[]
     * */
    private $comentarios = null;

   /**
     * @ManyToMany(targetEntity="Jornada")
     * @JoinTable(name="jornadaspublicas",
     * joinColumns={@JoinColumn(name="jugador_id", referencedColumnName="id")},
     * inverseJoinColumns={@JoinColumn(name="jornada_id",referencedColumnName="id")})
     * @var Comentario[]
     */
    private $jornadasPublicas = null;

    /**
     * @ManyToMany(targetEntity="Comentario")
     * @JoinTable(name="comentariosgustados",
     * joinColumns={@JoinColumn(name="jugador_id", referencedColumnName="id")},
     * inverseJoinColumns={@JoinColumn(name="comentario_id",referencedColumnName="id")})
     * @var Comentario[]
     */
    private $comentariosGustados = null;

    /**
     * @ManyToMany(targetEntity="Comentario")
     * @JoinTable(name="comentariosnogustados",
     * joinColumns={@JoinColumn(name="jugador_id", referencedColumnName="id")},
     * inverseJoinColumns={@JoinColumn(name="comentario_id",referencedColumnName="id")})     )
     * @var Comentario[]
     */
    private $comentariosNoGustados = null;

    function __construct($idFb)
    {
        $this->setIdFacebook($idFb);
        $this->setSumaComentarios(0);
        $this->setSumaPronosticos(0);
        $this->setSumaPuntosSemanal(0);
        $this->setUltimaConexion(new DateTime());

        $this->pronosticos = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->comentariosGustados = new ArrayCollection();
        $this->comentariosNoGustados = new ArrayCollection();
        $this->jornadasPublicas = new ArrayCollection();
    }

    public function comentarioAsignado($comentario)
    {
        $this->comentarios[] = $comentario;
    }

    public function pronosticoAsignado($pronostico){
        $this->pronosticos[] = $pronostico;
    }

    public function isJornadaPublica($idJornada) {
        if (!is_numeric($idJornada)) return false;
        foreach ($this->jornadasPublicas as $jornada) {
            if ($jornada->getId() == $idJornada)
                return true;
        }
        return false;
    }

    public function comentarioGustado($comentario) {
        $this->comentariosGustados->add($comentario);
        $comentario->masUnVoto();
    }

    public function comentarioNoGustado($comentario) {
        $this->comentariosNoGustados->add($comentario);
        $comentario->menosUnVoto();
    }

    public function jornadaPublicaAsignada($jornada) {
        $this->jornadasPublicas->add($jornada);
    }


    public function eliminarComentarioNoGustado($comentario) {
        $this->comentariosNoGustados->removeElement($comentario);
        $comentario->masUnVoto();
    }

    public function eliminarComentarioGustado($comentario) {
        $this->comentariosGustados->removeElement($comentario);
        $comentario->menosUnVoto();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSumaPronosticos() {
        return $this->sumaPronosticos;
    }

    public function setSumaPronosticos($sumaPronosticos) {
        $this->sumaPronosticos = $sumaPronosticos;
    }

    public function getSumaComentarios() {
        return $this->sumaComentarios;
    }

    public function setSumaComentarios($sumaComentarios) {
        $this->sumaComentarios = $sumaComentarios;
    }

    public function getUltimaConexion() {
        return $this->ultimaConexion;
    }

    public function setUltimaConexion($ultimaConexion) {
        $this->ultimaConexion = $ultimaConexion;
    }

    public function getIdFacebook() {
        return $this->idFacebook;
    }

    public function setIdFacebook($idFacebook) {
        $this->idFacebook = $idFacebook;
    }

    public function getSigueClub() {
        return $this->sigueClub;
    }

    public function setSigueClub($club) {
        $this->sigueClub = $club;
    }

    public function tieneClub() {
        return $this->sigueClub != null;
    }

    public function getSumaPuntosSemanal() {
        return $this->sumaPuntosSemanal;
    }

    public function setSumaPuntosSemanal($sumaPuntosSemanal) {
        $this->sumaPuntosSemanal = $sumaPuntosSemanal;
    }

    /**
     *
     * @param Comentario $comentario
     * @return boolean
     */
    public function gustaComentarioAJugador($comentario) {
        return $this->comentariosGustados->contains($comentario);
    }

    public function noGustaComentarioAJugador($comentario) {
        return $this->comentariosNoGustados->contains($comentario);
    }

    public function masUnVotoComentario() {
        $this->sumaComentarios++;
    }

    public function menosUnVotoComentario() {
        $this->sumaComentarios--;
    }

    public function mas3Puntos() {
        $this->sumaPronosticos += 3;
    }
}

?>