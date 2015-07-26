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


                    $('#macropago_tabla_socios').html('<tr><td class="right"><input onchange="ListaPagos.MacroPagoSeleccionarTodosChanged();" type="checkbox" class="macropago_socio_chk" id="macropago_socio_todos">' +
                        '<label class="macropago_socio_label" for="macropago_socio_todos"><b>Seleccionar todos</b></label></td></tr>');
                    var html = "";
                    var countCols = 1;

                    ListaPagos.ListaSocios = {};
                    for (var i = 0; i < data.length; i++) {
                        ListaPagos.ListaSocios[data[i].id] = data[i];

                        if(countCols == 1){
                            html += '<tr><td class="right">';
                        }else{
                            html += '<td class="left">';
                        }

                        html += '<input type="checkbox" class="macropago_socio_chk" id="macropago_socio_' + data[i].id + '">' +
                            '<label class="macropago_socio_label" for="macropago_socio_' + data[i].id + '">' + data[i].nombre + '</label></td>';

                        if(countCols == 2){
                            html += '</tr>';
                            $('#macropago_tabla_socios').append(html);
                            html = "";
                            countCols = 0;
                        }else if(i == data.length-1){
                            html += '<td></td></tr>';
                            $('#macropago_tabla_socios').append(html);
                        }

                        countCols += 1;
                    }

                    ListaPagos.LoadListaPagos();
                    ListaPagos.LoadListaDeudas();
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
    },
    ExportarComoListaPagosPorMes: function(){
        $("#exportIframe").attr("src","proc/controller.php?exportar=exportar_pagos_por_mes");
    },
    LoadListaDeudas: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_all_deudas" }
        }).done(function (data) {
            if (data && !data.error) {
                $('#listaDeudasTabla').html("");
                for (var i = 0; i < data.length; i++) {

                    $('#listaDeudasTabla').append('<tr>' +
                        '<td>' + data[i].monto + '</td>' +
                        '<td>' + data[i].razon + '</td>' +
                        '<td><a href="/socio.php?id=' + data[i].id_socio + '" class="label" style="background-color:#AF002A;">#' + ListaPagos.ListaSocios[data[i].id_socio].numero + ' ' + ListaPagos.ListaSocios[data[i].id_socio].nombre + '</a></td>' +
                        '<td><a href="#" onclick="ListaPagos.CancelarDeuda(\'' + data[i].id + '\');return false;">cancelar</a></td></tr>');
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
    CancelarDeuda: function(id){
        if(confirm("Cancelar deuda?")){
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "cancelar_deuda", id: id }
            }).done(function (data) {
                if (data && !data.error) {

                    ListaPagos.LoadListaDeudas();

                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'Error al cancelar deuda.');
                    }
                }
                Toolbox.StopLoader();
            });
        }
    },
    OpenModalMacroPago: function(){
        $('#macroPagoModal').modal('show');
    },
    VerificarMacroPago: function(){
        if(confirm("Esta seguro que desea agregar este pago a todos los socios seleccionados?")){
            if($('.macropago_valor').val()==""){
                Toolbox.ShowFeedback('macroPagoModalFeedback', 'error', 'Falt&oacute; especificar el valor del pago');
                return false;
            }else if(isNaN($('.macropago_valor').val())){
                Toolbox.ShowFeedback('macroPagoModalFeedback', 'error', 'Falto especificar fecha de pago');
                return false;
            }else if($('.macropago_fecha').val() == ""){
                Toolbox.ShowFeedback('macroPagoModalFeedback', 'error', 'Falto especificar fecha de pago');
                return false;
            }
            return true;
        }else{
            return false;
        }
    },
    AgregarMacroPago: function(){
        if(ListaPagos.VerificarMacroPago()){

            var listaSocioIds = [];
            $('.macropago_socio_chk').each(function() {
                if ($(this).prop("checked") == true) {
                    if($(this).attr('id') != 'macropago_socio_todos'){
                        listaSocioIds.push($(this).attr('id').substring(16));
                    }
                }
            });

            var razonPago = $("#macropago_razon").val();
            if($("#macropago_razon").val() == "mensualidad"){
                razonPago = "mensualidad (" + $('#macropago_razonMensualidad').val() + "/" + $('#macropago_razonMensualidadAnio').val() + ")";
            }

            for(var i=0;i<listaSocioIds.length;i++){
                (function(index){
                    Toolbox.ShowLoaderModal();
                    $.ajax({
                        dataType: 'json',
                        type: "POST",
                        url: "proc/controller.php",
                        data: { func: "ingresar_pago",
                            id_socio: listaSocioIds[index],
                            valor: $(".macropago_valor").val(),
                            fecha_pago: Toolbox.DataToMysqlDate($(".macropago_fecha").val()),
                            razon: razonPago,
                            notas: "",
                            tipo: $("#macropago_tipo").val() }
                    }).done(function (data) {

                        if(index == listaSocioIds.length-1){
                            ListaPagos.LoadListaPagos();
                            $('#macroPagoModal').modal('hide');
                            Toolbox.ShowFeedback('feedbackContainer', 'success', 'Macro pago agregado con exito');
                        }
                        Toolbox.StopLoaderModal();
                    });
                })(i);
            }
        }
    },
    MacroPagoSeleccionarTodosChanged: function(){
        if($('#macropago_socio_todos').prop("checked") == true){
            $('.macropago_socio_chk').prop("checked",true);
        }else{
            $('.macropago_socio_chk').prop("checked",false);
        }
    },
    TogglePagoRazon: function(){
        if($('#macropago_razon').val() == "mensualidad"){
            $('#macropago_razonMensualidad').css('display','block');
            $('#macropago_razonMensualidadAnio').css('display','block');
        }else{
            $('#macropago_razonMensualidad').css('display','none');
            $('#macropago_razonMensualidadAnio').css('display','none');
        }
    }
}

$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_lista_pagos');
    $(".macropago_fecha").mask("99/99/9999");
    ListaPagos.LoadSocios();

});
