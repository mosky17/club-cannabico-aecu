/**
 * Created with JetBrains PhpStorm.
 * User: Martin
 * Date: 03/08/13
 * Time: 07:27 PM
 * To change this template use File | Settings | File Templates.
 */

var Gasto = {
    IdGasto: null,
    GastoData: {},
    LoadGasto: function () {

        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_gasto", id: Gasto.IdGasto }
        }).done(function (data) {
                if (data && !data.error) {
                    Gasto.GastoData = data;

                    if (data.cancelado == true) {
                        $('#gastoLabelEstado').css('display', 'block');
                        $('#gastoBtnCancelarContainer').css('display', 'none');
                    } else {
                        $('#gastoLabelEstado').css('display', 'none');
                        $('#gastoBtnCancelarContainer').css('display', 'block');
                    }
                    $("#gastoNombreTitulo").html('Gasto #' + data.id);
                    $("#gastoDatosValorValor").html('<p>' + data.valor + "</p>");
                    $("#gastoDatosValorFechaGasto").html('<p>' + Toolbox.MysqlDateToDate(data.fecha_pago) + "</p>");
                    $("#gastoDatosValorNotas").html('<p>' + data.notas + "</p>");
                    $("#gastoDatosValorRazon").html('<p>' + Toolbox.TransformSpecialTag(data.razon) + "</p>");

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
    CancelarGasto: function () {

        var confirmacion = confirm('Anular gasto permanentemente?');

        if (confirmacion) {
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "cancelar_gasto", id: Gasto.IdGasto }
            }).done(function (data) {
                    if (data && !data.error) {
                        Gasto.LoadGasto();
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
        Gasto.IdGasto = params['id'];
    }

    $('#gastoBtnCancelar').on('click', function () {
        Gasto.CancelarGasto();
    });

    Gasto.LoadGasto();

});