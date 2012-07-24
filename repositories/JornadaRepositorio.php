<?php

use Doctrine\ORM\EntityRepository;

class JornadaRepositorio extends EntityRepository {
 
    
    /**
     * Devuelve el número de jornada actual.
     * @return int
     */
    function getNumJornada() {
        $dql = "SELECT j.id FROM Jornada j 
            WHERE CURRENT_DATE() < j.fechaResultados
            ORDER BY j.id ASC";
        $query = $this->getEntityManager()->createQuery($dql)->
            setMaxResults(1);
        
        $result = $query->getScalarResult();
        $r = reset($result);
        if (!isset($r) || sizeof($r) == 0) return NULL;
        return $r['id'];
    }
    
}

?>
