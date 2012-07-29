$(document).ready(function() {
    $offsetComentarios = new Array();

    accionesPartidos();
    accionesEstadisticas();

    $('div.btnJornadaAnterior').toggle(function() {
        cargarJornadaAnterior();
        $(this).html('Jornada actual');
        $('div.partidos').hide();
    },function() {
        $(this).html('Jornada anterior');
        $('div.partidos').show();
        $('div.partidos-janterior').hide();
    });

});

accionesPartidos = function() {
    $('div.partido, div.partido-janterior').hover(function() {
        $(this).addClass('partido-hover');
    },function() {
        $(this).removeClass('partido-hover');
    })

    $('.btnComentariosAmigos').click(function() {
        var $idPartido = $(this).parent().parent().attr("id");
        cargarComentarios($idPartido,1) });

    $('.btnComentariosRecientes').click(function() {
        var $idPartido = $(this).parent().parent().attr("id");;
        cargarComentarios($idPartido,2) });

    $('.btnComentariosMejores').click(function() {
        var $idPartido = $(this).parent().parent().attr("id");;
        cargarComentarios($idPartido,3) });

    /* Código para mostrar la "explicación" de los botones.
     * Indico que el antecesor es .partidos para evitar el comportamiento
     * en .partidos-janterior
     **/

    $('.partidos .btnComentariosAmigos').hover(function() {
        $('<span id="explicacion">¿Y tus amigos?</span>').insertAfter($(this));
    },function() {
        $('#explicacion').remove();
    });

    $('.partidos .btnComentariosRecientes').hover(function() {
        $('<span id="explicacion">Los últimos</span>').insertAfter($(this));
    },function() {
        $('#explicacion').remove();
    });

    $('.partidos .btnComentariosMejores').hover(function() {
        $('<span id="explicacion">Los + votados</span>').insertAfter($(this));
    },function() {
        $('#explicacion').remove();
    });

    $('div.uno').not('.borde-rojo').click(function() {
        idPartido = $(this).parent().parent().attr('id');
        $(this).parent().find('.borde-rojo').removeClass('borde-rojo');
        $(this).addClass('borde-rojo');
        guardarResultado(idPartido,'1');
    });

    $('div.x').not('.borde-rojo').click(function() {
        idPartido = $(this).parent().parent().attr('id');
        $(this).parent().find('.borde-rojo').removeClass('borde-rojo');
        guardarResultado(idPartido,'x');
        $(this).addClass('borde-rojo');
    });

    $('div.dos').not('.borde-rojo').click(function() {
        var idPartido = $(this).parent().parent().attr('id');
        $(this).parent().find('.borde-rojo').removeClass('borde-rojo');
        guardarResultado(idPartido,'2');
        $(this).addClass('borde-rojo');
    });

    mostrarPanelComentar();
}

/**
 * Muestra el panel con el comentario del jugador.
 */
mostrarPanelComentar = function() {
    var comentarPanel1 = '<textarea class="comentar-textarea">';
    var comentarPanel2 = '</textarea><div class="btnPanelComentario btnOcultarComentar">Ocultar</div>';
    var comentarPanel3 = '<div class="btnPanelComentario btnComentar">Guardar comentarios</div>';

    $('input.comentar').click(function() {
        var textoInput = $(this).attr('value');
        var $padre = $(this).parent();

        var contenidoHttml = comentarPanel1 + textoInput + comentarPanel2;

        /* Ocultar botón btnComentar si el comentario está en la BD */
        var readonly = $(this).attr('readonly');
        if (!readonly || readonly.toLowerCase() === 'false') {
            contenidoHttml += comentarPanel3;
        }

        $padre.find("div.comentar-panel").html(contenidoHttml).hide();

        $(this).addClass('no-visible');
        $padre.find("div.comentar-panel").slideDown();
        $padre.removeClass('partido-hover');
        $padre.addClass('mostrandoComentario');
        $padre.unbind('hover');

        if ($(this).attr('readonly') == 'readonly') {
            $('textarea.comentar-textarea').attr('readonly','readonly');

            /* Mostrar mensaje de que el comentario no se puede guardar */
            $('<div class="mensaje">Este comentario no se puede modificar.</div>')
                .prependTo($(this).parent().find('.comentar-panel'))
                .hide().slideDown().delay(5000).slideUp(function() {
                    $(this).remove();
                });
        } else {
            $('textarea.comentar-textarea').select();
        }

        /**
         * Funcionalidad del botón para comentar.
         */
        $('div.btnComentar').click(function() {
            idPartido = $padre.attr('id');
            comentario = $(this).parent()
                .find('textarea.comentar-textarea').val();
            guardarComentario(idPartido,comentario);
        });

        /**
         * Funcionalidad del botón para ocultar comentario.
         */
        $('div.btnOcultarComentar').click(function() {
            textoTextarea = $(this).parent().
                find('textarea.comentar-textarea').val();
            $(this).parent().slideUp();
            $(this).parent().parent().find('input.comentar').
                attr('value',textoTextarea).addClass('visible')
                .removeClass('no-visible').end()
                .removeClass('mostrandoComentario')
                .hover(function() {
                    $(this).addClass('partido-hover');
                        },function() {
                    $(this).removeClass('partido-hover');
                });
        });
    });

}

