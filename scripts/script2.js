
$('div.btnCompartirComentario').click(function() {
    var jugador = $('div.comentario-cabecera-nombre').html();
    $.get('compartirEnFB.php?jugador=' + jugador);
});