$(document).ready(function() {
    $offsetComentarios = new Array();

    $('div.partido').hover(function() {
        $(this).addClass('partido-hover');
    },function() {
        $(this).removeClass('partido-hover');
    })

    $('.btnComentariosAmigos').click(function() {
        var $idPartido = $(this).parent().attr("id");
        cargarComentarios($idPartido,1) }); 
    
    $('.btnComentariosRecientes').click(function() {
        var $idPartido = $(this).parent().attr("id");;
        cargarComentarios($idPartido,2) });

    $('.btnComentariosMejores').click(function() {
        var $idPartido = $(this).parent().attr("id");;
        cargarComentarios($idPartido,3) });

    var comentarPanel1 = '<textarea class="comentar-textarea">';
    var comentarPanel2 = '</textarea><div class="btnOcultarComentar btnPanelComentario"\n\
        >Ocultar</div><div class="btnPanelComentario btnComentar">Guardar comentario</div>';

    /**
     * Muestra el panel con el comentario del jugador.
     */
    $('input.comentar').click(function() {
        var textoInput = $(this).attr('value');
        var $padre = $(this).parent();
        
        /* Crea el textarea */
        $padre.find("div.comentar-panel")
            .html(comentarPanel1 + textoInput + comentarPanel2).hide();
        
        $(this).addClass('no-visible');
        $padre.find("div.comentar-panel").slideDown();
        $padre.removeClass('partido-hover');
        $padre.addClass('mostrandoComentario');
        
        $padre.unbind('hover');

        
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
                })

        }); 

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
    
    $('div.btnJornadaAnterior').toggle(function() {
        cargarJornadaAnterior();
        $(this).html('Jornada actual');
        $('div.partidos').hide();
    },function() {
        $(this).html('Jornada anterior');
        $('div.partidos').show();
        $('div.partidos-janterior').hide();
    });
    
    /**
     * Muestra y oculta la sección de estadísticas
     */
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
        $('div.rankingPronosticos').empty()
            .load('cargarRanking.php?offset=0&opcion=1',function() {
            
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
            }); 
        
        /**
         * Carga el ranking de jugadores por mejores comentarios.
         * @element DIV rankingComentarios.
         */
        $('div.rankingComentarios').empty()
            .load('cargarRanking.php?offset=0&opcion=2',function() {
            
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
            }); 
 
    },function() {
        $(this).html('Estad&iacute;sticas');
        $('div.estadisticas').hide();
    }); 


    
    
});

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
            alert('+1');
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMenos1').removeClass('marcado');
            gustoComentario($(this).parent(),1);
        })
        $('div.btnMenos1').not('marcado').click(function() {
            alert('-1');
            $(this).addClass('marcado');
            $(this).parent().find('div.btnMas1').removeClass('marcado');
            gustoComentario($(this).parent(),2);
        })
        
    }).show();

    $offsetComentarios[$idPartido] += 3; 
}

cargarMasComentarios = function($idPartido,$opcion) {
    
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
        
        $('div.btnMas1').not('div.marcado').click(function() {
            gustoComentario($(this).parent().parent().attr('id'),1);
        })
        $('div.btnMenos1').not('div.marcado').click(function() {
            gustoComentario($(this).parent().parent().attr('id'),2);
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
    alert('holaholita');
    
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
            asignarEventosJornadaAnterior).show();
}

asignarEventosJornadaAnterior = function() {
    $('.btnComentariosAmigos').click(function() {
        var $idPartido = $(this).parent().attr("id");
        cargarComentarios($idPartido,1) }); 
    
    $('.btnComentariosRecientes').click(function() {
        var $idPartido = $(this).parent().attr("id");;
        cargarComentarios($idPartido,2) });

    $('.btnComentariosMejores').click(function() {
        var $idPartido = $(this).parent().attr("id");;
        cargarComentarios($idPartido,3) });

    var comentarPanel1 = '<textarea class="comentar-textarea"\n\>';
    var comentarPanel2 = '</textarea><div class="btnOcultarComentar"\n\
        >Ocultar</div><div class="btnComentar">Guardar comentario</div>';

    $('input.comentar').click(function() {
        var textoInput = $(this).attr('value');

        $(this).parent().find("div.comentar-panel")
            .html(comentarPanel1 + textoInput + comentarPanel2).show();
        $(this).addClass('no-visible');
        $('div.btnComentar').click(function() {
            $idPartido = $(this).parent().parent().attr('id');
            $comentario = $(this).parent()
                .find('textarea.comentar-textarea').val();
            guardarComentario($idPartido,$comentario);
        });   
        $('div.btnOcultarComentar').click(function() {
            $(this).parent().hide();
            $(this).parent().parent()
                .find('input.comentar').addClass('visible')
                    .removeClass('no-visible'); 
        }); 
    });

    $('div.uno').not('borde-rojo').click(function() {
        $idPartido = $(this).parent().parent().attr('id');
        $(this).parent().find('.borde-rojo').removeClass('borde-rojo');
        $(this).addClass('borde-rojo');
        guardarResultado($idPartido,'1');
    });
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
    else urlComentarios += "&opcion=4&idf=" + idFacebook

    alert(urlComentarios);

    $('div.jugador-comentarios').empty().load(urlComentarios,function() {     
        $('.btnOcultarComentarios').click(jqBtnCerrarComentariosJugador);
        
        /**
         * Incluir en PHP y en CSS.
         */
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