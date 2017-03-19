/**
 * Created with JetBrains PhpStorm.
 * User: Martin
 * Date: 08/07/13
 * Time: 10:50 PM
 * To change this template use File | Settings | File Templates.
 */

var Pago = {
    IdPago: null,
    PagoData: {},
    LoadPago: function () {

        $('#pagoDatosValorRazon').css("display","block");
        $('#pagoDatosValorDescuento').css("display","block");
        $('#pagoBtnCancelar').css("display","block");
        $('#pagoEditarRazon').css("display","none");
        $('#pagoEditarDescuento').css("display","none");
        $('#pagoBtnSalvar').css("display","none");

        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_pago", id: Pago.IdPago }
        }).done(function (data) {
                if (data && !data.error) {
                    Pago.PagoData = data;

                    var descuento = "";
                    if(data.descuento != "" && data.descuento != "0.00"){
                        descuento = data.descuento + ' ' + Toolbox.TransformSpecialTag(data.descuento_json)
                    }

                    if (data.cancelado == true) {
                        $('#pagoLabelEstado').css('display', 'block');
                        $('#pagoBtnCancelarContainer').css('display', 'none');
                    } else {
                        $('#pagoLabelEstado').css('display', 'none');
                        $('#pagoBtnCancelarContainer').css('display', 'block');
                    }
                    $("#pagoNombreTitulo").html('Pago #' + data.id);
                    $("#pagoDatosValorValor").html('<p>' + data.valor + "</p>");
                    $("#pagoDatosValorFechaPago").html('<p>' + Toolbox.MysqlDateToDate(data.fecha_pago) + "</p>");
                    $("#pagoDatosValorTipo").html('<p>' + Toolbox.TransformSpecialTag(data.tipo) + "</p>");
                    $("#pagoDatosValorNotas").html('<p>' + data.notas + "</p>");
                    $("#pagoDatosValorRazon").html('<p>' + Toolbox.TransformSpecialTag(data.razon) + "</p>");
                    $("#pagoDatosValorSocio").html('<p>' + data.id_socio + "</p>");
                    $("#pagoDatosValorDescuento").html(descuento);

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
    CancelarPago: function () {

        var confirmacion = confirm('Anular pago permanentemente?');

        if (confirmacion) {
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "cancelar_pago", id: Pago.IdPago }
            }).done(function (data) {
                    if (data && !data.error) {
                        Pago.LoadPago();
                    } else {
                        if (data && data.error) {
                            Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                        } else {
                            Toolbox.ShowFeedback('feedbackContainer', 'error', 'Unexpected error');
                        }
                    }
                    Toolbox.StopLoader();
                });
        }
    },
    Editar: function(){
        $('#pagoDatosValorRazon').css("display","none");
        $('#pagoEditarRazon').css("display","table");
        $('#pagoDatosValorDescuento').css("display","none");

        $("#pagoEditarRazonRazon").val(Pago.PagoData.razon);
        $("#pagoEditarDescuentoDescuento").val(Pago.PagoData.descuento);
        $("#pagoEditarDescuentoRazonDescuento").val(Pago.PagoData.descuento_json);

        $('#pagoEditarDescuento').css("display","block");
        $('#pagoBtnCancelar').css("display","none");
        $('#pagoBtnSalvar').css("display","block");
    },
    Salvar: function(){

        //var razonPago = $("#pagoEditarRazonRazon").val();
        //if ($("#pagoEditarRazonRazon").val() == "mensualidad") {
        //    razonPago = "mensualidad (" + $('#pagoEditarRazonMensualidadMes').val() + "/" + $('#pagoEditarRazonMensualidadAnio').val() + ")";
        //}

        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "salvar_pago_modificar",
                id: Pago.IdPago,
                razon: $("#pagoEditarRazonRazon").val(),
                descuento: $("#pagoEditarDescuentoDescuento").val(),
                descuento_json: $("#pagoEditarDescuentoRazonDescuento").val()
            }
        }).done(function (data) {
            if (data && !data.error) {
                Pago.LoadPago();

            } else {
                if (data && data.error) {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                } else {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', 'No se pudieron salvar las modificaciones');
                }
            }
            Toolbox.StopLoader();
        });
    }
}

    $(document).ready(function () {

        Toolbox.UpdateActiveNavbar('');
        var params = Toolbox.GetUrlVars();

        if (params['id']) {
            Pago.IdPago = params['id'];
        }

        $('#pagoBtnCancelar').on('click', function () {
            Pago.CancelarPago();
        });

        Pago.LoadPago();

    });
