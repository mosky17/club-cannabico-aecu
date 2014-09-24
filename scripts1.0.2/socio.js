var Socio = {
    Editing: false,
    New: false,
    IdSocio: null,
    Tags: {},
    SocioData: {},
    GetTags: function () {
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_tags" }
        }).done(function (data) {
                if (data && !data.error) {

                    for (var i = 0; i < data.length; i++) {
                        Socio.Tags[data[i].id] = data[i];
                    }

                    if (Socio.New) {
                        Socio.LoadNewForm();
                    } else if (Socio.IdSocio) {
                        Socio.LoadSocio();
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
    LoadNewForm: function () {

        $('#socioNombreTitulo').html('Nuevo Socio');
        $("#socioBtnSalvarContainer").css('display', 'block');
        $('#socioDatosValorNumero').html('<input id="socioNuevoNumero" type="text">');
        $('#socioDatosValorEmail').html('<input id="socioNuevoEmail" type="text">');
        $('#socioDatosValorDocumento').html('<input id="socioNuevoDocumento" type="text">');
        $('#socioDatosValorNombre').html('<input id="socioNuevoNombre" type="text">');
        $('#socioDatosValorTelefono').html('<input id="socioNuevoTelefono" type="text">');
        $('#socioDatosValorFechaInicio').html('<input id="socioNuevoFechaInicio"  placeholder="01/12/2013" type="text">');
        $('#socioDatosValorTags').html('');
        $.each(Socio.Tags, function (index, value) {
            $('#socioDatosValorTags').append('<label><input type="checkbox" id="socioNuevoTagChk_' + value.id + '" class="socioNuevoTagChk" name="' + value.id + '"/>' + value.nombre + '</label>');
        });
        $('#socioDatosValorObservaciones').html('<textarea id="socioNuevoObservaciones"></textarea>');
        $('#socioDatosFieldNombre').css('display', 'block');
        $("#socioNuevoFechaInicio").mask("99/99/9999");
    },
    SalvarSocio: function () {

        if (Socio.VerificarDatosSocio()) {

            var tags = new Array();
            $(".socioNuevoTagChk:checked").each(function () {
                tags.push($(this).attr('name'));
            });

            var postData;
            if (Socio.Editing) {
                postData = { func: "update_socio", id: Socio.IdSocio, numero: $('#socioNuevoNumero').val(), nombre: $('#socioNuevoNombre').val(), documento: $('#socioNuevoDocumento').val(),
                    email: $('#socioNuevoEmail').val(), telefono: $('#socioNuevoTelefono').val(), fecha_inicio: Toolbox.DataToMysqlDate($('#socioNuevoFechaInicio').val()), tags: tags,
                    observaciones: $('#socioNuevoObservaciones').val()};
            } else {
                postData = { func: "create_socio", id: Socio.IdSocio, numero: $('#socioNuevoNumero').val(), nombre: $('#socioNuevoNombre').val(), documento: $('#socioNuevoDocumento').val(),
                    email: $('#socioNuevoEmail').val(), telefono: $('#socioNuevoTelefono').val(), fecha_inicio: Toolbox.DataToMysqlDate($('#socioNuevoFechaInicio').val()), tags: tags,
                    observaciones: $('#socioNuevoObservaciones').val()};
            }

            Toolbox.ShowLoader();

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: postData
            }).done(function (data) {
                    if (data && !data.error) {
                        //console.log(data);
                        document.location.href = "socio.php?id=" + data;
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
    VerificarDatosSocio: function () {

        var error = undefined;

        if (Socio.New) {
            if (!error && $('#socioNuevoNombre').val() == '') {
                error = 'Falt&oacute; especificar nombre de socio';
            } else if (!error && $('#socioNuevoNumero').val() == '') {
                error = 'Falto especificar numero de socio';
            } else if (!error && isNaN($('#socioNuevoNumero').val())) {
                error = 'Numero de socio invalido';
            }
            if (!error && $('#socioNuevoEmail').val() == '') {
                error = 'Falto especificar un email';
            }
            if (!error && $('#socioNuevoNombre').val() == '') {
                error = 'Falto especificar el nombre del socio';
            }
            if (!error && $('#socioNuevoFechaInicio').val() == '') {
                error = 'Falto especificar fecha de inicio';
            }
        }

        if (error == undefined) {
            Toolbox.ShowFeedback('feedbackContainer', '', '');
        } else {
            Toolbox.ShowFeedback('feedbackContainer', 'error', error);
        }

        return error == undefined;
    },
    LoadSocio: function () {

        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_socio", id: Socio.IdSocio }
        }).done(function (data) {
                if (data && !data.error) {
                    Socio.SocioData = data;

                    $('#socioLabelEstado').removeClass('labelEstadoActivo');
                    $('#socioLabelEstado').removeClass('labelEstadoSuspendido');
                    if(data.activo==true){
                        $('#socioLabelEstado').addClass('labelEstadoActivo');
                        $('#socioLabelEstado').html("Activo");
                    }else{
                        $('#socioLabelEstado').addClass('labelEstadoSuspendido');
                        $('#socioLabelEstado').html("Suspendido");
                    }

                    $("#socioDatosFieldNombre").css('display', 'none');
                    $("#socioBtnSalvarContainer").css('display', 'none');
                    $("#socioNombreTitulo").html(data.nombre + '<i class="icon-edit socioIconBtnTitle" onClick="Socio.EditSocio();" title="Editar socio"></i>');
                    $("#socioDatosValorNumero").html('<p>' + data.numero + "</p>");
                    $("#socioDatosValorDocumento").html('<p>' + data.documento + "</p>");
                    $("#socioDatosValorEmail").html('<p>' + data.email + "</p>");
                    $("#socioDatosValorFechaInicio").html('<p>' + Toolbox.MysqlDateToDate(data.fecha_inicio) + "</p>");
                    $("#socioDatosValorTelefono").html('<p>' + data.telefono + "</p>");
                    if (data.observaciones) {
                        $("#socioDatosValorObservaciones").html('<p>' + data.observaciones + "</p>");
                    }
                    //tags
                    var tagsHtml = "<div style='padding: 10px 0 0;'>";
                    for (var j = 0; j < data.tags.length; j++) {
                        if (data.tags[j] && data.tags[j] != '' && Socio.Tags[data.tags[j]]) {
                            tagsHtml += '<span class="label socioTag" style="background-color:' + Socio.Tags[data.tags[j]].color + '">' + Socio.Tags[data.tags[j]].nombre + '</span>';
                        }
                    }
                    $("#socioDatosValorTags").html(tagsHtml + "</div>");


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
    EditSocio: function () {
        Socio.Editing = true;
        $('#socioNombreTitulo').html(Socio.SocioData.nombre);
        $("#socioDatosFieldNombre").css('display', 'block');
        $("#socioBtnSalvarContainer").css('display', 'block');
        $('#socioDatosValorNumero').html('<input id="socioNuevoNumero" type="text" value="' + Socio.SocioData.numero + '">');
        $('#socioDatosValorEmail').html('<input id="socioNuevoEmail" type="text" value="' + Socio.SocioData.email + '">');
        $('#socioDatosValorDocumento').html('<input id="socioNuevoDocumento" type="text" value="' + Socio.SocioData.documento + '">');
        $('#socioDatosValorNombre').html('<input id="socioNuevoNombre" type="text" value="' + Socio.SocioData.nombre + '">');
        $('#socioDatosValorTelefono').html('<input id="socioNuevoTelefono" type="text" value="' + Socio.SocioData.telefono + '">');
        $('#socioDatosValorFechaInicio').html('<input id="socioNuevoFechaInicio" type="text"  placeholder="01/12/2013" value="' + Toolbox.MysqlDateToDate(Socio.SocioData.fecha_inicio) + '">');
        $('#socioDatosValorTags').html('');
        $.each(Socio.Tags, function (index, value) {
            $('#socioDatosValorTags').append('<label><input type="checkbox" id="socioNuevoTagChk_' + value.id + '" class="socioNuevoTagChk" name="' + value.id + '"/>' + value.nombre + '</label>');
        });
        $.each(Socio.SocioData.tags, function (index, value) {
            $('#socioNuevoTagChk_' + value).attr('checked', 'checked');
        });
        $('#socioDatosValorObservaciones').html('<textarea id="socioNuevoObservaciones">' + Socio.SocioData.observaciones + '</textarea>');
        $("#socioNuevoFechaInicio").mask("99/99/9999");

    },
    OpenModalNuevoPago: function(){

        $('.feedbackContainerModal').css('display', 'none');
        $('#socioIngresarPagoValor').val('');
        $('#socioIngresarPagoNotas').val('');
        $('#socioIngresarPagoFecha').val(Toolbox.GetFechaHoyLocal());
        $('#socioIngresarPagoModal').modal("show");
    },
    IngresarPago: function () {
        if (Socio.VerificarDatosPago()) {

            var razonPago = $("#socioIngresarPagoRazon").val();
            if($("#socioIngresarPagoRazon").val() == "mensualidad"){
                razonPago = "mensualidad (" + $('#socioIngresarPagoRazonMensualidadMes').val() + "/" + $('#socioIngresarPagoRazonMensualidadAnio').val() + ")";
            }

            Toolbox.ShowLoaderModal();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "ingresar_pago", id_socio: Socio.IdSocio, valor: $("#socioIngresarPagoValor").val(),
                    fecha_pago: Toolbox.DataToMysqlDate($("#socioIngresarPagoFecha").val()),
                    razon: razonPago, notas: $("#socioIngresarPagoNotas").val(),
                    tipo: $("#socioIngresarPagoTipo").val() }
            }).done(function (data) {
                    if (data && !data.error) {
                        Socio.LoadPagos();
                        $('#socioIngresarPagoModal').modal('hide');
                    } else {
                        if (data && data.error) {
                            Toolbox.ShowFeedback('feedbackContainerModalIngresarPago', 'error', data.error);
                        } else {
                            Toolbox.ShowFeedback('feedbackContainerModalIngresarPago', 'error', 'Unexpected error');
                        }
                    }
                    Toolbox.StopLoaderModal();
                });
        }
    },
    VerificarDatosPago: function () {
        var error = undefined;


        if (!error && $('#socioIngresarPagoValor').val() == '') {
            error = 'Falt&oacute; especificar el valor del pago';
        } else if (!error && $('#socioIngresarPagoFecha').val() == '') {
            error = 'Falto especificar fecha de pago';
        } else if (!error && isNaN($('#socioIngresarPagoValor').val())) {
            error = 'Valor invalido';
        }

        if (error == undefined) {
            Toolbox.ShowFeedback('feedbackContainerModalIngresarPago', '', '');
        }
        else {
            Toolbox.ShowFeedback('feedbackContainerModalIngresarPago', 'error', error);
        }

        return error == undefined;
    },
    LoadPagos: function () {
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_pagos_socio", id_socio: Socio.IdSocio }
        }).done(function (data) {
                if (data && !data.error) {

                    $('#listaPagosSocioTabla').html("");
                    for (var i = 0; i < data.length; i++) {

                        $('#listaPagosSocioTabla').append('<tr onClick="document.location.href = \'/pago.php?id=' + data[i].id + '\'"><td>' + data[i].id + '</td>' +
                            '<td>' + data[i].valor + '</td>' +
                            '<td>' + Toolbox.MysqlDateToDate(data[i].fecha_pago) + '</td>' +
                            '<td>' + Toolbox.TransformSpecialTag(data[i].razon) + '</td>' +
                            '<td>' + data[i].notas + '</td>' +
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
    CambiarEstadoSocio: function(){

        var nuevoEstado;
        var confirmacion = false;
        if($('#socioEditarEstado').val() == 'activo'){
            if(Socio.SocioData.activo==true){
                $('#socioCambiarEstadoModal').modal('hide');
                return;
            }else{
                nuevoEstado = true;
            }
        }else if($('#socioEditarEstado').val() == 'suspendido'){
            if(Socio.SocioData.activo!=true){
                $('#socioCambiarEstadoModal').modal('hide');
                return;
            }else{
                nuevoEstado = false;
            }
        }else if($('#socioEditarEstado').val() == 'eliminar'){
            confirmacion = confirm('Eliminar socio permanentemente?');
        }

        if($('#socioEditarEstado').val() == 'eliminar'){
            if(confirmacion){
                Toolbox.ShowLoader();
                $.ajax({
                    dataType: 'json',
                    type: "POST",
                    url: "proc/controller.php",
                    data: { func: "eliminar_socio", id_socio: Socio.IdSocio }
                }).done(function (data) {
                        if (data && !data.error) {
                            document.location.href = "/";
                        } else {
                            if (data && data.error) {
                                Toolbox.ShowFeedback('feedbackContainerModalCambiarEstado', 'error', data.error);
                            } else {
                                Toolbox.ShowFeedback('feedbackContainerModalCambiarEstado', 'error', 'Unexpected error');
                            }
                        }
                        Toolbox.StopLoader();
                    });
            }
        }else{
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "update_estado_socio", id_socio: Socio.IdSocio, activo: nuevoEstado }
            }).done(function (data) {
                    if (data && !data.error) {
                        Socio.LoadSocio();
                        $('#socioCambiarEstadoModal').modal('hide');
                    } else {
                        if (data && data.error) {
                            Toolbox.ShowFeedback('feedbackContainerModalCambiarEstado', 'error', data.error);
                        } else {
                            Toolbox.ShowFeedback('feedbackContainerModalCambiarEstado', 'error', 'Unexpected error');
                        }
                    }
                    Toolbox.StopLoader();
                });
        }
    },
    OpenModalNuevaEntrega: function(){
        $('.feedbackContainerModal').css('display', 'none');
        $('#socioIngresarEntregaGramos').val('');
        $('#socioIngresarEntregaFecha').val(Toolbox.GetFechaHoyLocal());
        $('#socioIngresarEntregaVariedad').val('');
        $('#socioIngresarEntregaNotas').val('');
        $('.loaderModal').css('display','none');
        $('#socioIngresarEntregaModal').modal('show');
    },
    IngresarEntrega: function(){
        if(Socio.VerificarNuevaEntrega()){
            Toolbox.ShowLoaderModal();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: { func: "ingresar_entrega", id_socio: Socio.IdSocio, gramos: $("#socioIngresarEntregaGramos").val(),
                    fecha: Toolbox.DataToMysqlDate($("#socioIngresarEntregaFecha").val()),
                    variedad: $("#socioIngresarEntregaVariedad").val(), notas: $("#socioIngresarEntregaNotas").val()}
            }).done(function (data) {
                    if (data && !data.error) {
                        Socio.LoadEntregas();
                        $('#socioIngresarEntregaModal').modal('hide');
                    } else {
                        if (data && data.error) {
                            Toolbox.ShowFeedback('socioIngresarEntregaModalFeedback', 'error', data.error);
                        } else {
                            Toolbox.ShowFeedback('socioIngresarEntregaModalFeedback', 'error', 'Error Inesperado');
                        }
                    }
                    Toolbox.StopLoaderModal();
                });
        }
    },
    VerificarNuevaEntrega: function(){
        var error = undefined;


        if (!error && $('#socioIngresarEntregaGramos').val() == '') {
            error = 'Falt&oacute; especificar la cantidad de gramos';
        } else if (!error && $('#socioIngresarEntregaFecha').val() == '') {
            error = 'Falto especificar fecha de entrega';
        } else if (!error && isNaN($('#socioIngresarEntregaGramos').val())) {
            error = 'Gramos invalidos';
        }else if (!error && $('#socioIngresarEntregaVariedad').val() == '') {
            error = 'Falto especificar la variedad';
        }

        if (error == undefined) {
            Toolbox.ShowFeedback('socioIngresarEntregaModalFeedback', '', '');
        }
        else {
            Toolbox.ShowFeedback('socioIngresarEntregaModalFeedback', 'error', error);
        }

        return error == undefined;
    },
    LoadEntregas: function(){
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "get_entregas_socio", id_socio: Socio.IdSocio }
        }).done(function (data) {
                if (data && !data.error) {

                    $('#listaEntregasSocioTabla').html("");
                    for (var i = 0; i < data.length; i++) {

                        $('#listaEntregasSocioTabla').append('<tr onClick=""><td>' + data[i].gramos + '</td>' +
                            '<td>' + Toolbox.MysqlDateToDate(data[i].fecha) + '</td>' +
                            '<td>' + Toolbox.TransformSpecialTag(data[i].variedad) + '</td>' +
                            '<td>' + data[i].notas + '</td></tr>');
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
    TogglePagoRazon: function(){
        if($('#socioIngresarPagoRazon').val() == "mensualidad"){
            $('#socioIngresarPagoRazonMensualidadMes').css('display','block');
            $('#socioIngresarPagoRazonMensualidadAnio').css('display','block');
        }else{
            $('#socioIngresarPagoRazonMensualidadMes').css('display','none');
            $('#socioIngresarPagoRazonMensualidadAnio').css('display','none');
        }
    }
}

$(document).ready(function () {

    $('#socioBtnSalvar').on('click', Socio.SalvarSocio);

    Toolbox.UpdateActiveNavbar('');
    var params = Toolbox.GetUrlVars();

    if (params['new'] && params['new'] == 'true') {
        Socio.New = true;
    } else if (params['id']) {
        Socio.IdSocio = params['id'];
    }
    Socio.GetTags();


    $('#socioIngresarPagoModalBtnIngresar').on('click', Socio.IngresarPago);
    $("#socioIngresarPagoFecha").mask("99/99/9999");
    $('#socioLabelEstado').on('click', function () {
        $('.feedbackContainerModal').css('display', 'none');
        $('#socioIngresarPagoValor').val('');
        $('#socioIngresarPagoFecha').val(Toolbox.GetFechaHoyLocal());
        $('#socioIngresarPagoTipo').val('');
        $('#socioIngresarPagoRazon').val('');
        $('#socioIngresarPagoNotas').val('');
        $('.loaderModal').css('display','none');
        $('#socioCambiarEstadoModal').modal({
            show: true
        });
    });
    $('#socioCambiarEstadoModalBtnCambiar').on('click',function(){
        Socio.CambiarEstadoSocio();
    });

    Socio.LoadPagos();
    Socio.LoadEntregas();


});
