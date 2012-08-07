$(document).ready(function() {

    $('#elegirClub').fadeIn('slow');
    $('#elegirClub div.guardarClub').click(function() {
        $.get('guardarClub.php?club=' + $('#elegirClub option:selected').val(),
        function() {
            $('#elegirClub').fadeOut('slow').remove();
        });
    })
    $('#elegirClub div.cerrarElegirClub').click(function() {
        $('#elegirClub').fadeOut('slow').remove();
    })

    /**
     * Mantiene el número de comentarios mostrados.
     * clave: id del partido.
     * valor: entero.
     */
    offsetComentarios = new Array();

    numJornada = $('#jornada').html();
    $('#jornada').remove();

    /**
     * Esta variable si (== true) indica que se ha superado fechaTope de
     * la jornada y el usuario no puede comentar ni pronosticar resultados.
     */
    jugando = $('#jugando').html() == 'jugando';
    $('#jugando').remove();

    accionesPartidos();

    /* Las explicaciones sólo se muestran en partidos.
     * No en partidos-janterior
     */
    $('div.partido div.btnComentariosAmigos').hover(function() {
        $('<span id="explicacion">¿Y tus amigos?</span>').insertAfter($(this));
    },function() {
        $('#explicacion').remove();
    });
    $('div.partido div.btnComentariosRecientes').hover(function() {
        $('<span id="explicacion">Los últimos</span>').insertAfter($(this));
    },function() {
        $('#explicacion').remove();
    });
    $('div.partido div.btnComentariosMejores').hover(function() {
        $('<span id="explicacion">Los + votados</span>').insertAfter($(this));
    },function() {
        $('#explicacion').remove();
    });

    accionesListaAmigos();

    /**
     * Variable que define qué sección está viendo el usuario.
     * "actual", "anterior" y "estadisticas".
     */
    mostrando = "actual";

    $('#btnJornadaAnterior').click(function() {
        if (mostrando == "actual") mostrarJornadaAnterior();
        else mostrarJornadaActual();
    });

    $('#btnEstadisticas').click(function() {
        if (mostrando == "estadisticas") mostrarJornadaActual();
        else mostrarEstadisticas();
    });
});

mostrarJornadaAnterior = function() {
    if (typeof jornadaAnteriorCargada == 'undefined') {
        $('div.partidos-janterior')
            .load('jornadaAnterior.php?jornada=' + numJornada,function() {
            accionesPartidos();
            $(this).slideDown();

        });
        jornadaAnteriorCargada = true;
    } else {
        $('div.partidos-janterior').slideDown();
    }

    $('#btnJornadaAnterior').html('Jornada actual');
    $('#btnEstadisticas').html('Estad&iacute;sticas');

    $('div.partidos').slideUp();
    $('div.estadisticas').slideUp();

    mostrando = "anterior";
}

mostrarJornadaActual = function() {
    $('#btnJornadaAnterior').html('Jornada anterior');
    $('#btnEstadisticas').html('Estad&iacute;sticas');

    $('div.estadisticas').slideUp();
    $('div.partidos-janterior').slideUp();
    $('div.partidos').slideDown();

    mostrando = "actual";
}

/**
 * Muestra la sección de estadísticas.
 * - Define el comportamiento para el botón de estadísticas.
 *
 * Cuando se cargan las estadísticas personales:
 * - Se desliza el div de estadísticas.
 *
 * Cuando se cargan las estadísticas globales:
 * - Se le asignan imágenes de fondo a los 3 primeros ranking-jugador.
 */
mostrarEstadisticas = function(idFacebook) {
    cargarEstadisticas(idFacebook);

    $('#btnJornadaAnterior').html('Jornada actual');
    /*$('#btnEstadisticas').html('Ocultar stad&iacute;sticas');*/

    $('div.partidos').slideUp();
    $('div.partidos-janterior').slideUp();
    $('div.estadisticas').slideDown();

    mostrando = "estadisticas";
}

function sendRequestToRecipients(idFacebook) {
    FB.ui({
        method: 'apprequests',
        message: 'Demuestra lo que sabes de fútbol...',
        to: idFacebook
    }, requestCallback);
}

function requestCallback(response) {
}


