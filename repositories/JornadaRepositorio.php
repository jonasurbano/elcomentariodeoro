<?php

use Doctrine\ORM\EntityRepository;

class JornadaRepositorio extends EntityRepository {


    /**
     * Devuelve el número de jornada teniendo en cuenta
     * que la fecha es anterior a la fecha de resultados.
     * @return int
     */
    function getNumJornada() {
        $now = new DateTime();
        $fecha = "'" . $now->format("Y-m-d H:i:s") . "'";
        $dql = "SELECT j.id FROM Jornada j
            WHERE" . $fecha . "< j.fechaResultados
            ORDER BY j.id ASC";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setMaxResults(1);

        $result = $query->getScalarResult();
        $r = reset($result);
        if (!isset($r) || sizeof($r) == 0) return NULL;
        return $r['id'];
    }

    /**
     * Devuelve el número de jornada teniendo en cuenta
     * que la fecha es anterior a la fecha de resultados.
     * @return int
     */
    public function getJornada() {
        $now = new DateTime();
        $fecha = "'" . $now->format("Y-m-d H:i:s") . "'";
        $dql = "SELECT j FROM Jornada j
            WHERE" . $fecha . " < j.fechaResultados
            ORDER BY j.id ASC";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setMaxResults(1);

        $result = $query->getResult();
        $r = reset($result);
        if (!isset($r) || sizeof($r) == 0) return NULL;
        return $r;
    }

    public function numUltimaJornada() {
        $now = new DateTime();
        $dql = "SELECT j.id FROM Jornada j
            ORDER BY j.id DESC";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setMaxResults(1);

        $result = $query->getScalarResult();
        $r = reset($result);
        if (!isset($r) || sizeof($r) == 0) return NULL;
        return $r['id'];
    }

}

?>
