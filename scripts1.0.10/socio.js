var Socio = {
    Editing: false,
    New: false,
    IdSocio: null,
    Tags: {},
    SocioData: {},
    Geneticas: null,
    CuotaCostos: null,
    CurrentCostoCuota: 0,
    GetTags: function () {
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: {func: "get_tags"}
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
    LoadGeneticas: function () {
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: {func: "get_lista_geneticas"}
        }).done(function (data) {
            if (data && !data.error) {
                Socio.Geneticas = {};
                $('#socioIngresarEntregaVariedad').html("");
                for (var i = 0; i < data.length; i++) {
                    Socio.Geneticas[data[i].id] = data[i];

                    //load macroentregas_select
                    $('#socioIngresarEntregaVariedad').append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');

                }
            }
            Socio.LoadEntregas();
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
        $('#socioDatosValorFechaNacimiento').html('<input id="socioNuevoFechaNacimiento"  placeholder="01/12/2013" type="text">');
        $('#socioDatosValorTags').html('');
        $.each(Socio.Tags, function (index, value) {
            $('#socioDatosValorTags').append('<label><input type="checkbox" id="socioNuevoTagChk_' + value.id + '" class="socioNuevoTagChk" name="' + value.id + '"/>' + value.nombre + '</label>');
        });
        $('#socioDatosValorObservaciones').html('<textarea id="socioNuevoObservaciones"></textarea>');
        $('#socioDatosFieldNombre').css('display', 'block');
        $("#socioNuevoFechaInicio").mask("99/99/9999");
        $("#socioNuevoFechaNacimiento").mask("99/99/9999");
    },
    SalvarSocio: function () {

        if (Socio.VerificarDatosSocio()) {

            var tags = new Array();
            $(".socioNuevoTagChk:checked").each(function () {
                tags.push($(this).attr('name'));
            });

            var postData;
            if (Socio.Editing) {
                postData = {
                    func: "update_socio",
                    id: Socio.IdSocio,
                    numero: $('#socioNuevoNumero').val(),
                    nombre: $('#socioNuevoNombre').val(),
                    documento: $('#socioNuevoDocumento').val(),
                    email: $('#socioNuevoEmail').val(),
                    telefono: $('#socioNuevoTelefono').val(),
                    fecha_inicio: Toolbox.DataToMysqlDate($('#socioNuevoFechaInicio').val()),
                    tags: tags,
                    observaciones: $('#socioNuevoObservaciones').val(),
                    fecha_nacimiento: Toolbox.DataToMysqlDate($('#socioNuevoFechaNacimiento').val())
                };
            } else {
                postData = {
                    func: "create_socio",
                    numero: $('#socioNuevoNumero').val(),
                    nombre: $('#socioNuevoNombre').val(),
                    documento: $('#socioNuevoDocumento').val(),
                    email: $('#socioNuevoEmail').val(),
                    telefono: $('#socioNuevoTelefono').val(),
                    fecha_inicio: Toolbox.DataToMysqlDate($('#socioNuevoFechaInicio').val()),
                    tags: tags,
                    observaciones: $('#socioNuevoObservaciones').val(),
                    fecha_nacimiento: Toolbox.DataToMysqlDate($('#socioNuevoFechaNacimiento').val())
                };
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
                error = 'Falt&oacute; especificar numero de socio';
            } else if (!error && isNaN($('#socioNuevoNumero').val())) {
                error = 'N&uacute;mero de socio invalido';
            }
            if (!error && $('#socioNuevoEmail').val() == '') {
                error = 'Falt&oacute; especificar un email';
            }
            if (!error && $('#socioNuevoNombre').val() == '') {
                error = 'Falt&oacute; especificar el nombre del socio';
            }
            if (!error && $('#socioNuevoFechaInicio').val() == '') {
                error = 'Falt&oacute; especificar fecha de inicio';
            }
            if (!error && $('#socioNuevoFechaNacimiento').val() == '') {
                error = 'Falt&oacute; especificar fecha de nacimiento';
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
            data: {func: "get_socio", id: Socio.IdSocio}
        }).done(function (data) {
            if (data && !data.error) {
                Socio.SocioData = data;

                $('#socioLabelEstado').removeClass('labelEstadoActivo');
                $('#socioLabelEstado').removeClass('labelEstadoSuspendido');
                if (data.activo == true) {
                    $('#socioLabelEstado').addClass('labelEstadoActivo');
                    $('#socioLabelEstado').html("Activo");
                } else {
                    $('#socioLabelEstado').addClass('labelEstadoSuspendido');
                    $('#socioLabelEstado').html("Suspendido");
                }

                $("#socioDatosFieldNombre").css('display', 'none');
                $("#socioBtnSalvarContainer").css('display', 'none');
                $("#socioNombreTitulo").html(data.nombre + '<i class="icon-edit socioIconBtnTitle" onClick="Socio.EditSocio();" title="Editar socio"></i><i class="icon-eye-open socioIconBtnTitle" onClick="Socio.OpenSocioView();" title="Vista de socio"></i>');
                $("#socioDatosValorNumero").html('<p>' + data.numero + "</p>");
                $("#socioDatosValorDocumento").html('<p>' + data.documento + "</p>");
                $("#socioDatosValorEmail").html('<p>' + data.email + "</p>");
                $("#socioDatosValorFechaInicio").html('<p>' + Toolbox.MysqlDateToDate(data.fecha_inicio) + "</p>");
                $("#socioDatosValorFechaNacimiento").html('<p>' + Toolbox.MysqlDateToDate(data.fecha_nacimiento) + "</p>");
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
        $('#socioDatosValorFechaNacimiento').html('<input id="socioNuevoFechaNacimiento" type="text"  placeholder="01/12/2013" value="' + Toolbox.MysqlDateToDate(Socio.SocioData.fecha_nacimiento) + '">');
        $('#socioDatosValorTags').html('');
        $.each(Socio.Tags, function (index, value) {
            $('#socioDatosValorTags').append('<label><input type="checkbox" id="socioNuevoTagChk_' + value.id + '" class="socioNuevoTagChk" name="' + value.id + '"/>' + value.nombre + '</label>');
        });
        $.each(Socio.SocioData.tags, function (index, value) {
            $('#socioNuevoTagChk_' + value).attr('checked', 'checked');
        });
        $('#socioDatosValorObservaciones').html('<textarea id="socioNuevoObservaciones">' + Socio.SocioData.observaciones + '</textarea>');
        $("#socioNuevoFechaInicio").mask("99/99/9999");
        $("#socioNuevoFechaNacimiento").mask("99/99/9999");

    },
    OpenModalNuevoPago: function () {

        $('.feedbackContainerModal').css('display', 'none');

        Socio.calcularValorCuotaNuevoPago();

        $('#socioIngresarPagoNotas').val('');
        $('#socioIngresarPagoFecha').val(Toolbox.GetFechaHoyLocal());
        $('#socioIngresarPagoModal').modal("show");
    },
    calcularValorCuotaNuevoPago: function(){
        var year = $('#socioIngresarPagoRazonMensualidadAnio').val();
        var mes = Toolbox.NombreMesesEspIndex[$('#socioIngresarPagoRazonMensualidadMes').val().toLowerCase()];

        for(var i=0;i<Socio.CuotaCostos.length;i++){
            var mesInicio = Socio.CuotaCostos[i].fecha_inicio.split("-")[1];
            var mesFin = Socio.CuotaCostos[i].fecha_fin.split("-")[1];
            var yearInicio = Socio.CuotaCostos[i].fecha_inicio.split("-")[0];
            var yearFin = Socio.CuotaCostos[i].fecha_fin.split("-")[0];

            if(((yearInicio == year && mesInicio <= mes) || yearInicio < year) &&
                ((yearFin == year && mesFin >= mes) || yearFin > year)){
                    $('#socioIngresarPagoValor').val(Socio.CuotaCostos[i].valor);
                Socio.CurrentCostoCuota = Socio.CuotaCostos[i].valor;
            }
        }
    },
    OnChangeMonto: function(){
        //$('#socioIngresarPagoDescuento').val(Socio.CurrentCostoCuota - $('#socioIngresarPagoValor').val());
    },
    IngresarPago: function () {
        if (Socio.VerificarDatosPago()) {

            var razonPago = $("#socioIngresarPagoRazon").val();
            if ($("#socioIngresarPagoRazon").val() == "mensualidad") {
                razonPago = "mensualidad (" + $('#socioIngresarPagoRazonMensualidadMes').val() + "/" + $('#socioIngresarPagoRazonMensualidadAnio').val() + ")";
            }

            Toolbox.ShowLoaderModal();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: {
                    func: "ingresar_pago",
                    id_socio: Socio.IdSocio,
                    valor: $("#socioIngresarPagoValor").val(),
                    fecha_pago: Toolbox.DataToMysqlDate($("#socioIngresarPagoFecha").val()),
                    razon: razonPago, notas: $("#socioIngresarPagoNotas").val(),
                    tipo: $("#socioIngresarPagoTipo").val(),
                    descuento: $("#socioIngresarPagoDescuento").val(),
                    descuento_json: $("#socioIngresarPagoRazonDescuento").val()
                }
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
        //else if (!error && $('#socioIngresarPagoRazon').val() == "mensualidad" &&
        //    Socio.CurrentCostoCuota > 0 &&
        //    $('#socioIngresarPagoValor').val() + $('#socioIngresarPagoDescuento').val() > Socio.CurrentCostoCuota){
        //
        //    error = 'El monto y el descuento exceden el costo de la cuota mensual establecida de $' + Socio.CurrentCostoCuota;
        //}

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
            data: {func: "get_pagos_socio", id_socio: Socio.IdSocio}
        }).done(function (data) {
            if (data && !data.error) {

                $('#listaPagosSocioTabla').html("");
                for (var i = 0; i < data.length; i++) {

                    var descuento = "";
                    if(data[i].descuento != "" && data[i].descuento != "0.00"){
                        descuento = data[i].descuento + ' ' + Toolbox.TransformSpecialTag(data[i].descuento_json)
                    }

                    $('#listaPagosSocioTabla').append('<tr onClick="document.location.href = \'/pago.php?id=' + data[i].id + '\'"><td>' + data[i].id + '</td>' +
                        '<td>' + data[i].valor + '</td>' +
                        '<td>' + Toolbox.MysqlDateToDate(data[i].fecha_pago) + '</td>' +
                        '<td>' + Toolbox.TransformSpecialTag(data[i].razon) + '</td>' +
                        '<td>' + descuento + '</td>' +
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
    CambiarEstadoSocio: function () {

        var nuevoEstado;
        var confirmacion = false;
        if ($('#socioEditarEstado').val() == 'activo') {
            if (Socio.SocioData.activo == true) {
                $('#socioCambiarEstadoModal').modal('hide');
                return;
            } else {
                nuevoEstado = true;
            }
        } else if ($('#socioEditarEstado').val() == 'suspendido') {
            if (Socio.SocioData.activo != true) {
                $('#socioCambiarEstadoModal').modal('hide');
                return;
            } else {
                nuevoEstado = false;
            }
        } else if ($('#socioEditarEstado').val() == 'eliminar') {
            confirmacion = confirm('Eliminar socio permanentemente?');
        }

        if ($('#socioEditarEstado').val() == 'eliminar') {
            if (confirmacion) {
                Toolbox.ShowLoader();
                $.ajax({
                    dataType: 'json',
                    type: "POST",
                    url: "proc/controller.php",
                    data: {func: "eliminar_socio", id_socio: Socio.IdSocio}
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
        } else {
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: {func: "update_estado_socio", id_socio: Socio.IdSocio, activo: nuevoEstado}
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
    OpenModalNuevaEntrega: function () {
        $('.feedbackContainerModal').css('display', 'none');
        $('#socioIngresarEntregaGramos').val('');
        $('#socioIngresarEntregaFecha').val(Toolbox.GetFechaHoyLocal());
        $('#socioIngresarEntregaVariedad').val('');
        $('#socioIngresarEntregaNotas').val('');
        $('.loaderModal').css('display', 'none');
        $('#socioIngresarEntregaModal').modal('show');
        $('#socioIngresarPagoDescuento').val(0);
    },
    IngresarEntrega: function () {
        if (Socio.VerificarNuevaEntrega()) {
            Toolbox.ShowLoaderModal();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: {
                    func: "ingresar_entrega",
                    id_socio: Socio.IdSocio,
                    gramos: $("#socioIngresarEntregaGramos").val(),
                    fecha: Toolbox.DataToMysqlDate($("#socioIngresarEntregaFecha").val()),
                    variedad: $("#socioIngresarEntregaVariedad").val(),
                    notas: $("#socioIngresarEntregaNotas").val()
                }
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
    VerificarNuevaEntrega: function () {
        var error = undefined;


        if (!error && $('#socioIngresarEntregaGramos').val() == '') {
            error = 'Falt&oacute; especificar la cantidad de gramos';
        } else if (!error && $('#socioIngresarEntregaFecha').val() == '') {
            error = 'Falto especificar fecha de entrega';
        } else if (!error && isNaN($('#socioIngresarEntregaGramos').val())) {
            error = 'Gramos invalidos';
        } else if (!error && $('#socioIngresarEntregaVariedad').val() == '') {
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
    LoadEntregas: function () {
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: {func: "get_entregas_socio", id_socio: Socio.IdSocio}
        }).done(function (data) {
            if (data && !data.error) {

                $('#listaEntregasSocioTabla').html("");
                for (var i = 0; i < data.length; i++) {

                    var genetica = Socio.Geneticas[data[i].id_genetica];
                    if (genetica) {
                        genetica = genetica.nombre;
                    } else {
                        genetica = "";
                    }

                    $('#listaEntregasSocioTabla').append('<tr onClick=""><td>' + data[i].gramos + '</td>' +
                        '<td>' + Toolbox.MysqlDateToDate(data[i].fecha) + '</td>' +
                        '<td>' + Toolbox.TransformSpecialTag(genetica) + '</td>' +
                        '<td>' + data[i].notas + '</td>' +
                        '<td><a href="#" onclick="Socio.CancelarEntrega(\'' + data[i].id + '\');return false;">borrar</a></td></tr>');
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
    CancelarEntrega: function (id) {
        if (confirm("Cancelar entrega?")) {
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: {func: "cancelar_entrega", id: id}
            }).done(function (data) {
                if (data && !data.error) {

                    Socio.LoadEntregas();

                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'Error al cancelar entrega.');
                    }
                }
                Toolbox.StopLoader();
            });
        }
    },
    TogglePagoRazon: function () {
        if ($('#socioIngresarPagoRazon').val() == "mensualidad") {
            $('#socioIngresarPagoRazonMensualidadMes').css('display', 'block');
            $('#socioIngresarPagoRazonMensualidadAnio').css('display', 'block');
        } else {
            $('#socioIngresarPagoRazonMensualidadMes').css('display', 'none');
            $('#socioIngresarPagoRazonMensualidadAnio').css('display', 'none');
        }
    },
    GetDeudas: function () {
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: {func: "get_deudas_socio", id_socio: Socio.IdSocio}
        }).done(function (data) {
            if (data && !data.error) {
                $('.socioRecordatorioDeudaContainer').html('');
                for (var i = 0; i < data.length; i++) {
                    $('.socioRecordatorioDeudaContainer').append('<span class="alert alert-danger socio-deuda"><strong>$' + data[i].monto + "</strong>  " + data[i].razon +
                        '<button type="button" class="close" aria-label="Close" onclick="Socio.CancelarDeuda(\'' + data[i].id + '\');"><span aria-hidden="true">&times;</span></button></span>');
                }
            }
            Toolbox.StopLoader();
        });
    },
    OpenModalNuevaDeuda: function () {
        $('.feedbackContainerModal').css('display', 'none');
        $('#socioIngresarDeudaMonto').val('');
        $('#socioIngresarDeudaRazon').val('');
        $('#socioIngresarDeudaModal').modal("show");
    },
    IngresarDeuda: function () {
        Toolbox.ShowLoader();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: {
                func: "ingresar_deuda", id_socio: Socio.IdSocio,
                monto: $('#socioIngresarDeudaMonto').val(),
                razon: $('#socioIngresarDeudaRazon').val()
            }
        }).done(function (data) {
            if (data && !data.error) {

                $('#socioIngresarDeudaModal').modal("hide");
                Socio.GetDeudas();

            } else {
                if (data && data.error) {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                } else {
                    Toolbox.ShowFeedback('feedbackContainer', 'error', 'Error al cancelar deuda.');
                }
            }
            Toolbox.StopLoader();
        });
    },
    CancelarDeuda: function (id) {
        if (confirm("Cancelar deuda?")) {
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: {func: "cancelar_deuda", id: id}
            }).done(function (data) {
                if (data && !data.error) {

                    Socio.GetDeudas();

                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'Error al cancelar deuda.');
                    }
                }
                Toolbox.StopLoader();
            });
        }
    },
    OpenSocioView: function () {
        if (!Socio.SocioData.hash) {
            Toolbox.ShowLoader();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: "proc/controller.php",
                data: {func: "generar_hash", id: Socio.IdSocio}
            }).done(function (data) {
                if (data && !data.error) {

                    window.open(GLOBAL_domain + '/vista_socio.php?h=' + data,
                        '_blank'
                    );

                } else {
                    if (data && data.error) {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                    } else {
                        Toolbox.ShowFeedback('feedbackContainer', 'error', 'Error al cancelar deuda.');
                    }
                }
                Toolbox.StopLoader();
            });
        } else {
            window.open(GLOBAL_domain + '/vista_socio.php?h=' + Socio.SocioData.hash,
                '_blank'
            );
        }
    },
    GetCuotaCostos: function(){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: {func: "get_costos_cuotas"}
        }).done(function (data) {
            if (data && !data.error) {
                Socio.CuotaCostos = data;
            }
            Toolbox.StopLoader();
        });
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
        $('.loaderModal').css('display', 'none');
        $('#socioCambiarEstadoModal').modal({
            show: true
        });
    });
    $('#socioCambiarEstadoModalBtnCambiar').on('click', function () {
        Socio.CambiarEstadoSocio();
    });

    var today = new Date();
    $('#socioIngresarPagoRazonMensualidadAnio').val(today.getFullYear());
    $('#socioIngresarPagoRazonMensualidadMes').val(Toolbox.NombreMesesEsp[today.getMonth()+1]);

    Socio.LoadPagos();
    Socio.GetDeudas();
    Socio.LoadGeneticas();
    Socio.GetCuotaCostos();
});
