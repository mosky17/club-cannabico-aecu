/**
 * Created with JetBrains PhpStorm.
 * User: Martin
 * Date: 09/03/14
 * Time: 08:06 PM
 * To change this template use File | Settings | File Templates.
 */

var Estadisticas = {
    DataGastos:null,
    DataPagos:null,
    DataGeneticas: null,
    LoadListaGastos: function () {

        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_gastos" }
        }).done(function (data) {
                if (data && !data.error) {
                    Estadisticas.DataGastos = data;
                    Estadisticas.ArmarTortaGastos(data);
                    if(Estadisticas.DataPagos){
                        Estadisticas.ArmarGraficaIngresosEgresos();
                    }
                }
                Toolbox.StopLoader();
            });
    },
    LoadPagos: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_pagos" }
        }).done(function (data) {
                if (data && !data.error) {
                    Estadisticas.DataPagos = data;
                    if(Estadisticas.DataGastos){
                        Estadisticas.ArmarGraficaIngresosEgresos();
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
            url: "proc/controller.php",
            data: { func: "get_lista_geneticas" }
        }).done(function (data) {
            if (data && !data.error) {
                Estadisticas.DataGeneticas = {};
                for(var i=0;i<data.length;i++){
                    Estadisticas.DataGeneticas[data[i].id] = data[i];
                }
                Estadisticas.LoadEntregas();
            }
            Toolbox.StopLoader();
        });
    },
    LoadEntregas: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_entregas" }
        }).done(function (data) {
                if (data && !data.error) {
                    Estadisticas.ArmarGraficaEntregas(data);
                }
                Toolbox.StopLoader();
            });
    },
    ArmarBarsGastos: function(data){

        //parse data
        var auxData = {};
        for(var i=0;i<data.length;i++){

            var valor = Number(data[i].valor);
            if(valor > 0){
                var mes = new Date(data[i].fecha_pago);
                var year = mes.getFullYear();
                mes = Toolbox.NombreMesesEsp[mes.getMonth()+1] + " " + year;

                if(!auxData[mes]){
                    auxData[mes] = 0;
                }
                auxData[mes] += valor;
            }
        }
        var dataSource = [];
        $.each(auxData, function( index, value ) {
            var mes = index;
            dataSource.push({mes: mes, valor: value});
        });

        $("#bars-gastos").dxChart({
            dataSource: dataSource,
            argumentAxis: {
                valueMarginsEnabled: false,
                discreteAxisDivisionMode: "crossLabels",
                grid: {
                    visible: true
                }
            },
            series: {
                valueField: "valor",
                argumentField: "mes",
                color: '#CB797C'
            },
            commonSeriesSettings: {
                label: {
                    visible: true,
                    connector: {
                        visible: true
                    },
                    customizeText: function(value){
                        return "$" + value.value;
                    }
                },
                argumentField: "mes",
                type: "fullStackedLine"
            },
            tooltip: {
                enabled: true,
                customizeText: function(value){
                    return "$" + value.value;
                }
            },
            legend: {
                visible: false
            },
            valueAxis: {
                title: {
                    text: "pesos"
                }
            },
            title: "Gastos totales (por mes)"
        });
    },
    ArmarTortaGastos: function(data){

        //parse data
        var auxData = {};
        for(var i=0;i<data.length;i++){
            if(data[i].rubro == "" || data[i].rubro == "Devoluciones"){
                //do nothing
            }else{
                if(!auxData[data[i].rubro]){
                    auxData[data[i].rubro] = 0;
                }
                auxData[data[i].rubro] += Number(data[i].valor);
            }
        }
        var dataSource = [];
        $.each(auxData, function( index, value ) {
            var rubro = index;

            switch(rubro){
                case 'Locacion':
                    rubro = 'Locaci&oacute;n';
                    break;
                case 'Energia':
                    rubro = 'Energ&iacute;a';
                    break;
            }

            dataSource.push({rubro: rubro, valor: value});
        });

        $("#torta-gastos").dxPieChart({
            size:{
                width: 900
            },
            dataSource: dataSource,
            series: [
                {
                    argumentField: "rubro",
                    valueField: "valor",
                    label:{
                        visible: true,
                        connector:{
                            visible:true,
                            width: 1
                        },
                        customizeText: function(value){
                            return value.percentText;
                        }
                    }
                }
            ],
            tooltip: {
                enabled: true,
                customizeText: function(value){
                    return "$" + value.value;
                }
            },
            title: "Gastos totales por rubro ($)"
        });
    },
    ArmarGraficaIngresosEgresos: function(){

        var dataSource = [];
        var auxDataEgreso = {};
        var auxDataIngreso = {};

        //parse data GASTOS
        //console.log(Estadisticas.DataGastos);
        for(var i=0;i<Estadisticas.DataGastos.length;i++){

            if(Estadisticas.DataGastos[i].fecha_pago == "2014-10-01"){
                console.log(new Date(Estadisticas.DataGastos[i].fecha_pago));
            }

            var mes = (Estadisticas.DataGastos[i].fecha_pago).split("-")[1];
            var year = (Estadisticas.DataGastos[i].fecha_pago).split("-")[0];
            mes = Toolbox.NombreMesesEsp[Number(mes)] + " " + year;

            var valor = Number(Estadisticas.DataGastos[i].valor);
            if(valor > 0){
                //egreso
                if(!auxDataEgreso[mes]){
                    auxDataEgreso[mes] = 0;
                }
                auxDataEgreso[mes] += valor;
                if(mes == "Octubre 2014"){
                    console.log(valor);
                }
            }else{
                //ingreso
                if(!auxDataIngreso[mes]){
                    auxDataIngreso[mes] = 0;
                }
                auxDataIngreso[mes] += valor * -1;
            }

        }
        $.each(auxDataEgreso, function( index, value ) {
            var mes = index;
            dataSource.push({mes: mes, egresos: value});
        });
        $.each(auxDataIngreso, function( index, value ) {
            var mes = index;
            dataSource.push({mes: mes, ingresos: value});
        });

        //parse data PAGOS
        auxDataIngreso = {};
        for(var i=0;i<Estadisticas.DataPagos.length;i++){

            var mes = new Date(Estadisticas.DataPagos[i].fecha_pago);
            var year = mes.getFullYear();
            mes = Toolbox.NombreMesesEsp[mes.getMonth()+1] + " " + year;

            if(!auxDataIngreso[mes]){
                auxDataIngreso[mes] = 0;
            }
            auxDataIngreso[mes] += Number(Estadisticas.DataPagos[i].valor);
        }
        $.each(auxDataIngreso, function( index, value ) {
            var mes = index;
            dataSource.push({mes: mes, ingresos: value});
        });

        //console.log(dataSource);

        $("#torta-ingresos").dxChart({
            dataSource: dataSource,
            series: [
                {
                    argumentField: "mes",
                    valueField: "egresos",
                    name: "Egresos",
                    type: "bar",
                    color: '#C46A6A'
                },
                {
                argumentField: "mes",
                valueField: "ingresos",
                name: "Ingresos",
                type: "bar",
                color: '#7EC16A'
                }],
            tooltip: {
                enabled: true,
                customizeText: function(value){
                    return "$" + value.value;
                }
            },
            legend: {
                visible: true,
                verticalAlignment: 'bottom',
                horizontalAlignment: 'center'
            },
            valueAxis: {
                title: {
                    text: "pesos"
                }
            },
            title: "Ingresos/Egresos (por mes)"
        });
    },
    ArmarGraficaEntregas: function(data){

        //parse data

        var auxData = {};
        var variedades = {};
        for(var i=0;i<data.length;i++){

            var fecha = new Date(data[i].fecha);
            var year = fecha.getFullYear();
            var textoX = Toolbox.NombreMesesEsp[fecha.getMonth()+1] + " " + year;

            if(!auxData[textoX]){
                auxData[textoX] = {};
            }

            var variedad = Estadisticas.DataGeneticas[data[i].id_genetica];
            if(variedad){
                variedad = variedad.nombre;
            }else{
                variedad = "Sin definir"
            }
            if(!variedades[variedad]){
                variedades[variedad] = "true";
            }

            if(auxData[textoX][variedad]){
                auxData[textoX][variedad] += Number(data[i].gramos);
            }else{
                auxData[textoX][variedad] = Number(data[i].gramos);
            }
        }
        var dataSource = [];
        $.each(auxData, function( index, value ) {
            var mes = index;
            var variedades = value;
            var obj = {mes: mes};
            $.each(variedades, function( indexV, valueV ) {
                obj[indexV] = valueV;
            });
            dataSource.push(obj);
        });

        var fields = [];
        $.each(variedades, function( index, value ) {
            fields.push({valueField: index, name: index});
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
            commonSeriesSettings: {
                argumentField: "mes",
                type: "stackedBar"
            },
            series: fields,
            legend: {
                verticalAlignment: "bottom",
                horizontalAlignment: "center",
                itemTextPosition: 'top'
            },
            title: "Entregas totales (por mes)",
            tooltip: {
                enabled: true,
                customizeText: function () {
                    return this.seriesName + ": " + this.valueText + " gr.";
                }
            },
            valueAxis: {
                title: {
                    text: "gramos"
                }
            },
        });
    }
}



$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_lista_estadisticas');
    Estadisticas.LoadListaGastos();
    Estadisticas.LoadPagos();
    Estadisticas.LoadGeneticas();

});