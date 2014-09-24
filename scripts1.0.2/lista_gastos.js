/**
 * Created with JetBrains PhpStorm.
 * User: Martin
 * Date: 03/08/13
 * Time: 07:12 PM
 * To change this template use File | Settings | File Templates.
 */

var ListaGastos = {

    CreandoHaber: false,
    LoadListaGastos: function () {

        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_gastos" }
        }).done(function (data) {
                if (data && !data.error) {
                    $('#listaGastosTabla').html("");
                    for (var i = 0; i < data.length; i++) {

                        var tagCancelado = "";
                        if(data[i].cancelado==true){
                            tagCancelado = '<span class="label labelCancelado">GASTO CANCELADO</span> ';
                        }

                        var valor = Number(data[i].valor).toFixed(2);
                        var haber = '<i class="icon icon-arrow-right green-arrow"></i>';
                        if(valor > 0){
                            haber = '<i class="icon icon-arrow-left red-arrow"></i>';
                        }else{
                            valor = valor * -1;
                        }

                        $('#listaGastosTabla').append('<tr onClick="document.location.href = \'/gasto.php?id=' + data[i].id + '\'"><td>' + data[i].id + '</td>' +
                            '<td>' + valor + '</td>' +
                            '<td>' + haber + '</td>' +
                            '<td>' + Toolbox.MysqlDateToDate(data[i].fecha_pago) + '</td>' +
                            '<td>' + data[i].razon + '</td>' +
                            '<td>' + Toolbox.TransformSpecialTag(data[i].rubro) + '</td>' +
                            '<td>' + tagCancelado + data[i].notas + '</td></tr>');
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
    ExportarCaja: function(){
        $("#exportIframe").attr("src","proc/controller.php?exportar=exportar_caja");
    },
    IngresarGasto: function () {
        if (ListaGastos.VerificarDatosGasto()) {

            var valor = $("#listaIngresarGastoValor").val();
            if(ListaGastos.CreandoHaber){
                valor = valor*-1;
            }

            var rubro = $('#listaIngresarGastoGrupo').val();

            Toolbox.ShowLoaderModal();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "ingresar_gasto", valor: valor,
                    fecha_pago: Toolbox.DataToMysqlDate($("#listaIngresarGastoFecha").val()),
                    razon: $("#listaIngresarGastoRazon").val(), notas: $("#listaIngresarGastoNotas").val(),
                    rubro: rubro}
            }).done(function (data) {
                    if (data && !data.error) {
                        ListaGastos.LoadListaGastos();
                        $('#listaIngresarGastoModal').modal('hide');
                    } else {
                        if (data && data.error) {
                            Toolbox.ShowFeedback('feedbackContainerModalIngresarGasto', 'error', data.error);
                        } else {
                            Toolbox.ShowFeedback('feedbackContainerModalIngresarGasto', 'error', 'Unexpected error');
                        }
                    }
                    Toolbox.StopLoaderModal();
                });
        }
    },
    VerificarDatosGasto: function () {
        var error = undefined;


        if (!error && $('#listaIngresarGastoValor').val() == '') {
            error = 'Falt&oacute; especificar el valor del gasto';
        } else if (!error && $('#listaIngresarGastoFecha').val() == '') {
            error = 'Falt&oacute; especificar fecha de gasto';
        } else if (!error && isNaN($('#listaIngresarGastoValor').val())) {
            error = 'Valor invalido';
        } else if (!error && $('#listaIngresarGastoGrupo').val() == "") {
            error = 'Falt&oacute; especificar rubro';
        }

        if (error == undefined) {
            Toolbox.ShowFeedback('feedbackContainerModalIngresarGasto', '', '');
        }
        else {
            Toolbox.ShowFeedback('feedbackContainerModalIngresarGasto', 'error', error);
        }

        return error == undefined;
    },
    GetTotales: function(){
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_totales" }
        }).done(function (data) {
                if (data && !data.error) {
                   $('.totales').html('<div class="campo">Ingreso Socios: <span class="text-success">$' + data.ingresos_socios + '</span></div>' +
                                      '<div class="campo">Otros Ingresos: <span class="text-success">$' + data.otros_ingresos + '</span></div>' +
                                       '<div class="campo">Gastos: <span class="text-error">$' + data.gastos + '</span></div>' +
                                       '<div class="campo">Total en Caja: <b>$' + Number(data.ingresos_socios + data.otros_ingresos - data.gastos)  + '</b></div>');
                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainerModalIngresarGasto', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainerModalIngresarGasto', 'error', 'Unexpected error');
                    }
                }
                Toolbox.StopLoader();
            });
    },
    OpenModalNuevoGasto: function(){
        $('.feedbackContainerModal').css('display', 'none');
        $('#listaIngresarGastoModalLabel').html('Nuevo Gasto');
        ListaGastos.CreandoHaber = false;
        $('#listaIngresarGastoValor').val('');
        $('#listaIngresarGastoFecha').val(Toolbox.GetFechaHoyLocal());
        $('#listaIngresarGastoRazon').val('');
        $('#listaIngresarGastoNotas').val('');
        $('#listaIngresarGastoGrupo').val('');
        $('.loaderModal').css('display','none');
        $('#listaIngresarGastoModal').modal('show');
        $('#listaIngresarGastoValor').focus();
    },
    OpenModalNuevoHaber: function(){

        $('.feedbackContainerModal').css('display', 'none');
        $('#listaIngresarGastoModalLabel').html('Nuevo Ingreso');
        ListaGastos.CreandoHaber = true;
        $('#listaIngresarGastoValor').val('');
        $('#listaIngresarGastoFecha').val(Toolbox.GetFechaHoyLocal());
        $('#listaIngresarGastoRazon').val('');
        $('#listaIngresarGastoNotas').val('');
        $('#listaIngresarGastoGrupo').val('');
        $('.loaderModal').css('display','none');
        $('#listaIngresarGastoModal').modal('show');
        $('#listaIngresarGastoValor').focus();
    }
}

$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_lista_gastos');

    ListaGastos.LoadListaGastos();

    $('#listaIngresarGastoModalBtnIngresar').on('click', ListaGastos.IngresarGasto);
    $("#listaIngresarGastoFecha").mask("99/99/9999");

    ListaGastos.GetTotales();

});
