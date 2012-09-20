<?php

class PronosticoRepositorio extends \Doctrine\ORM\EntityRepository {

    public function getPronostico($idPartido,$idJugador) {
        $dql = "SELECT p FROM Pronostico p
            WHERE p.partido = ?1 AND p.jugador = ?2";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->
            setParameter(2, (int)$idJugador)->
            setMaxResults(1);

        $result = $query->getResult();
        if (!isset($result) || sizeof($result) == 0) return NULL;
        return reset($result);
    }

    /**
     * Devuelve el resultado esperado por el jugador.
     * @param int $idPartido
     * @param int $idJugador
     * @return string '1' | 'x' | '2'
     */
    public function getResultado($idPartido,$idJugador) {
        $dql = "SELECT p.resultado FROM Pronostico p
            WHERE p.partido = ?1 AND p.jugador = ?2";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->
            setParameter(2, (int)$idJugador)->
            setMaxResults(1);

        $result = $query->getScalarResult();
        $r = reset($result);
        if (!isset($r) || sizeof($r) == 0) return NULL;
        return $r['resultado'];
    }

    public function getPronosticos($idPartido) {
        $dql = "SELECT p FROM Pronostico p
            WHERE p.partido = ?1";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido);

        $result = $query->getResult();
        if (!isset($result) || sizeof($result) == 0) return NULL;
        return $result;
    }
}

?>
