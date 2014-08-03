/**
 * Created with JetBrains PhpStorm.
 * User: Martin
 * Date: 09/03/14
 * Time: 08:06 PM
 * To change this template use File | Settings | File Templates.
 */

var Estadisticas = {
    LoadListaGastos: function () {

        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_gastos" }
        }).done(function (data) {
                if (data && !data.error) {
                    Estadisticas.ArmarBarsGastos(data);
                    Estadisticas.ArmarTortaGastos(data);
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
                    Estadisticas.ArmarGraficaIngresos(data);
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
            if(!auxData[data[i].rubro]){
                auxData[data[i].rubro] = 0;
            }
            auxData[data[i].rubro] += Number(data[i].valor);
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
    ArmarGraficaIngresos: function(data){

        //parse data
        var auxData = {};
        for(var i=0;i<data.length;i++){

            var mes = new Date(data[i].fecha_pago);
            var year = mes.getFullYear();
            mes = Toolbox.NombreMesesEsp[mes.getMonth()+1] + " " + year;

            if(!auxData[mes]){
                auxData[mes] = 0;
            }
            auxData[mes] += Number(data[i].valor);
        }
        var dataSource = [];
        $.each(auxData, function( index, value ) {
            var mes = index;
            dataSource.push({mes: mes, valor: value});
        });

        $("#torta-ingresos").dxChart({
            dataSource: dataSource,
            series: {
                argumentField: "mes",
                valueField: "valor",
                name: "Ingresos",
                type: "bar",
                color: '#ffa500'
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
            title: "Ingresos totales (por mes)"
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

            var variedad = data[i].variedad;
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

        console.log(dataSource);

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
            title: "Entregas totales (en gramos)",
            tooltip: {
                enabled: true,
                customizeText: function () {
                    return this.seriesName + ": " + this.valueText + " gr.";
                }
            }
        });
    }
}



$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_lista_estadisticas');
    Estadisticas.LoadListaGastos();
    Estadisticas.LoadPagos();
    Estadisticas.LoadEntregas();

});