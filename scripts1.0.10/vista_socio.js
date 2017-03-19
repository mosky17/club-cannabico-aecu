var VistaSocio = {
    IdSocio: null,
    Hash:null,
    SocioData: {},
    Geneticas: null,
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

                    var descuento = "";
                    if(data[i].descuento != "" && data[i].descuento != "0.00"){
                        descuento = data[i].descuento + ' ' + Toolbox.TransformSpecialTag(data[i].descuento_json)
                    }

                    $('#listaPagosSocioTabla').append('<tr><td>' + data[i].id + '</td>' +
                        '<td>' + data[i].valor + '</td>' +
                        '<td>' + Toolbox.MysqlDateToDate(data[i].fecha_pago) + '</td>' +
                        '<td>' + Toolbox.TransformSpecialTag(data[i].razon) + '</td>' +
                        '<td>' + descuento + '</td>' +
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
                VistaSocio.ArmarGraficaEntregas(data);
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
                if(data.length > 0){
                    $('.deudas').css("display","block");
                }
                for(var i=0;i<data.length;i++){
                    $('.socioRecordatorioDeudaContainer').append('<span class="alert alert-danger socio-deuda"><strong>$' + data[i].monto + "</strong>  " + data[i].razon + '</span>');
                }
            }
            Toolbox.StopLoader();
        });
    },
    ArmarGraficaEntregas: function(data){

        var dataSource = [];
        var auxData = {};
        var orderCats = [];

        data.sort(function(a, b){
            var dateA = new Date(a.fecha);
            var dateB = new Date(b.fecha);
            return dateA - dateB;
        });

        //parse data
        for(var i=0;i<data.length;i++){
            var fecha = new Date(data[i].fecha);
            var year = fecha.getFullYear();
            var textoX = Toolbox.NombreMesesEsp[fecha.getMonth()+1] + " " + year;

            if(!auxData[textoX]){
                auxData[textoX] = 0;
                orderCats.push(textoX);
            }

            auxData[textoX] += Number(data[i].gramos);
        }

        $.each(auxData, function( index, value ) {
            var mes = index;
            dataSource.push({mes: mes, valor: value});
        });

        //console.log(dataSource);

        /*
         var dataSource = [
         { state: "Germany", young: 6.7, older: 5.1 },
         { state: "Japan", young: 9.6, middle: 43.4, older: 9},
         { state: "Russia", young: 13.5, middle: 49},
         { state: "USA", middle: 90.3, older: 14.5 }
         ];
         */

        $("#torta-entregas").dxChart({
            dataSource: dataSource,
            series: {
                argumentField: "mes",
                valueField: "valor",
                type: "bar",
                color: '#ffa500'
            },
            legend: {
                visible: false
            },
            tooltip: {
                enabled: true,
                customizeText: function () {
                    var num = Number(this.valueText);
                    return num.toFixed(2) + " gr.";
                }
            },
            valueAxis: {
                title: {
                    text: "gramos"
                }
            },
            argumentAxis: {
                type: 'discrete',
                categories: orderCats
            }
        });
    }
}

$(document).ready(function () {

    var params = Toolbox.GetUrlVars();

    if (params['h']) {
        VistaSocio.Hash = params['h'];
    }
    VistaSocio.LoadSocio();
});
