var Informes = {
    InformesCosechaData:null,
    GeneticasData:null,
    OpenModalNuevoInformeCosecha: function(){
        $('#informeCosechaModalFeedback').css('display','none');
        $('.informeCosechaFecha').val('');
        $('.informeCosechaGenetica').val('');
        $('.informeCosechaCantidadPlantas').val('');
        $('.informeCosechaPesoFresco').val('');
        $('.informeCosechaPesoSeco').val('');
        $('.informeCosechaLote').val('');
        if(Informes.InformesCosechaData && Object.keys(Informes.InformesCosechaData).length > 0){
            var last = Informes.InformesCosechaData[Object.keys(Informes.InformesCosechaData)[Object.keys(Informes.InformesCosechaData).length - 1]]
            $('.informeCosechaResponsableTecnico').val(last.responsable_tecnico);
            $('.informeCosechaResponsableProduccion').val(last.responsable_produccion);
        }else{
            $('.informeCosechaResponsableTecnico').val('');
            $('.informeCosechaResponsableProduccion').val('');
        }
        $('.informeCosechaAclaraciones').val('');
        $('#informeCosechaModal').modal('show');
    },
    OpenModalExportar: function(){
        $('#exportarInformeCosechaModalFeedback').css('display','none');
        $('#exportarInformeCosechaModal').modal('show');
    },
    ExportInformeCosecha: function(){

        var arrayIds = [];
        var mes = $('.exportarInformeCosechaMes').val();
        var anio = $('.exportarInformeCosechaAnio').val();

        $.each(Informes.InformesCosechaData, function (index, value) {
            if(value.fecha.indexOf(anio + '-' + mes + '-') == 0){
                arrayIds.push(index);
            }
        });

        if(arrayIds.length > 0){
            var stringIds = '';
            for(var i=0;i<arrayIds.length;i++){
                if(i > 0){
                    stringIds += ",";
                }
                stringIds += arrayIds[i];
            }
            window.open("proc/controller.php?exportar=exportar_informe_cosecha&ids=" + stringIds + '&periodo=' + mes + '\/' + anio, '_blank');
            //$('#exportarInformeCosechaModal').modal('hide');
        }else{
            Toolbox.ShowFeedback('exportarInformeCosechaModalFeedback', 'error', "No hay informes en ese per&iacute;odo.");
        }
    },
    SalvarNuevoInformeCosecha: function(){
        if(Informes.VerificarNuevoInformeCosecha()){
            Toolbox.ShowLoader();

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "ingresar_informe_cosecha",
                    fecha:Toolbox.DataToMysqlDate($('.informeCosechaFecha').val()),
                    id_genetica:$('.informeCosechaGenetica').val(),
                    cantidad_plantas:$('.informeCosechaCantidadPlantas').val(),
                    peso_total_fresco:$('.informeCosechaPesoFresco').val(),
                    peso_total_seco:$('.informeCosechaPesoSeco').val(),
                    lote:$('.informeCosechaLote').val(),
                    responsable_tecnico:$('.informeCosechaResponsableTecnico').val(),
                    responsable_produccion:$('.informeCosechaResponsableProduccion').val(),
                    aclaraciones:$('.informeCosechaAclaraciones').val()}
            }).done(function (data) {
                if (data && !data.error) {
                    Informes.GetInformesCosecha();
                    $('#informeCosechaModal').modal('hide');
                    Toolbox.ShowFeedback('feedbackContainer', 'success', "Informe agregado con exito");
                }else{
                    if(data && data.error){
                        Toolbox.ShowFeedback('informeCosechaModalFeedback', 'error', data.error);
                    }else{
                        Toolbox.ShowFeedback('informeCosechaModalFeedback', 'error', 'No se pudo crear el informe');
                    }
                }
                Toolbox.StopLoader();
            });
        }
    },
    VerificarNuevoInformeCosecha: function(){
        if($('.informeCosechaFecha').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar una fecha.');
            return false;
        }
        if($('.informeCosechaGenetica').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar una gen&eacute;tica.');
            return false;
        }
        if($('.informeCosechaCantidadPlantas').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar la cantidad de plantas cosechadas.');
            return false;
        }
        if(isNaN($('.informeCosechaCantidadPlantas').val())){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','La cantidad de plantas especificada no es correcta.');
            return false;
        }
        if($('.informeCosechaPesoFresco').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar el peso total fresco.');
            return false;
        }
        if(isNaN($('.informeCosechaPesoFresco').val())){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','El peso total fresco especificado no es correcto.');
            return false;
        }
        if($('.informeCosechaPesoSeco').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar el peso total seco.');
            return false;
        }
        if(isNaN($('.informeCosechaPesoSeco').val())){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','El peso total seco especificado no es correcto.');
            return false;
        }
        if($('.informeCosechaLote').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar un identificador del lote.');
            return false;
        }
        if($('.informeCosechaResponsableTecnico').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar el nombre del responsable t&eacute;cnico.');
            return false;
        }
        if($('.informeCosechaResponsableProduccion').val() == ''){
            Toolbox.ShowFeedback('informeCosechaModalFeedback','error','Tiene que especificar el nombre del responsable de producci&oacute;n.');
            return false;
        }
        return true;
    },
    BorrarInformeCosecha: function(id){
        if(confirm("Esta seguro que desea borrar este informe?")){

            Toolbox.ShowLoader();

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "borrar_informe_cosecha", id:id}
            }).done(function (data) {
                if (data && !data.error) {
                    Informes.GetInformesCosecha();
                    Toolbox.ShowFeedback('feedbackContainer', 'success', "Informe borrado con exito");
                }else{
                    if(data && data.error){
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    }else{
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'No se pudo borrar el informe');
                    }
                }
                Toolbox.StopLoader();
            });
        }
    },
    GetInformesCosecha: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_informes_cosecha" }
        }).done(function (data) {
            if (data && !data.error) {

                Informes.InformesCosechaData = {};
                $('.tabla-informes-cosecha').html("");
                for(var i=0;i<data.length;i++){
                    Informes.InformesCosechaData[data[i].id] = data[i];
                    $('.tabla-informes-cosecha').append('<tr><td>' + Toolbox.MysqlDateToDate(data[i].fecha) + '</td>' +
                        '<td>' + Informes.GeneticasData[data[i].id_genetica].nombre + '</td>' +
                        '<td>' + data[i].lote + '</td>' +
                        '<td>' + data[i].peso_total_seco + '</td>' +
                        '<td>' + data[i].responsable_tecnico + '</td>' +
                        '<td>' + data[i].responsable_produccion + '</td>' +
                        '<td>' + data[i].aclaraciones + '</td>' +
                        '<td><a href="#" onclick="Informes.BorrarInformeCosecha(\'' + data[i].id + '\');return false;">borrar</a></td></tr>');
                }
            }
            Toolbox.StopLoader();
        });
    },
    GetGeneticas: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_geneticas" }
        }).done(function (data) {
            if (data && !data.error) {

                Informes.GeneticasData = {};
                $('.informeCosechaGenetica').html('<option value=""> - seleccionar - </option>');
                for(var i=0;i<data.length;i++){
                    Informes.GeneticasData[data[i].id] = data[i];
                    $('.informeCosechaGenetica').append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
            }
            Informes.GetInformesCosecha();
            Toolbox.StopLoader();
        });
    }
}

$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_informes');
    var params = Toolbox.GetUrlVars();

    $(".informeCosechaFecha").mask("99/99/9999");

    Informes.GetGeneticas();

});