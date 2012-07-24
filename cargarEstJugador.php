<?

/**
 * @param string idf. Opcional. Si se quiere cargar los
 *  comentarios de otro jugador que no es el de la sesión.
 */

require_once 'bootstrap.php';

if (!isset($_GET['idf'])) {
    $idFacebook = $facebook->getUser();
} else {
    $idFacebook = $_GET['idf'];
}

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$jugador = $repositorioJugadores->getJugador($idFacebook);
if (!isset($jugador)) {
    $jugador = new Jugador($idFacebook);
    $em->persist($jugador);
    $em->flush($jugador);
}

$fql = "SELECT name, url, pic FROM profile WHERE id =" . $idFacebook . ";";
$array = $facebook->api(array( 'method' => 'fql.query',
                                'query' => $fql,));

$a = reset($array);

$rankingPronosticos = $repositorioJugadores->
    posicionRankingPronosticos($jugador->getId());

$rankingComentarios = $repositorioJugadores->
    posicionRankingComentarios($jugador->getId());

?>
<div class="jugador-cabecera" id="<?= $idFacebook ?>">
    <p class="jugador-foto" style="background-image: url(<?= $a['pic'] ?>);"></p>
    <div class="jugador-nombre"><a target="_blank" href="<?= $a['url'] ?>"><?= $a['name'] ?></a></div>
    <div class="jugador-puntosPronostico">Puntos por pron&oacute;stico: <?= $jugador->getSumaPronosticos() ?></div>
    <div class="jugador-ranking-pronostico">Ranking: <?= $rankingPronosticos ?>º</div>
    <div class="jugador-puntosComentario">Puntos por comentario: <?= $jugador->getSumaComentarios() ?></div>
    <div class="jugador-ranking-comentario">Ranking: <?= $rankingComentarios ?>º</div>
</div>
<div style="clear:left;"></div>
<div class="jugador-comentarios"></div>    

