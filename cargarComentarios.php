<?php

/**
 * @param string idf. Opcional. Para opción 4 si se quiere cargar los
 *  comentarios de otro jugador que no es el de la sesión.
 * @param int opcion
 *  - 1. Comentarios de amigos.
 *  - 2. Comentarios recientes.
 *  - 3. Comentarios más votados.
 *  - 4. Comentarios de un jugador.
 * @param int offset Primer comentario a devolver por la consulta.
 * @param int idPartido. Opcional Identificador del partido para las 
 *  opciones 1, 2 y 3.
 */

if (!isset($_GET['opcion'])) die();
if (!isset($_GET['offset'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioComentarios = $em->getRepository('Comentario');
$repositorioJugadores = $em->getRepository('Jugador');

/**
 * Elabora una cadena de los ids de la aplicación
 * para una consulta WHERE-IN en DQL.
 * @global EntityRepository $repositorioJugadores
 * @param  Array $amigosFb
 * @return string
 */
function listaAmigos($amigosFb) {
    $listaAmigos = '';
    global $repositorioJugadores;
    foreach ($amigosFb as $amigo) {
        $idFb = idx($amigo, 'id');
        $idAmigo = $repositorioJugadores->getIdJugador($idFb);
        
        if ($idAmigo) $listaAmigos .= "'" . $idAmigo . "',";
    }

    $listaAmigos = substr($listaAmigos,0,-1);
    return $listaAmigos;
}

$opcion = $_GET['opcion'];
if ($opcion != 4 && !isset($_GET['idpartido'])) die();
$offset = $_GET['offset'];

$idFacebook = $facebook->getUser();

if ($opcion == 1) {    
    $idPartido = $_GET['idpartido'];
    
    $idFacebook = $facebook->getUser();
    $amigosFb = idx($facebook->api('/me/friends?limit=10'), 'data', array());
    $listaAmigos = listaAmigos($amigosFb);
    
    $comentarios = $repositorioComentarios->
        getComentariosAmigos($offset,$idPartido,$listaAmigos);
    $hayComentariosPosteriores = $repositorioComentarios->
        hayMasComentariosAmigos($offset,$idPartido,$listaAmigos);
    
} else if ($opcion == 2) {
    $idPartido = $_GET['idpartido'];

    $comentarios = $repositorioComentarios->
        getComentariosRecientes($offset,$idPartido);
    $hayComentariosPosteriores = $repositorioComentarios->
        hayPosterioresComentariosRecientes($offset,$idPartido);

} else if ($opcion == 3) {
    $idPartido = $_GET['idpartido'];
    
    $comentarios = $repositorioComentarios->
        getComentariosMasVotados($offset,$idPartido);
    $hayComentariosPosteriores = $repositorioComentarios->
        hayPosterioresComentariosMasVotados($offset,$idPartido);    
} else if ($opcion == 4) {
    if (isset($_GET['idf'])) $idFacebook = $_GET['idf']; 
    $idJugador = $repositorioJugadores->getIdJugador($idFacebook);
    if (!isset($idJugador)) die();
    
    $comentarios = $repositorioComentarios->
        getComentariosJugador($offset,$idJugador);
    $hayComentariosPosteriores = $repositorioComentarios->
        hayPosterioresComentariosJugador($offset,$idJugador);    
}

$jugador = $repositorioJugadores->getJugador($idFacebook);
foreach ($comentarios as $comentario) {  
    $marcadoMas1 = '';
    $marcadoMenos1 = '';
    
    if (isset($jugador) && $jugador->gustaComentarioAJugador($comentario)) {
        $marcadoMas1 = ' marcado'; }
    if (isset($jugador) && $jugador->noGustaComentarioAJugador($comentario)) {
        $marcadoMenos1 = ' marcado'; } 
        
    ?><div class="comentario" id="com-<?= $comentario->getId() ?>">
        <?
        if ($opcion != 4) {
            
            $fql = "SELECT name, pic FROM profile WHERE id =" . 
                $comentario->getEscritor()->getIdFacebook() . ";";
            
            $array = $facebook->api(array( 'method' => 'fql.query',
                                            'query' => $fql,));

            $a = reset($array);
            
        ?><div class="comentario-cabecera">
            <div class="comentario-cabecera-foto" style="background-image: 
                url(<?= $a['pic'] ?>);"></div>
            <div class="comentario-cabecera-nombre"><?= $a['name'] ?></div>
        </div><? } ?>
        <div class="comentario-texto"><?= 
            $comentario->getComentario() ?></div>
        <div class="comentario-pie"><div class="votos"><?
        $votos = $comentario->getVotos(); 
        if ($votos == 1) echo '1 voto';
        else if ($votos == -1) echo '-1 voto';
        else echo $comentario->getVotos() . ' votos';
        ?></div><? if ($opcion != 4) {
        ?><div class="btnMas1<?= $marcadoMas1 ?>">+1</div><div class="btnMenos1
            <?= $marcadoMenos1 ?>">-1</div><? } ?><div 
            class="btnCompartirComentario">Compartir en Facebook</div>
            <div style="clear: both;" ></div>
        </div>
        
    </div>
<div class="btnOcultarComentarios">Ocultar comentarios</div><? }
    
if ($hayComentariosPosteriores > 0) { 
    if ($opcion == 1) { ?><div class="masComentariosAmigos">M&aacute;s comentarios</div><? 
    } else if ($opcion == 2) { ?><div class="masComentariosRecientes"><? 
    } else if ($opcion == 3) { ?><div class="masComentariosMejores"><? 
    } else if ($opcion == 4) { ?><div class="masComentariosJugador"><?
} ?>M&aacute;s comentarios</div><? } ?>
