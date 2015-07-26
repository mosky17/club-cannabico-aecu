var VistaSocio = {
    IdSocio: null,
    Hash:null,
    Tags: {},
    SocioData: {},
    Geneticas: null,
    GetTags: function () {
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/public_controller.php",
            data: { func: "get_tags" }
        }).done(function (data) {
            if (data && !data.error) {

                for (var i = 0; i < data.length; i++) {
                    VistaSocio.Tags[data[i].id] = data[i];
                }

                VistaSocio.LoadSocio();

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
    LoadGeneticas: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/public_controller.php",
            data: { func: "get_lista_geneticas" }
        }).done(function (data) {
            if (data && !data.error) {
                VistaSocio.Geneticas = {};

                for(var i=0;i<data.length;i++){
                    VistaSocio.Geneticas[data[i].id] = data[i];
                }
            }
            VistaSocio.LoadEntregas();
            Toolbox.StopLoader();
        });
    },
    LoadSocio: function () {

        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/public_controller.php",
            data: { func: "get_socio", hash: VistaSocio.Hash }
        }).done(function (data) {
            if (data && !data.error) {
                VistaSocio.SocioData = data;
                VistaSocio.IdSocio = data.id;

                $('#socioLabelEstado').removeClass('labelEstadoActivo');
                $('#socioLabelEstado').removeClass('labelEstadoSuspendido');
                if(data.activo==true){
                    $('#socioLabelEstado').addClass('labelEstadoActivo');
                    $('#socioLabelEstado').html("Activo");
                }else{
                    $('#socioLabelEstado').addClass('labelEstadoSuspendido');
                    $('#socioLabelEstado').html("Suspendido");
                }

                $("#socioNombreTitulo").html(data.nombre);
                $("#socioDatosValorNumero").html('<p>' + data.numero + "</p>");
                $("#socioDatosValorEmail").html('<p>' + data.email + "</p>");
                $("#socioDatosValorFechaInicio").html('<p>' + Toolbox.MysqlDateToDate(data.fecha_inicio) + "</p>");
                //tags
                var tagsHtml = "<div style='padding: 10px 0 0;'>";
                for (var j = 0; j < data.tags.length; j++) {
                    if (data.tags[j] && data.tags[j] != '' && VistaSocio.Tags[data.tags[j]]) {
                        tagsHtml += '<span class="label socioTag" style="background-color:' + VistaSocio.Tags[data.tags[j]].color + '">' + VistaSocio.Tags[data.tags[j]].nombre + '</span>';
                    }
                }
                $("#socioDatosValorTags").html(tagsHtml + "</div>");

                VistaSocio.LoadPagos();
                VistaSocio.GetDeudas();
                VistaSocio.LoadGeneticas();

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
    LoadPagos: function () {
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/public_controller.php",
            data: { func: "get_pagos_socio", id_socio: VistaSocio.IdSocio }
        }).done(function (data) {
            if (data && !data.error) {

                $('#listaPagosSocioTabla').html("");
                for (var i = 0; i < data.length; i++) {

                    $('#listaPagosSocioTabla').append('<tr><td>' + data[i].id + '</td>' +
                        '<td>' + data[i].valor + '</td>' +
                        '<td>' + Toolbox.MysqlDateToDate(data[i].fecha_pago) + '</td>' +
                        '<td>' + Toolbox.TransformSpecialTag(data[i].razon) + '</td>' +
                        '<td>' + Toolbox.TransformSpecialTag(data[i].tipo) + '</td></tr>');
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
    LoadEntregas: function(){
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/public_controller.php",
            data: { func: "get_entregas_socio", id_socio: VistaSocio.IdSocio }
        }).done(function (data) {
            if (data && !data.error) {

                $('#listaEntregasSocioTabla').html("");
                for (var i = 0; i < data.length; i++) {

                    var genetica = VistaSocio.Geneticas[data[i].id_genetica];
                    if(genetica){
                        genetica = genetica.nombre;
                    }else{
                        genetica = "";
                    }

                    $('#listaEntregasSocioTabla').append('<tr><td>' + data[i].gramos + '</td>' +
                        '<td>' + Toolbox.MysqlDateToDate(data[i].fecha) + '</td>' +
                        '<td>' + Toolbox.TransformSpecialTag(genetica) + '</td></tr>');
                }

            } else {
                if (data && data.error) {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                } else {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', 'Error inesperado');
                }
            }
            Toolbox.StopLoader();
        });
    },
    GetDeudas: function(){
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/public_controller.php",
            data: { func: "get_deudas_socio", id_socio: VistaSocio.IdSocio }
        }).done(function (data) {
            if (data && !data.error) {
                $('.socioRecordatorioDeudaContainer').html('');
                for(var i=0;i<data.length;i++){
                    $('.socioRecordatorioDeudaContainer').append('<span class="alert alert-danger socio-deuda"><strong>$' + data[i].monto + "</strong>  " + data[i].razon + '</span>');
                }
            }
            Toolbox.StopLoader();
        });
    }
}

$(document).ready(function () {

    var params = Toolbox.GetUrlVars();

    if (params['h']) {
        VistaSocio.Hash = params['h'];
    }
    VistaSocio.GetTags();

});
