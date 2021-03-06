var Index = {
    Tags: {},
    GetTags: function () {

        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_tags" }
        }).done(function (data) {
                Index.Tags = {};
                if (data && !data.error) {

                    var htmlAgregar = "";
                    for (var i = 0; i < data.length; i++) {
                        Index.Tags[data[i].id] = data[i];

                        //armar tabla checkboxes del armar lista mails
                        if(i==0){
                            htmlAgregar = '<tr><td><input id="lista_enviar_a_todos" type="checkbox" />' +
                                '<label style="position: relative; top: 2px;" for="lista_enviar_a_todos">Todos</label></td>';

                        }
                        if(i != 0 && i%2 != 0){
                            htmlAgregar += "</tr><tr>"
                        }
                        htmlAgregar += '<td><input id="lista_enviar_a_' + data[i].id + '" class="checkboxEnviarGrupo" name="' + data[i].id + '" type="checkbox" />' +
                            '<label for="lista_enviar_a_' + data[i].id + '"><span class="label socioTag" style="background-color:' + data[i].color + '">' + data[i].nombre + '</span></label></td>';
                    }
                    htmlAgregar += "</tr>";
                    $('#socioArmarListaMailsModalGruposTable tbody').html(htmlAgregar);
                    $('#lista_enviar_a_todos').off('change');
                    $('#lista_enviar_a_todos').on('change', function(){
                        if($('#lista_enviar_a_todos').prop("checked") == true){
                            $('.checkboxEnviarGrupo').prop('checked',true);
                            $('.checkboxEnviarGrupo').prop('disabled',true);
                        }else{
                            $('.checkboxEnviarGrupo').prop('checked', false);
                            $('.checkboxEnviarGrupo').prop('disabled',false);
                        }
                    });

                    Index.LoadListaSocios();
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
    SociosActivos: null,
    SociosAMostrar:"activos",
    LoadListaSocios: function () {

        var func = 'get_socios_activos';
        if(Index.SociosAMostrar == 'suspendidos'){
            func = 'get_socios_suspendidos';
        }

        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: func }
        }).done(function (data) {
                if (data && !data.error) {
                    $('#listaSociosTabla').html("");

                    var countSocios = 0;

                    for (var i = 0; i < data.length; i++) {

                        if(Index.SociosAMostrar != 'suspendidos'){
                            if(i==0){
                                Index.SociosActivos = [];
                            }
                            Index.SociosActivos.push(data[i]);
                        }

                        //if(data[i].activo == true){

                            countSocios += 1;

                            var tagsHtml = "";
                            if(data[i].activo != true){
                                tagsHtml += '<span class="label label-important" style="margin-right: 3px;">SUSPENDIDO</span>';
                            }
                            for (var j = 0; j < data[i].tags.length; j++) {
                                if (data[i].tags[j] && data[i].tags[j] != '' && Index.Tags[data[i].tags[j]]) {
                                    tagsHtml += '<span class="label socioTag" style="background-color:' + Index.Tags[data[i].tags[j]].color + '">' + Index.Tags[data[i].tags[j]].nombre + '</span>';
                                }
                            }

                            $('#listaSociosTabla').append('<tr onClick="document.location.href = \'socio.php?id=' + data[i].id + '\';"><td>' + data[i].numero + '</td>' +
                                '<td>' + data[i].nombre + '</td>' +
                                '<td>' + data[i].email + '</td>' +
                                '<td>' + Toolbox.MysqlDateToDate(data[i].fecha_inicio) + '</td>' +
                                '<td>' + tagsHtml + '</td></tr>');
                        //}
                    }

                    $('#totalRegistrosSocios').html("Total Registros: " + countSocios);

                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'Unexpected error');
                    }
                }
            Index.CheckWarnings();
                Toolbox.StopLoader();
            });
    },
    CheckWarnings: function(){
        if(Index.SociosActivos && Index.SociosActivos.length > 0){

            var warningEmails = false;
            var warningFechanac = false;
            var warningDocus = false;
            var emails = [];

            for(var i=0;i<Index.SociosActivos.length;i++){
                if(!Index.SociosActivos[i].documento || Index.SociosActivos[i].documento == ""){
                    warningDocus = true;
                }
                if(!Index.SociosActivos[i].fecha_nacimiento || Index.SociosActivos[i].fecha_nacimiento == "" || Index.SociosActivos[i].fecha_nacimiento == "0000-00-00"){
                    warningFechanac = true;
                }
                if(!Index.SociosActivos[i].email || Index.SociosActivos[i].email == ""){
                    warningEmails = true;
                }else{
                    for(var j=0;i<emails.length;j++){
                        if(emails[j] == Index.SociosActivos[i].email){
                            warningEmails = true;
                        }
                    }
                    emails.push(Index.SociosActivos[i].email);
                }
            }

            if(warningEmails){
                $('#index-warning-emails').css('display','block');
            }
            if(warningFechanac){
                $('#index-warning-fechanac').css('display','block');
            }
            if(warningDocus){
                $('#index-warning-doc').css('display','block');
            }
        }
    },
    ImportarSocioAecu: function () {

        Toolbox.ShowLoaderModal();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "importar_socio_aecu", numero: $('#listaSociosModalImportarNumero').val() }
        }).done(function (data) {
                if (data && !data.error) {
                    Toolbox.ShowFeedback('feedbackContainerModalImport', 'success', "Socio importado con exito");
                    Index.LoadListaSocios();
                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainerModalImport', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainerModalImport', 'error', 'Unexpected error');
                    }
                }
                $('#listaSociosModalImportarNumero').val("");
                Toolbox.StopLoaderModal();
            });
    },
    ArmarListaMails: function(){

        var tags = [];
        $('.checkboxEnviarGrupo').each(function(){
            if(this.checked){
                tags.push($(this).attr("name"));
            }

        });

        Toolbox.ShowLoaderModal();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_lista_mails", tags: tags, all:$('#lista_enviar_a_todos').prop('checked') }
        }).done(function (data) {
                if (data && !data.error) {
                    var lista = "";
                    for (var i = 0; i < data.length; i++) {
                        if(i>0){
                            lista += ";\n";
                        }
                        lista += data[i].email;
                    }
                    $('#socioArmarListaMailsModalLista').val(lista);
                    $('#socioArmarListaMailsModalCommentLista').html("total: " + data.length + " correos");
                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainerModalArmarListaMails', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainerModalArmarListaMails', 'error', 'Unexpected error');
                    }
                }
                Toolbox.StopLoaderModal();
            });
    },
    OpenEnviarEstados: function(){
        $('.feedbackContainerModal').css('display', 'none');
        $('#listaSociosModalEstados').modal('show');
    },
    EnviarEstadosDeCuenta: function(){

        Toolbox.ShowLoaderModal();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "send_estados_de_cuenta", total: $('#listaSociosModalEstadosTotal').val(), texto:$('#listaSociosModalEstadosTexto').val() }
        }).done(function (data) {
                if (data && !data.error) {

                    Toolbox.ShowFeedback('feedbackContainer', 'success', "Estados de cuenta enviados con exito");
                    $('#listaSociosModalEstados').modal('hide');

                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainerModalEstados', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainerModalEstados', 'error', 'Unexpected error');
                    }
                }
                Toolbox.StopLoaderModal();
            });
    },
    CambiarSociosAMostrar: function(){
        Index.SociosAMostrar = $('.lista-socios-show').val();
        Index.LoadListaSocios();
    },
    ExportarListaSociosActivos: function(){
        $("#exportIframe").attr("src","proc/controller.php?exportar=exportar_socios_activos");
    }
}

$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_lista_socios');
    Index.GetTags();
    $('#listaSociosBtnCrearSocio').on('click', function () {
        document.location.href = "socio.php?new=true";
    });
    $('#listaSociosBtnImportarSocio').on('click', function () {

        $('.feedbackContainerModal').css('display', 'none');
        $('#listaSociosModalImport').modal({
            show: true
        });

    });
    $('#listaSociosBtnArmarListaMails').on('click', function () {

        $('.feedbackContainerModal').css('display', 'none');
        $('#socioArmarListaMailsModal').modal({
            show: true
        });

    });
    $('#listaSociosModalImportarBtn').on('click',Index.ImportarSocioAecu);


});