/**
 * Muestra y oculta la sección de estadísticas
 */
accionesEstadisticas = function() {

    $('div.btnEstadisticas').toggle(function() {
        $('div.estadisticas').show();

        $('div.estadisticas-jugador').empty().load('cargarEstJugador.php',
            function() {
                cargarMejoresComentariosJugador();
                $('div.btnEstadisticas').html('Ocultar estad&iacute;sticas');
        });

        /**
         * Carga el ranking de jugadores por mejores pronósticos.
         * @element DIV rankingPronosticos.
         */
        $('div.estadisticasGlobales').empty()
            .load('cargarRanking.php?offset=0',function() {

                /**
                 * Carga la sección estadísitcas del jugador.
                 * @element: Nombres de los usuarios en los rankings.
                 */
                $('div.ranking-nombre').click(function() {
                   var idElem = $(this).parent().attr('id');
                   var idFacebook = idElem.substring(4);
                   $('div.estadisticas-jugador').empty()
                       .load('cargarEstJugador.php?idf=' + idFacebook,
                       function() {
                           cargarMejoresComentariosJugador(idFacebook);
                   });
                });

                $('div.rankingPronosticos div.ranking-jugador').first()
                    .css('background-image','url("../images/oro.jpg")');

                $('div.rankingComentarios div.ranking-jugador').first()
                    .css('background-image','url("../images/oro.jpg")');

                $('div.rankingPronosticos div.ranking-jugador').eq(1)
                    .css('background-image','url("../images/plata.jpg")');

                $('div.rankingComentarios div.ranking-jugador').eq(1)
                    .css('background-image','url("../images/plata.jpg")');

                $('div.rankingPronosticos div.ranking-jugador').eq(2)
                    .css('background-image','url("../images/bronce.jpg")');

                $('div.rankingComentarios div.ranking-jugador').eq(2)
                    .css('background-image','url("../images/bronce.jpg")');

            });

    },function() {
        $(this).html('Estad&iacute;sticas');
        $('div.estadisticas').hide();
    });
}

jqBtnCerrarComentarios = function() {
    $(this).parent().slideUp();
    var $idPartido = $(this).parent().parent().attr("id");
    $offsetComentarios[$idPartido] = 0;
}

cargarComentarios = function($idPartido,$opcion) {
    $offsetComentarios[$idPartido] = 0;

    var $urlComentarios = "cargarComentarios.php?offset="
        + $offsetComentarios[$idPartido]
        + "&idpartido=" + $idPartido + "&opcion=" + $opcion;

    $('#comentarios-' + $idPartido).empty().load($urlComentarios,function() {
        $('.btnOcultarComentarios').click(jqBtnCerrarComentarios);

        $('.masComentariosAmigos').click(function(){
            cargarMasComentarios($idPartido,1) });
        $('.masComentariosRecientes').click(function(){
            cargarMasComentarios($idPartido,2) });
        $('.masComentariosMejores').click(function(){
            cargarMasComentarios($idPartido,3) });

        /**
         * Si +1 o -1 está marcado no lanzará el evento.
         */
        $('div.btnMas1').not('marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMenos1').removeClass('marcado');
            gustoComentario($(this).parent(),1);
        })
        $('div.btnMenos1').not('marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMas1').removeClass('marcado');
            gustoComentario($(this).parent(),2);
        })

        $('div.btnMas1').not('marcado').hover(function() {
            $(this).addClass('btnVotos-hover');
        },function() {
            $(this).removeClass('btnVotos-hover');
        })
        $('div.btnMenos1').not('marcado').hover(function() {
            $(this).addClass('btnVotos-hover');
        },function() {
            $(this).removeClass('btnVotos-hover');
        })

    }).show();

    $offsetComentarios[$idPartido] += 3;
}