compartirEnFacebook = function($boton,mensaje) {
    var $mensajeFb = $('<div class="mensajeFb"><textarea class="texto">'
        + mensaje + '</textarea><span>Compartiendo...</span><div class="btnCerrar">Cerrar</div>' +
        '<div class="btnCompartir">Compartir</div></div>')
        .appendTo('body').hide()
        .css({
            'top' : $boton.offset().top + 22,
            'left': $boton.offset().left - 101
        }).fadeIn();

    $mensajeFb.find('div.btnCompartir').click(function() {
        $mensajeFb.find('span').css('visibility','visible');
        $.get('compartirEnFB.php?mensaje=' + mensaje,function(data) {
            if (data != '') alert(data);
            /*$mensajeFb.children().not('span').remove();
            $mensajeFb.find('span').html('Compartido en tu muro.');
            $mensajeFb.delay(5000).fadeOut().remove();*/
            $mensajeFb.fadeOut().remove();
        });
    });

    $mensajeFb.find('div.btnCerrar').click(function() {
        $mensajeFb.fadeOut().remove();
    });
}


accionesListaAmigos = function() {
    $.get('cargarAmigos.php',function(data) {
        $('div.cabecera').append(data);
        listaAmigos = $('#listaAmigos').html();
        $('#listaAmigos').remove();

        $('#amigos').hover(function() {},function() {
            $(this).scrollTop(0);
        })

        $('.amigo:gt(0)').click(function() {
            var idFb = $(this).attr('id').substring(4);
            var esJugador;
            $.get('esJugador.php?idf=' + idFb,function(data) {
                esJugador = data == 'jugador';
                if (esJugador) {
                    mostrarEstadisticas(idFb);
                } else {
                    sendRequestToRecipients(idFb);
                }
            })

        });
    });
}

/**
 * Define comportamiento para div.partido y su contenido.
 * - Define hover para div.partido y div.partido-janterior.
 * - Define click y hover para los 3 botones de comentarios.
 * - Define click para los botones de resultado.
 * - Define las acciones para mostrar los comentarios.
 */
accionesPartidos = function() {
    /**
     * No defino :hover en CSS porque cuando la altura de div.partido
     * se amplía para div.panel-comentar no quiero que :hover ocurra.
     */
    $('div.partido:odd, div.partido-janterior:odd')
        .addClass('partido-impar');

    $('div.partido:even, div.partido-janterior:even')
        .addClass('partido-par');

    $('div.partido:odd, div.partido-janterior:odd').hover(function() {
        $(this).addClass('partido-impar-hover');
    },function() {
        $(this).removeClass('partido-impar-hover');
    })

    $('div.partido:even, div.partido-janterior:even').hover(function() {
        $(this).addClass('partido-par-hover');
    },function() {
        $(this).removeClass('partido-par-hover');
    })



    $('div.btnComentariosAmigos').click(function() {
        var idPartido = $(this).parent().attr("id");
        cargarComentarios(idPartido,1) });

    $('div.btnComentariosRecientes').click(function() {
        var idPartido = $(this).parent().attr("id");;
        cargarComentarios(idPartido,2) });

    $('div.btnComentariosMejores').click(function() {
        var idPartido = $(this).parent().attr("id");;
        cargarComentarios(idPartido,3) });

    if (!jugando) {
        $('div.partido div.resultado').children().hover(function() {
           $(this).addClass('resultados-hover');
        },function() {
           $(this).removeClass('resultados-hover');
        });

        $('div.partido div.resultado').children().click(function() {
            var $this = $(this);
            var $parent = $(this).parent();
            var idPartido = $parent.parent().attr('id');
            $parent.find('.borde-rojo').removeClass('borde-rojo');
            $this.addClass('borde-rojo');
            if ($this.hasClass('uno')) guardarResultado(idPartido,'1');
            else if ($this.hasClass('x')) guardarResultado(idPartido,'2');
            else if ($this.hasClass('dos')) guardarResultado(idPartido,'3');
        });
    }

    $('div.puntuacion').hover(function() {
        var texto;
        if ($(this).hasClass('puntuacionPronostico')) texto = 'Pronóstico';
        else texto = 'Votos de comentarios';
        $('<div id="puntuacionDscripcion">' + texto + '</div>').appendTo('body')
            .css({
                'top' : $(this).offset().top - 22,
                'left': $(this).offset().left - 22
            }).show();
    },function() {
        $('#puntuacionDscripcion').remove();
    });

    $('span.compartirPuntuacionSemanalEnFb').click(function() {
        var resultado = $(this).parent().find('.puntos').html();
        var mensaje = 'He conseguido ' + resultado + ' puntos esta semana en'
            + ' YoSéDeFútbol. Supérame.';
        compartirEnFacebook($(this),mensaje);
    });

    mostrarPanelComentar();
}

