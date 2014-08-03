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

        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_pago", id: Pago.IdPago }
        }).done(function (data) {
                if (data && !data.error) {
                    Pago.PagoData = data;

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
