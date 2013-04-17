(function($) {
    /**
     * Verificar si la colección está auditable o no
     * y aplicar visibilidad acorde esto.
     */
    function verificarSeleccionAuditable() {
        if ($('#ColeccionEsAuditable').is(':checked')) {
            $('#ColeccionGrupoId').parent().css('visibility', 'visible');
        } else {
            $('#ColeccionGrupoId').parent().css('visibility', 'hidden');
        }
    }

    /**
     * Verificar los permisos de acceso
     */
    function verificarAcceso() {
        var alguna = false, todos = false;
        $.each($('.permiso-acceso'), function(i, node) {
            if($(node).is(':checked')) {
                alguna = true;
            }
        });
        var anonimo = $('#ColeccionAccesoAnonimo');
        if(alguna && anonimo.is(':checked')) {
            anonimo.prop('checked', false);
        }
        $.each($('.permiso-acceso'), function(i, node) {
            var permiso = $(node);
            if(permiso.is(':checked') && permiso.attr('id') == 'Grupo1Acceso') {
                todos = true;
            }
            if(todos) {
                permiso.prop('checked', true);
            }
        });
    }

    /**
     * Verificar los permisos de creación
     */
    function verificarCrear() {
        var todos = false;
        $.each($('.permiso-creación'), function(i, node) {
            var permiso = $(node);
            if(permiso.is(':checked') && permiso.attr('id') == 'Grupo1Creación') {
                todos = true;
            }
            if(todos) {
                permiso.prop('checked', true);
            }
        });
    }

    /**
     * Agrega un campo a la colección
     */
    var campoId = 0;
    function agregarCampo() {
        var campos = $('#CamposColeccion'), campoClass = 'campo-' + campoId;
        campos.append('<tr class="' + campoClass + '"></tr>');
        $('.' + campoClass).load('/admin/colecciones/add_campo/' + campoId);
        campoId += 1;
    }
    $(function() {
        campoId = $('#CamposColeccion').children().length;
        verificarSeleccionAuditable();
        verificarAcceso();
        $('#ColeccionEsAuditable').change(function() {
            verificarSeleccionAuditable();
        });
        $('#AgregarCampo').click(function() {
            agregarCampo();
        });
        var anonimo = $('#ColeccionAccesoAnonimo');
        anonimo.change(function() {
            if (anonimo.is(':checked')) {
                $.each($('.permiso-acceso'), function(i, node) {
                    $(node).prop('checked', false);
                });
            }
        });
        $.each($('.permiso-acceso'), function(i, node) {
            $(node).change(function() {
                verificarAcceso();
            });
        });
        $.each($('.permiso-creación'), function(i, node) {
            $(node).change(function() {
                verificarCrear();
            });
        });
    });
})(jQuery);
