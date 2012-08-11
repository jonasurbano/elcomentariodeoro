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

/**
 * Esquema DOM resultante.
 * <div class="comentario" id="com-idComentario">">
 *  <div class="comentario-cabecera">
 *   <div class="comentario-cabecera-foto"></div>
 *   <div class="comentario-cabecera-nombre"></div>
 *  </div>
 *  <div class="comentario-texto"></div>
 *  <div class="comentario-pie">
 *   <div class="votos"></div>
 *   <div class="btnMas1">+1</div>
 *   <div class="btnMenos1">-1</div>
 *   <div class="btnCompartirComentario">Compartir en Facebook</div>
 *   <div style="clear:both;"></div>
 *  </div>
 *  <div class="comentarios-fb">
 *   <fb:comments></fb:comments>
 *  </div>
 * </div>
 * <div class="btnOcultarComentarios">Ocultar comentarios</div>
 * <div class="masComentariosAmigos">M&aacute;s comentarios</div>
 */

if (!isset($_GET['opcion']) || !is_numeric($_GET['opcion']) ||
    $_GET['opcion'] < 1 || $_GET['opcion'] > 4) exit();
if (!isset($_GET['offset']) || !is_numeric($_GET['offset']) ||
    $_GET['offset'] < 0) exit();

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
if ($opcion != 4 && !isset($_GET['idpartido'])) exit();
$offset = $_GET['offset'];

$idFacebook = $facebook->getUser();
if ($idFacebook) {
    $jugador = $repositorioJugadores->getJugador($idFacebook);
    if ($jugador) $idJugador = $jugador->getId();
    else $idJugador = -1;
}

/**
 * Si los comentarios son del usuario, no se pueden votar.
 */
$mostrarVotacion = true;

if ($opcion == 1) {
    if (!isset($_GET['idpartido']) || !is_numeric($_GET['idpartido'])) exit();
    $idPartido = $_GET['idpartido'];

    if (!$idFacebook) exit();
    $amigosFb = idx($facebook->api('/' . $idFacebook .
        '/friends?limit=10'), 'data', array());
    $listaAmigos = listaAmigos($amigosFb);

    $comentarios = $repositorioComentarios->
        getComentariosAmigos($offset,$idPartido,$listaAmigos);

} else if ($opcion == 2) {
    if (!isset($_GET['idpartido']) || !is_numeric($_GET['idpartido'])) exit();
    $idPartido = $_GET['idpartido'];

    $comentarios = $repositorioComentarios->
        getComentariosRecientes($offset,$idPartido,$idJugador);

} else if ($opcion == 3) {
    if (!isset($_GET['idpartido']) || !is_numeric($_GET['idpartido'])) exit();
    $idPartido = $_GET['idpartido'];

    $comentarios = $repositorioComentarios->
        getComentariosMasVotados($offset,$idPartido,$idJugador);


} else if ($opcion == 4) {
    if (isset($_GET['idf']) && is_numeric($_GET['idf'])) {
        $mostrarVotacion = true;
        $idJugador = $repositorioJugadores->getIdJugador($_GET['idf']);
    } else $mostrarVotacion = false;

    $comentarios = $repositorioComentarios->
        getComentariosJugador($offset,$idJugador);

}

$hayComentariosPosteriores = sizeof($comentarios) >= 4;
if ($hayComentariosPosteriores) {
    unset($comentarios[3]);
}

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
        <div class="comentario-texto">
        <? if ($opcion == 4) {
            $partido = $comentario->getPartido();
            $club1 = $partido->getClub1();
            $club2 = $partido->getClub2();
            echo '<b>Partido: ' . $club1 . ' - ' . $club2 . '.</b> ';
        } ?>
        <?= $comentario->getComentario() ?></div>
        <div class="comentario-pie"><div class="votos"><?
        $votos = $comentario->getVotos();
        if ($votos == 1) echo '1 voto';
        else if ($votos == -1) echo '-1 voto';
        else echo $comentario->getVotos() . ' votos';
        ?></div><? if (isset($mostrarVotacion) && $mostrarVotacion) {
        ?><div class="btnMas1<?= $marcadoMas1 ?>">+1</div><div class="btnMenos1
            <?= $marcadoMenos1 ?>">-1</div><? } ?><div
            class="btnCompartirComentario">Compartir en Facebook</div>
            <div style="clear:both;"></div>
        </div>
        <div class="comentarios-fb">
            <fb:comments href="<?= AppInfo::getUrl('/verComentario.php?id=' . $comentario->getId() ); ?>" num_posts="3" width="670"></fb:comments>
        </div>
    </div>
<div class="btnOcultarComentarios">Ocultar comentarios</div><? }

if ($hayComentariosPosteriores) {
    if ($opcion == 1) { ?><div class="masComentariosAmigos">M&aacute;s comentarios</div><?
    } else if ($opcion == 2) { ?><div class="masComentariosRecientes"><?
    } else if ($opcion == 3) { ?><div class="masComentariosMejores"><?
    } else if ($opcion == 4) { ?><div class="masComentariosJugador"><?
} ?>M&aacute;s comentarios</div><? } ?>