/**
 * Define comportamiento necesario para mostrar el panel para comentar.
 * - Define el comportamiento del cuadro de texto y de partido.
 * - Define el comportamiento para ocultar el panel.
 * - Define el comportamiento del botón para guardar un comentario.
 * - Define el comportamiento del botón para ocultar el panel.
 */
mostrarPanelComentar = function() {
    var comentarPanel1 = '<textarea class="comentar-textarea">';
    var comentarPanel2 = '</textarea><div class="btnPanelComentario ' +
        'btnOcultarComentar">Ocultar</div>';
    var comentarPanel3 = '<div class="btnPanelComentario btnComentar">' +
        'Guardar comentarios</div>';

    $('input.comentar').click(function() {
        var textoInput = $(this).attr('value');
        var $partido = $(this).parent();
        var contenidoHttml = comentarPanel1 + textoInput + comentarPanel2;

        /* Si el comentario viene desde la BD input['readonly']='readonly' */
        var readonly = $(this).attr('readonly');
        if (!jugando && (!readonly || readonly.toLowerCase() === 'false')) {
            contenidoHttml += comentarPanel3;
        }

        /* Retirar hover de partido */
        $partido.find("div.comentar-panel")
            .html(contenidoHttml)
            .hide()
            .slideDown()
            .removeClass('partido-hover')
            .addClass('mostrandoComentario')
            .unbind('hover');

        $(this).addClass('no-visible');

        if ($(this).attr('readonly') == 'readonly' || jugando) {
            $('textarea.comentar-textarea').attr('readonly','readonly');

            /* Mostrar mensaje de que el comentario no se puede guardar */
            $('<div class="mensaje">Este comentario no se puede modificar.</div>')
                .prependTo($partido.find('.comentar-panel'))
                .hide().slideDown().delay(5000).slideUp(function() {
                    $(this).remove();
                });
        } else {
            $('textarea.comentar-textarea').select();
        }

        ocultarPanel = function($partido) {
            textoTextarea = $partido.find('textarea.comentar-textarea').val();
            $partido.find('div.comentar-panel').slideUp();
            $partido.find('input.comentar').attr('value',textoTextarea)
                .addClass('visible')
                .removeClass('no-visible');

            /* Volver a asignar hover a partido */
            $partido.removeClass('mostrandoComentario')
                .hover(function() {
                    $partido.addClass('partido-hover');
                        },function() {
                    $partido.removeClass('partido-hover');
            });
        }

        /* $partido ya que con $() se cerraban todos. */
        $partido.find('div.btnComentar').click(function() {
            idPartido = $partido.attr('id');
            comentario = $(this).parent()
                .find('textarea.comentar-textarea').val();
            guardarComentario(idPartido,comentario);
            ocultarPanel($partido);
        });

        $partido.find('div.btnOcultarComentar').click(function() {
            ocultarPanel($partido);
        });
    });
}

/**
 * Carga las estadísticas del jugador y globales.
 * - Carga las estadísticas del jugador y los mejores comentarios del usuario.
 * - Carga las estadísticas globales.
 * - Define el comportamiento cuando se hace click sobre los nombres de los
 *      jugadores.
 * - Oculta las capas de partidos.
 * - Cambia el botón a "Jornada anterior"
 *
 * Cuando se cargan las estadísticas del jugador:
 * - Carga los mejores comentarios del jugador.
 * - Cambia el botón a "Ocultar estadísticas"
 */
