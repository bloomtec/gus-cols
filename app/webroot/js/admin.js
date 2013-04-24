/*global jQuery*/
(function ($) {
    'use strict';
    // Variables
    var campoId = 0;

    /**
     * Verificar si la colección está auditable o no
     * y aplicar visibilidad acorde esto.
     */
    function verificarSeleccionAuditable(campoColeccionAuditable) {
        var listaGrupos = $('#ColeccionGrupoId');
        if (listaGrupos.attr('type') !== 'hidden') {
            if (campoColeccionAuditable.is(':checked')) {
                listaGrupos.parent().css('visibility', 'visible');
            } else {
                listaGrupos.val('');
                listaGrupos.parent().css('visibility', 'hidden');
            }
        }
    }

    /**
     * Verificar los permisos de acceso
     */
    function verificarAcceso(checkboxAnonimo) {
        var alguna = false, todos = false, camposPermiso = $('.permiso-acceso');
        $.each(camposPermiso, function (i, node) {
            if ($(node).is(':checked')) {
                alguna = true;
            }
        });
        if (alguna && checkboxAnonimo.is(':checked')) {
            checkboxAnonimo.prop('checked', false);
        }
        $.each(camposPermiso, function (i, node) {
            var permiso = $(node);
            if (permiso.is(':checked') && permiso.attr('id') === 'Grupo1Acceso') {
                todos = true;
            }
            if (todos) {
                permiso.prop('checked', true);
            }
        });
    }

    /**
     * Verificar los permisos de creación
     */
    function verificarCrear() {
        var todos = false;
        $.each($('.permiso-creación'), function (i, node) {
            var permiso = $(node);
            if (permiso.is(':checked') && permiso.attr('id') === 'Grupo1Creación') {
                todos = true;
            }
            if (todos) {
                permiso.prop('checked', true);
            }
        });
    }

    /**
     * Agrega un campo a la colección
     */
    function agregarCampo() {
        var campos = $('#CamposColeccion'), campoClass = 'campo-' + campoId;
        if (campos) {
            campos.append('<tr class="' + campoClass + '"></tr>');
            $('.' + campoClass).load('/admin/colecciones/add_campo/' + campoId + '/' + $('#ColeccionId').val());
            campoId += 1;
        }
    }

    /**
     * Sección Document.ready
     */
    $(function () {

        var camposPermisos = $('.permiso-acceso'), campoColeccionAuditable = $('#ColeccionEsAuditable'), botonAgregarCampo = $('#AgregarCampo'), checkboxAnonimo = $('#ColeccionAccesoAnonimo');

        campoId = $('#CamposColeccion').children().length;

        if (campoColeccionAuditable) {
            verificarSeleccionAuditable(campoColeccionAuditable);
            campoColeccionAuditable.change(function () {
                verificarSeleccionAuditable(campoColeccionAuditable);
            });
        }

        botonAgregarCampo.click(function () {
            agregarCampo();
        });

        if (checkboxAnonimo) {
            verificarAcceso(checkboxAnonimo);
            checkboxAnonimo.change(function () {
                if (checkboxAnonimo.is(':checked')) {
                    $.each(camposPermisos, function (i, node) {
                        $(node).prop('checked', false);
                    });
                }
            });
            $.each(camposPermisos, function (i, node) {
                $(node).change(function () {
                    verificarAcceso(checkboxAnonimo);
                });
            });
        }

        $.each($('.permiso-creación'), function (i, node) {
            $(node).change(function () {
                verificarCrear();
            });
        });

    });
}(jQuery));
