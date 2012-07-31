<?php

class PartidoRepositorio extends Doctrine\ORM\EntityRepository {


    /**
     * Devuelve la lista de partidos
     * @param int $jornada Número de jornada
     * @return Partido
     */
    function getPartidos($jornada) {
        $dql = "SELECT p FROM PARTIDO p
            WHERE p.jornada = ?1";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(1, $jornada);
        return $query->getResult();
    }

    /**
     * Comprueba si hay partidos para la jornada indicada.
     * @param int $idJornada
     * @return boolean
     */
    function hayPartidos($idJornada) {
        $dql = "SELECT p.id FROM PARTIDO p
            WHERE p.jornada = ?1";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(1, $idJornada);
        return sizeof($query->getResult()) > 0;
    }

}

?>