cargarMasComentarios = function($idPartido,$opcion) {
a
    var $urlComentarios = "cargarComentarios.php?offset="
        + $offsetComentarios[$idPartido]
        + "&idpartido=" + $idPartido + "&opcion=" + $opcion;

    $.get($urlComentarios, function(data) {
        $('#comentarios-' + $idPartido + " > .masComentariosRecientes").remove();
        $('#comentarios-' + $idPartido + " > .masComentariosMejores").remove();

        $('#comentarios-' + $idPartido).append(data);

        $('.btnOcultarComentarios').click(jqBtnCerrarComentarios);

        $('.masComentariosMejores').click(function(){
            cargarMasComentarios($idPartido,1) });

        $('.masComentariosRecientes').click(function(){
            cargarMasComentarios($idPartido,2) });

        $('.masComentariosMejores').click(function(){
            cargarMasComentarios($idPartido,3) });

        $('div.btnMas1').not('marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMenos1').removeClass('marcado');
            gustoComentario($(this).parent(),1);
        })
        $('div.btnMenos1').not('marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMas1').removeClass('marcado');
            gustoComentario($(this).parent(),2);
        })

        $('div.btnMas1').not('marcado').hover(function() {
            $(this).addClass('btnVotos-hover');
        },function() {
            $(this).removeClass('btnVotos-hover');
        })
        $('div.btnMenos1').not('marcado').hover(function() {
            $(this).addClass('btnVotos-hover');
        },function() {
            $(this).removeClass('btnVotos-hover');
        })
    });

    $offsetComentarios[$idPartido] += 3;
}

/**
 * @param $comentario jQuery del elemento con clase comentario.
 * @param opcion
 */
gustoComentario = function($comentario,opcion) {
    idComentario = $comentario.parent().attr('id').substring(4);;

    var $urlGustoComentario = "gustoComentario.php?idComentario="
        + idComentario + "&opcion=" + opcion;

    $.get($urlGustoComentario,function(data) {
        $($comentario).find('div.votos').html(data);
    });

}

guardarComentario = function($idPartido,$comentario) {
    $.post("guardarComentario.php",
        { idPartido: $idPartido, comentario: $comentario });
}

guardarResultado = function($idPartido,$resultado) {


    $.post("guardarPronostico.php",
        { idPartido: $idPartido, resultado: $resultado },
    function(data) {
        $('#depuracion').append(data);
    });
}

cargarJornadaAnterior = function() {
    $numJornada = $('#jornada').html();
    $('div.partidos-janterior').
        load('jornadaAnterior.php?jornada=' + $numJornada,
            accionesPartidos).show();
}

jqBtnCerrarComentariosJugador = function() {
    $(this).parent().hide();
    $offsetComentariosJugador = 0;
}

/**
 * Carga comentarios del jugador debajo de sus estadísticas.
 * @parameter idFacebook. Opcional. id de Facebook si se quieren
 * ver los comentarios de otro jugador que no sea el de sesión.
 */
cargarMejoresComentariosJugador = function(idFacebook) {
    offsetComentariosJugador = 0;

    var urlComentarios = "cargarComentarios.php?offset="
        + offsetComentariosJugador;

    if (typeof idFacebook == 'undefined') urlComentarios += "&opcion=4";
    else urlComentarios += "&opcion=4&idf=" + idFacebook;

    $.get(urlComentarios,function(data) {
        $('div.jugador-comentarios').html(data).show();
    });

    $('div.jugador-comentarios').empty().load(urlComentarios,function() {
        $('.btnOcultarComentarios').click(jqBtnCerrarComentariosJugador);

        $('.masComentariosJugador').click(function(idFacebook){
            cargarMasMejoresComentariosJugador(idFacebook) });

        /**
         * Si +1 o -1 está marcado no lanzará el evento.
         */
        $('div.btnMas1').not('div.marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMenos1').removeClass('marcado');
            gustoComentario($(this).parent(),1);
        })
        $('div.btnMenos1').not('div.marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMas1').removeClass('marcado');
            gustoComentario($(this).parent(),2);
        })

    }).show();

    offsetComentariosJugador += 3;
}

/**
 * Carga más comentarios del jugador debajo de sus estadísticas.
 * @parameter idFacebook. Opcional. id de Facebook si se quieren
 * ver los comentarios de otro jugador que no sea el de sesión.
 */
cargarMasMejoresComentariosJugador = function(idFacebook) {

    var $urlComentarios = "cargarComentarios.php?offset="
        + $offsetComentariosJugador + "&opcion=4&idf=" + idFacebook;

    $.get($urlComentarios, function(data) {
        $('div.jugador-comentarios > .masComentariosJugador').remove();

        $('div.jugador-comentarios').append(data);

        $('.btnOcultarComentarios').click(jqBtnCerrarComentariosJugador);

        $('.masComentariosJugador').click(function(){
            cargarMasMejoresComentariosJugador(idFacebook) });

        $('div.btnMas1').not('div.marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMenos1').removeClass('marcado');
            gustoComentario($(this).parent(),1);
        })
        $('div.btnMenos1').not('div.marcado').click(function() {
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMas1').removeClass('marcado');
            gustoComentario($(this).parent(),2);
        })
    });

    $offsetComentarios[$idPartido] += 3;
}