cargarEstadisticas = function(idFacebook) {
    if (typeof idFacebook != 'undefined') {
        url = 'cargarEstJugador.php?idf=' + idFacebook;
    } else url = 'cargarEstJugador.php';

    $('div.estadisticas-jugador').empty()
        .load(url,function() {
            cargarMejoresComentariosJugador(idFacebook);
            $('#btnEstadisticas')
                .html('Ocultar estad&iacute;sticas');

            $('div.estadisticas').slideDown();

            offsetComentariosJugador = 0;
    });

    offsetRanking = 0;

    $('div.estadisticasGlobales').empty()
        .load('cargarRanking.php?offset=' + offsetRanking,
        function() {

        $('div.ranking-nombre').click(function() {
            var idElem = $(this).parent().attr('id');
            var idFacebook = idElem.substring(4);

            $('div.estadisticas-jugador').empty()
                .load('cargarEstJugador.php?idf=' + idFacebook,
                function() {
                    cargarMejoresComentariosJugador(idFacebook);

                    offsetComentariosJugador = 0;
                });
        });

        $('div.compartirRankingEnFb').click(function() {
            var $rankingJugador = $(this).parent().parent();
            var mensaje = 'He conseguido el ' +
                $rankingJugador.find('div.ranking-numero').html()
                + ' puesto en YoSéDeFútbol. Soy un crack.';

            compartirEnFacebook($(this),mensaje);
        });

        $('div.rankingPronosticos div.ranking-jugador-pronosticos').first()
            .css('background-image','url("../images/oro.jpg")');

        $('div.rankingComentarios div.ranking-jugador-comentarios').first()
            .css('background-image','url("../images/oro.jpg")');

        $('div.rankingClubes div.ranking-club').first()
            .css('background-image','url("../images/oro.jpg")');

        $('div.rankingPronosticos div.ranking-jugador-pronosticos').eq(1)
            .css('background-image','url("../images/plata.jpg")');

        $('div.rankingComentarios div.ranking-jugador-comentarios').eq(1)
            .css('background-image','url("../images/plata.jpg")');

        $('div.rankingClubes div.ranking-club').eq(1)
            .css('background-image','url("../images/plata.jpg")');

        $('div.rankingPronosticos div.ranking-jugador-pronosticos').eq(2)
            .css('background-image','url("../images/bronce.jpg")');

        $('div.rankingComentarios div.ranking-jugador-comentarios').eq(2)
            .css('background-image','url("../images/bronce.jpg")');

        $('div.rankingClubes div.ranking-club').eq(2)
            .css('background-image','url("../images/bronce.jpg")');

        offsetRanking += 5;

        $('div.ranking-masJugadores').click(function() {
            $.get('cargarRanking.php?offset=' + offsetRanking,function(data) {
                $('div.ranking-masJugadores').remove();
                $(data).find('div.ranking-jugador-pronosticos')
                    .appendTo('div.rankingPronosticos');
                $(data).find('div.ranking-jugador-comentarios')
                    .appendTo('div.rankingComentarios');


                offsetRanking += 3;

                $('div.ranking-nombre').click(function() {
                    var idElem = $(this).parent().attr('id');
                    var idFacebook = idElem.substring(4);

                    $('div.estadisticas-jugador').empty()
                        .load('cargarEstJugador.php?idf=' + idFacebook,
                    function() {
                        cargarMejoresComentariosJugador(idFacebook);

                        offsetComentariosJugador = 0;
                    });
                });
            });
        });

        $(this).css('display','block').slideDown();
    });
}

/*
 * Define el comportamiento de los botones +1 y -1 para click y hover.
 */
comportamientoBtnVotar = function() {
    /* Si +1 o -1 está marcado no lanzará el evento. */
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
}


/**
 * Carga y muestra los comentarios.
 * - Define el comportamiento de más comentarios.
 * - Define el comportamiento de los botones +1 y -1 para click y hover.
 * - Elimina los botones de más comentarios si existen.
 * - Define el comportamiento de los botones de ocultar comentarios.
 * - Define el comportamiento de los botones para compartir en Fb.
 */
cargarComentarios = function(idPartido,opcion) {
    if (typeof offsetComentarios[idPartido] == 'undefined')
        offsetComentarios[idPartido] = 0;

    var urlComentarios = "cargarComentarios.php?offset="
        + offsetComentarios[idPartido]
        + "&idpartido=" + idPartido + "&opcion=" + opcion;

    $.get(urlComentarios, function(data) {
        if (data == '') {
            return;
        }

        $('#comentarios-' + idPartido + " > .masComentariosRecientes").remove();
        $('#comentarios-' + idPartido + " > .masComentariosMejores").remove();

        $('#comentarios-' + idPartido).append(data).show();

        $('.btnOcultarComentarios').click(function() {
            $(this).parent().slideUp();
            var idPartido = $(this).parent().parent().attr("id");
            offsetComentarios[idPartido] = 0;
        });

        $('.masComentariosMejores').click(function(){
            cargarComentarios(idPartido,1) });
        $('.masComentariosRecientes').click(function(){
            cargarComentarios(idPartido,2) });
        $('.masComentariosMejores').click(function(){
            cargarComentarios(idPartido,3) });

        $('div.btnCompartirComentario').click(function() {
            var $comentario = $(this).parent().parent();
            var $partido = $comentario.parent().parent();
            var jugador = $comentario
                .find('div.comentario-cabecera-nombre').html();
            var club1 = $partido.find('div.club').eq(0).html();
            var club2 = $partido.find('div.club').eq(1).html();
            var mensaje = jugador + ' ha escrito un comentario muy bueno ' +
               'sobre el partido entre ' + club1 + ' y ' + club2 +
               ' en YoSéDeFútbol. ¿No vas a leerlo?';

           compartirEnFacebook($(this), mensaje);
        });

        $('div.comentario').hover(function() {
            $('div.comentarios-fb').hide();
            $(this).find('div.comentarios-fb').show();
        },function() {
            //$(this).find('div.comentarios-fb').hide();
        });

        comportamientoBtnVotar();

        FB.XFBML.parse();

    });

    offsetComentarios[idPartido] += 3;
}

