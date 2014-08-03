/**
 * Created with JetBrains PhpStorm.
 * User: Martin
 * Date: 07/07/13
 * Time: 10:28 PM
 * To change this template use File | Settings | File Templates.
 */

var ListaPagos = {
    ListaSocios: {},
    LoadSocios: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_socios" }
        }).done(function (data) {
                if (data && !data.error) {
                    ListaPagos.ListaSocios = {};
                    for (var i = 0; i < data.length; i++) {
                        ListaPagos.ListaSocios[data[i].id] = data[i];
                    }
                    ListaPagos.LoadListaPagos();
                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'Unexpected error');
                    }
                }
                Toolbox.StopLoader();
            });
    },
    LoadListaPagos: function () {

        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_pagos" }
        }).done(function (data) {
                if (data && !data.error) {
                    $('#listaPagosTabla').html("");
                    for (var i = 0; i < data.length; i++) {

                        var tagCancelado = "";
                        if(data[i].cancelado==true){
                            tagCancelado = '<span class="label labelCancelado">PAGO CANCELADO</span> ';
                        }

                        $('#listaPagosTabla').append('<tr onClick="document.location.href = \'/pago.php?id=' + data[i].id + '\'"><td>' + data[i].id + '</td>' +
                            '<td>' + data[i].valor + '</td>' +
                            '<td>' + Toolbox.MysqlDateToDate(data[i].fecha_pago) + '</td>' +
                            '<td>' + Toolbox.TransformSpecialTag(data[i].razon) + '</td>' +
                            '<td>' + tagCancelado + data[i].notas + '</td>' +
                            '<td>' + Toolbox.TransformSpecialTag(data[i].tipo) + '</td>' +
                            '<td><a href="/socio.php?id=' + data[i].id_socio + '" class="label" style="background-color:#AF002A;">#' + ListaPagos.ListaSocios[data[i].id_socio].numero + ' ' + ListaPagos.ListaSocios[data[i].id_socio].nombre + '</a></td></tr>');
                    }
                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'Unexpected error');
                    }
                }
                Toolbox.StopLoader();
            });
    },
    ExportarComoListaPagosPorSocio: function(){
        $("#exportIframe").attr("src","proc/controller.php?exportar=exportar_pagos_por_socio");
    },
    ExportarComoListaTotalPagoPorSocio: function(){
        $("#exportIframe").attr("src","proc/controller.php?exportar=exportar_pago_total_por_socio");
    }
}

$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_lista_pagos');
    ListaPagos.LoadSocios();

});
