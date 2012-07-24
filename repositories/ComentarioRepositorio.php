<?php

use Doctrine\ORM\EntityRepository;


/**
 * Description of ComentarioRepositorio
 *
 * @author hn
 */
class ComentarioRepositorio extends EntityRepository {

    /**
     * Devuelve 3 de los comentarios recientes.
     * @param int $offset.
     * @param int $idPartido.
     * @return Array de objetos Comentario.
     * Comprobar isset() y sizeof().
     */
    public function getComentariosRecientes($offset,$idPartido) {
        if ($offset < 0) return NULL;
        
        $dql = "SELECT c FROM Comentario c 
            WHERE c.partido = ?1
            ORDER BY c.fecha DESC";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->    
            setMaxResults(3)->
            setFirstResult((int)$offset);
        return $query->getResult();
    }
    
    /**
     * Comprueba si hay comentarios posteriores a los
     * últimos obtenidos que se han indicado con $offset.
     * @param int $offset
     * @param int $idPartido
     * @return boolean
     * Comprobar isset().
     */
    public function hayPosterioresComentariosRecientes($offset,$idPartido) {
        if ($offset < 0) return NULL;
        
        $dql = "SELECT c FROM Comentario c
            WHERE c.partido = ?1
            ORDER BY c.fecha DESC";
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter(1, (int)$idPartido)
            ->setFirstResult((int)$offset+3)    
            ->setMaxResults(1)
            ->getArrayResult();

        return sizeof($query) > 0;
    }
    
    /**
     * Devuelve 3 de los comentarios más votados.
     * @param int $offset.
     * @param int $idPartido.
     * @return Array de objetos Comentario.
     * Comprobar isset() y sizeof().
     */
    public function getComentariosMasVotados($offset,$idPartido) {
        if ($offset < 0) return NULL;
        
        $dql = "SELECT c FROM Comentario c 
            WHERE c.partido = ?1
            ORDER BY c.votos DESC";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->    
            setMaxResults(3)->
            setFirstResult((int)$offset);
        return $query->getResult();
    }
    
    /**
     * Comprueba si hay comentarios posteriores a los
     * últimos obtenidos que se han indicado con $offset.
     * @param int $offset
     * @param int $idPartido
     * @return boolean.
     * Comprobar isset().
     */
    public function hayPosterioresComentariosMasVotados($offset,$idPartido) {
        if ($offset < 0) return NULL;
        
        $dql = "SELECT c FROM Comentario c
            WHERE c.partido = ?1
            ORDER BY c.votos DESC";
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter(1, (int)$idPartido)
            ->setFirstResult((int)$offset+3)    
            ->setMaxResults(1)
            ->getArrayResult();

        return sizeof($query) > 0;
    }
    
    /**
     * Devuelve 3 de los comentarios de amigos en Facebook.
     * @param int $offset
     * @param int $idPartido
     * @param string $listaAmigos. Encaja en IN ($listaAmigos).
     * @return Array de objetos Comentario.
     * Comprobar isset() y sizeof().
     */
    public function getComentariosAmigos($offset,$idPartido,$listaAmigos) {
        $dql = "SELECT c FROM Comentario c 
            WHERE c.partido = ?1 AND c.escritor IN (" . 
            $listaAmigos . ") ORDER BY c.votos DESC";
        
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->
            setMaxResults(3)->
            setFirstResult($offset);
        
        return $query->getResult();
    }
    
    /**
     * Comprueba si hay comentarios posteriores a los
     * últimos obtenidos que se han indicado con $offset.
     * @param int $offset
     * @param int $idPartido
     * @return boolean.
     * Comprobar isset().
     */    
    public function hayMasComentariosAmigos($offset,$idPartido,$listaAmigos) {
        $dql = "SELECT c FROM Comentario c 
            WHERE c.partido = ?1 AND c.escritor IN (" . 
            $listaAmigos . ") ORDER BY c.votos DESC";
        
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->
            setMaxResults(3)->
            setFirstResult($offset+3);
        
        return sizeof($query->getResult()) > 0;
    }

    /**
     * Comprueba si se ha escrito un comentario para el partido
     * y el escritor pasados como argumentos.
     * @param int $idPartido
     * @param int $idEscritor
     * @return boolean
     */
    public function existeComentario($idPartido,$idEscritor) {
        $dql = "SELECT COUNT(c.id) AS num FROM Comentario c 
            WHERE c.partido = ?1 AND c.escritor = ?2";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->
            setParameter(2, (int)$idEscritor)->
            setMaxResults(1);
        
        $result = $query->getScalarResult();
        $r = reset($result);
        if (!isset($r) || sizeof($r) == 0) return NULL;
        return $r['num'] > 0;
    }
    
    /**
     * Devuelve el comentario identificado por $idPartido
     * y $idEscritor.
     * @param int $idPartido
     * @param int $idEscritor
     * @return Comentario
     * Comprobar isset().
     */
    public function getComentario($idPartido,$idEscritor) {
        $dql = "SELECT c FROM Comentario c 
            WHERE c.partido = ?1 AND c.escritor = ?2";
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idPartido)->
            setParameter(2, (int)$idEscritor)->
            setMaxResults(1);
        
        $result = $query->getResult();
        if (sizeof($result) == 0) return NULL;
        else if (sizeof($result) == 1) return reset($result);
    }
    
    public function getComentariosJugador($offset,$idEscritor) {
        $dql = "SELECT c FROM Comentario c 
            WHERE c.escritor = ?1 ORDER BY c.votos DESC";
        
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idEscritor)->
            setMaxResults(3)->
            setFirstResult($offset);
        
        return $query->getResult();
    }
    
    public function hayPosterioresComentariosJugador($offset,$idEscritor) {
        $dql = "SELECT c FROM Comentario c 
            WHERE c.escritor = ?1 ORDER BY c.votos DESC";
        
        $query = $this->getEntityManager()->
            createQuery($dql)->
            setParameter(1, (int)$idEscritor)->
            setMaxResults(3)->
            setFirstResult($offset+3);
        
        return sizeof($query->getResult()) > 0;    
    }
    
}

?>