/**
 * Ejecuta +1 o -1 sobre un comentario.
 * @param $comentario jQuery del elemento con clase comentario.
 * @param opcion
 * - Envía la petición al servidor.
 * - Actualiza el contador de votos.
 */
gustoComentario = function($comentario,opcion) {
    idComentario = $comentario.parent().attr('id').substring(4);

    var $urlGustoComentario = "gustoComentario.php?idComentario="
        + idComentario + "&opcion=" + opcion;

    $.get($urlGustoComentario,function(data) {
        $($comentario).find('div.votos').html(data);
    });
}

guardarComentario = function(idPartido,comentario) {
    $.post("guardarComentario.php",
        { idPartido: idPartido, comentario: comentario });
}

guardarResultado = function(idPartido,resultado) {
    $.get("guardarPronostico.php",
        { idPartido: idPartido, resultado: resultado });
}

/**
 * Carga comentarios del jugador debajo de sus estadísticas.
 * @parameter idFacebook. Opcional. id de Facebook si se quieren
 * ver los comentarios de otro jugador que no sea el de sesión.
 * - Define el comportamiento del botón para ocultar los comentarios.
 * - Define el comportamiento para el botón de más comentarios.
 * - Define el comportamiento de los botones +1 y -1.
 * - Elimina el botón de más comentarios anterior si existe.
 * - Define el comportamiento del botón Comentar en Facebook.
 */
cargarMejoresComentariosJugador = function(idFacebook) {

    if (typeof offsetComentariosJugador == 'undefined')
        offsetComentariosJugador = 0;

    var urlComentarios = "cargarComentarios.php?offset="
        + offsetComentariosJugador;

    if (typeof idFacebook == 'undefined') urlComentarios += "&opcion=4";
    else urlComentarios += "&opcion=4&idf=" + idFacebook;

    $.get(urlComentarios, function(data) {
        if (data == '') {
            $('div.jugador-comentarios').hide();
            return;
        }

        $('div.jugador-comentarios > .masComentariosJugador').remove();

        $('div.jugador-comentarios').append(data);

        $('div.btnOcultarComentarios').click(function() {
            $(this).parent().slideUp();
            offsetComentariosJugador = 0;
        });

        $('div.masComentariosJugador').click(function() {
            cargarMejoresComentariosJugador(idFacebook) });

        $('div.jugador-comentarios div.btnCompartirComentario').click(function()
        {
            var jugador = $('div.jugador-nombre a').html();
            var mensaje = jugador + ' ha escrito comentarios muy buenos ' +
                'en YoSéDeFútbol. ¿No vas a leerlos?';

            var $mensajeFb = $('<div class="mensajeFb"><textarea class="texto">'
                + mensaje + '</textarea><span>Compartiendo...</span><div class="btnCerrar">Cerrar</div>' +
                '<div class="btnCompartir">Compartir</div></div>')
                .appendTo('body').hide()
                .css({
                    'top' : $(this).offset().top + 22,
                    'left': $(this).offset().left - 101
                }).fadeIn();

            $mensajeFb.find('div.btnCompartir').click(function() {
                $mensajeFb.find('span').css('visibility','visible');
                $.get('compartirEnFB.php?mensaje=' + mensaje,function(data) {
                    if (data != '') alert(data);
                    $mensajeFb.fadeOut().remove();
                });
            });

            $mensajeFb.find('div.btnCerrar').click(function() {
                $mensajeFb.fadeOut().remove();
            });

        });

        comportamientoBtnVotar();

        $('div.comentario').hover(function() {
            $('div.comentarios-fb').hide();
            $(this).find('div.comentarios-fb').show();
        },function() {
            //$(this).find('div.comentarios-fb').hide();
        });

        FB.XFBML.parse();

        $('div.jugador-comentarios').slideDown();


    });

    offsetComentariosJugador += 3;
}