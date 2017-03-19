
var ListaEntregas = {
    SelectedGenetica:null,
    Geneticas:null,
    Entregas:null,
    LoadGeneticas: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_geneticas" }
        }).done(function (data) {
            if (data && !data.error) {
                $('.tabla-geneticas').html('');
                ListaEntregas.Geneticas = {};
                $('#macroentrega_variedades').html("");
                for(var i=0;i<data.length;i++){
                    ListaEntregas.Geneticas[data[i].id] = data[i];

                    //load macroentregas_select
                    $('#macroentrega_variedades').append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');

                    $('.tabla-geneticas').append('<tr><td>' + data[i].nombre + '</td>' +
                        '<td>' + data[i].origen + '</td>' +
                        '<td>' + data[i].detalles + '</td>' +
                        '<td id="listaGeneticasProducido_' + data[i].id + '"></td>' +
                        '<td><a href="#" onclick="ListaEntregas.OpenModalModificarGenetica(\'' + data[i].id + '\');return false;">modificar</a></td>' +
                        '<td><a href="#" onclick="ListaEntregas.BorrarGenetica(\'' + data[i].id + '\');return false;">borrar</a></td></tr>');
                    ListaEntregas.LoadProduccionGenetica(data[i].id);

                }
                if(ListaEntregas.Entregas == null){
                    ListaEntregas.LoadEntregas();
                }
            }
            Toolbox.StopLoader();
        });
    },
    LoadProduccionGenetica: function(id){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_producido_por_genetica", id:id }
        }).done(function (data) {
            if (data && !data.error) {
                $('#listaGeneticasProducido_' + id).html(data);
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
                $('#listaEntregasTabla').html('');
                ListaEntregas.Entregas = {};
                for(var i=0;i<data.length;i++){
                    ListaEntregas.Entregas[data[i].id] = data[i];
                    var genetica = ListaEntregas.Geneticas[data[i].id_genetica];
                    if(genetica){
                        genetica = genetica.nombre;
                    }else{
                        genetica = "";
                    }
                    $('#listaEntregasTabla').append('<tr><td>' + Toolbox.MysqlDateToDate(data[i].fecha) + '</td>' +
                        '<td>' + data[i].gramos + '</td>' +
                        '<td>' + genetica + '</td>' +
                        '<td>' + data[i].notas + '</td></tr>');
                }
            }
            Toolbox.StopLoader();
        });
    },
    OpenModalNuevaGenetica: function(){
        ListaEntregas.SelectedGenetica = null;
        $('#geneticaDatosModalLabel').html('Agregar Gen&eacute;tica');
        $('.genetica_datos_nombre').val('');
        $('.genetica_datos_origen').val('');
        $('.genetica_datos_detalles').val('');
        $('#geneticaDatosModalBtnSalvar').off('click');
        $('#geneticaDatosModalBtnSalvar').on('click',ListaEntregas.SalvarNuevaGenetica);
        $('#geneticaDatosModal').modal('show');
    },
    OpenModalModificarGenetica: function(id){
        ListaEntregas.SelectedGenetica = ListaEntregas.Geneticas[id];
        $('#geneticaDatosModalLabel').html('Modificar Gen&eacute;tica');
        $('.genetica_datos_nombre').val(ListaEntregas.SelectedGenetica.nombre);
        $('.genetica_datos_origen').val(ListaEntregas.SelectedGenetica.origen);
        $('.genetica_datos_detalles').val(ListaEntregas.SelectedGenetica.detalles);
        $('#geneticaDatosModalBtnSalvar').off('click');
        $('#geneticaDatosModalBtnSalvar').on('click',ListaEntregas.ModificarGenetica);
        $('#geneticaDatosModal').modal('show');
    },
    ModificarGenetica: function(){
        if(ListaEntregas.VerifyGeneticaData()){

            Toolbox.ShowLoaderModal();

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "update_genetica", id:ListaEntregas.SelectedGenetica.id,
                    nombre:$('.genetica_datos_nombre').val(),
                    origen:$('.genetica_datos_origen').val(),
                    detalles:$('.genetica_datos_detalles').val()
                }
            }).done(function (data) {
                if (data && !data.error) {
                    ListaEntregas.LoadGeneticas();
                    $('#geneticaDatosModal').modal('hide');
                    Toolbox.ShowFeedback('feedbackContainer', 'success', "Gen&eacute;tica modificada con exito");
                }else{
                    if(data && data.error){
                        Toolbox.ShowFeedback('geneticaDatosModalFeedback', 'error', data.error);
                    }else{
                        Toolbox.ShowFeedback('geneticaDatosModalFeedback', 'error', 'No se pudo modificar');
                    }
                }
                Toolbox.StopLoaderModal();
            });
        }
    },
    VerifyGeneticaData: function(){
        if($('.genetica_datos_nombre').val()==""){
            Toolbox.ShowFeedback('geneticaDatosModalFeedback', 'error', 'El nombre no puede estar vacio');
            return false;
        }
        return true;
    },
    SalvarNuevaGenetica: function(){
        if(ListaEntregas.VerifyGeneticaData()){

            Toolbox.ShowLoaderModal();

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "ingresar_genetica",
                    nombre:$('.genetica_datos_nombre').val(),
                    origen:$('.genetica_datos_origen').val(),
                    detalles:$('.genetica_datos_detalles').val()
                }
            }).done(function (data) {
                if (data && !data.error) {
                    ListaEntregas.LoadGeneticas();
                    $('#geneticaDatosModal').modal('hide');
                    Toolbox.ShowFeedback('feedbackContainer', 'success', "Gen&eacute;tica creada con exito");
                }else{
                    if(data && data.error){
                        Toolbox.ShowFeedback('geneticaDatosModalFeedback', 'error', data.error);
                    }else{
                        Toolbox.ShowFeedback('geneticaDatosModalFeedback', 'error', 'No se pudo crear');
                    }
                }
                Toolbox.StopLoaderModal();
            });
        }
    },
    BorrarGenetica: function(id){
        if(confirm("Esta seguro que desea borrar esta genetica?")){

            Toolbox.ShowLoader();

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "delete_genetica", id:id}
            }).done(function (data) {
                if (data && !data.error) {
                    ListaEntregas.LoadGeneticas();
                    Toolbox.ShowFeedback('feedbackContainer', 'success', "Gen&eacute;tica borrada con exito");
                }else{
                    if(data && data.error){
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    }else{
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'No se pudo borrar');
                    }
                }
                Toolbox.StopLoader();
            });
        }
    },
    OpenModalMacroEntrega: function(){
        $('#macroEntregaModal').modal('show');
    },
    VerificarMacroEntrega: function(){
        if(confirm("Esta seguro que desea agregar esta entrega a todos los socios seleccionados?")){
            if($('.macroentrega_peso').val()==""){
                Toolbox.ShowFeedback('macroEntregaModalFeedback', 'error', 'El peso no puede quedar vac&iacute;o');
                return false;
            }else if(isNaN($('.macroentrega_peso').val())){
                Toolbox.ShowFeedback('macroEntregaModalFeedback', 'error', 'El peso es incorrecto');
                return false;
            }
            return true;
        }else{
            return false;
        }
    },
    AgregarMacroEntrega: function(){
        if(ListaEntregas.VerificarMacroEntrega()){

            var listaSocioIds = [];
            $('.macroentrega_socio_chk').each(function() {
                if ($(this).prop("checked") == true) {
                    if($(this).attr('id') != 'macroentrega_socio_todos'){
                        listaSocioIds.push($(this).attr('id').substring(19));
                    }
                }
            });
            for(var i=0;i<listaSocioIds.length;i++){
                (function(index){
                    Toolbox.ShowLoaderModal();
                    $.ajax({
                        dataType: 'json',
                        type: "POST",
                        url: "proc/controller.php",
                        data: { func: "ingresar_entrega", id_socio: listaSocioIds[index], gramos: $('.macroentrega_peso').val(),
                            fecha: Toolbox.DataToMysqlDate($(".macroentrega_fecha").val()),
                            variedad: $("#macroentrega_variedades").val(), notas: ""}
                    }).done(function (data) {
                        if(index == listaSocioIds.length-1){
                            ListaEntregas.LoadEntregas();
                            $('#macroEntregaModal').modal('hide');
                            Toolbox.ShowFeedback('feedbackContainer', 'success', 'Macro entrega agregada con exito');
                        }
                        Toolbox.StopLoaderModal();
                    });
                })(i);
            }
        }
    },
    LoadSocios: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_socios_activos" }
        }).done(function (data) {
            if (data && !data.error) {
                $('#macroentrega_tabla_socios').html('<tr><td class="right"><input onchange="ListaEntregas.MacroEntregaSeleccionarTodosChanged();" type="checkbox" class="macroentrega_socio_chk" id="macroentrega_socio_todos">' +
                    '<label class="macroentrega_socio_label" for="macroentrega_socio_todos"><b>Seleccionar todos</b></label></td></tr>');
                var html = "";
                var countCols = 1;
                for(var i=0;i<data.length;i++){

                    if(countCols == 1){
                        html += '<tr><td class="right">';
                    }else{
                        html += '<td class="left">';
                    }

                    html += '<input type="checkbox" class="macroentrega_socio_chk" id="macroentrega_socio_' + data[i].id + '">' +
                        '<label class="macroentrega_socio_label" for="macroentrega_socio_' + data[i].id + '">' + data[i].nombre + '</label></td>';

                    if(countCols == 2){
                        html += '</tr>';
                        $('#macroentrega_tabla_socios').append(html);
                        html = "";
                        countCols = 0;
                    }else if(i == data.length-1){
                        html += '<td></td></tr>';
                        $('#macroentrega_tabla_socios').append(html);
                    }

                    countCols += 1;
                }
            } else {
                if (data && data.error) {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                } else {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', 'No se pudieron obtener los socios');
                }
            }
            Toolbox.StopLoader();
        });
    },
    MacroEntregaSeleccionarTodosChanged: function(){
        if($('#macroentrega_socio_todos').prop("checked") == true){
            $('.macroentrega_socio_chk').prop("checked",true);
        }else{
            $('.macroentrega_socio_chk').prop("checked",false);
        }
    }
}

$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_lista_entregas');
    $(".macroentrega_fecha").mask("99/99/9999");

    ListaEntregas.LoadGeneticas();
    ListaEntregas.LoadSocios();
});
