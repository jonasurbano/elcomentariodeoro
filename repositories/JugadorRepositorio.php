<?php

/**
 * Description of JugadorRepositorio
 *
 * @author hn
 */
class JugadorRepositorio extends Doctrine\ORM\EntityRepository {

    /**
     * Devuelve un objeto Jugador que tiene
     * el id de Facebook $idFacebook.
     * @param string $idFacebook
     * @return Jugador
     * Comprobar isset()
     */
    public function getJugador($idFacebook) {
        $dql = "SELECT j FROM Jugador j
            WHERE j.idFacebook = ?1";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, $idFacebook)->
            setMaxResults(1);

        $result = $query->getResult();
        $r = reset($result);
        if (sizeof($r) == 0) return NULL;
        return $r;
    }

    /**
     * Devuelve el id de Jugador con id de Facebook
     * $idFacebook,
     * @param string $idFacebook
     * @return int.
     * Comprobar si isset().
     */
    public function getIdJugador($idFacebook) {
        $dql = "SELECT j.id FROM Jugador j
            WHERE j.idFacebook = ?1";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, $idFacebook)->
            setMaxResults(1);

        $result = $query->getScalarResult();
        $r = reset($result);

        if (!isset($r))  return NULL;
        return (int)($r['id']);
    }

    public function rankingPronostico($offset) {
        $dql = "SELECT j FROM Jugador j
            ORDER BY j.sumaPronosticos DESC";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setFirstResult($offset)->
            setMaxResults(5);

        return $query->getResult();
    }

    public function posicionRankingPronosticos($idJugador) {
        $dql = "SELECT j.id FROM Jugador j
            ORDER BY j.sumaPronosticos DESC";
        $query = $this->getEntityManager()->
            createQuery($dql)->getScalarResult();

        foreach ($query as $key => $id) {
            if ($id['id'] == $idJugador)
                return $key + 1;
        }
    }

    public function posicionRankingComentarios($idJugador) {
        $dql = "SELECT j.id FROM Jugador j
            ORDER BY j.sumaComentarios DESC";
        $query = $this->getEntityManager()->
            createQuery($dql)->getScalarResult();

        foreach ($query as $key => $id) {
            if ($id['id'] == $idJugador)
                return $key + 1;
        }
    }

    public function hayMasRanking($offset) {
        $dql = "SELECT COUNT(j.id) AS num FROM Jugador j";
        $query = $this->getEntityManager()->
            createQuery($dql)->getResult();

        $r = reset($query);
        return ($offset * 5) < $r['num'];
    }

    public function rankingComentarios($offset) {
        $dql = "SELECT j FROM Jugador j
            ORDER BY j.sumaComentarios DESC";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setFirstResult($offset)->
            setMaxResults(5);

        return $query->getResult();
    }

    public function rankingClubes() {
        $dql = "SELECT j.sigueClub, (j.sumaPronosticos + j.sumaComentarios)
            AS suma FROM Jugador j WHERE j.sigueClub <> ''
            GROUP BY j.sigueClub ORDER BY suma DESC";

        return $this->getEntityManager()->createQuery($dql)->getResult();
    }

}

?>
