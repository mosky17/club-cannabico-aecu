var Toolbox = {
    NombreMesesEsp:{1:"Enero",2:"Febrero",3:"Marzo",4:"Abril",5:"Mayo",6:"Junio",7:"Julio",8:"Agosto",9:"Setiembre",10:"Octubre",11:"Noviembre",12:"Diciembre"},
    NombreMesesEspIndex:{"enero":1,"febrero":2,"marzo":3,"abril":4,"mayo":5,"junio":6,"julio":7,
        "agosto":8,"setiembre":9,"octubre":10,"noviembre":11,"diciembre":12},
    LoaderQueue: 0,
    LoaderQueueModal: 0,
    GetUrlVars: function () {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    UpdateActiveNavbar: function (active) {
        $('#headerNavigation').css('display','block');
        $('.nav_lista_link').removeClass('active');
        $('#' + active).addClass('active');
    },
    ShowLoader: function () {
        Toolbox.LoaderQueue += 1;
        $('#nav_loader').css('display', 'block');
    },
    StopLoader: function () {
        Toolbox.LoaderQueue -= 1;
        if (Toolbox.LoaderQueue == 0) {
            $('#nav_loader').css('display', 'none');
        }

    },
    ShowLoaderModal: function () {
        Toolbox.LoaderQueueModal += 1;
        $('.loaderModal').css('display', 'block');
    },
    StopLoaderModal: function () {
        Toolbox.LoaderQueueModal -= 1;
        if (Toolbox.LoaderQueueModal == 0) {
            $('.loaderModal').css('display', 'none');
        }

    },
    DataToMysqlDate: function (date) {
        var parts = date.split('/');
        return parts[2] + "-" + parts[1] + "-" + parts[0];
    },
    MysqlDateToDate: function (date) {
        var parts = date.split('-');
        return parts[2] + "/" + parts[1] + "/" + parts[0];
    },
    ShowFeedback: function (container, type, message, noAutoTop) {

        if (!message || message == "") {
            $('#' + container).css('display', 'none');
        } else {

            var typeClass = "";
            var prefix = "";

            if (type == 'error') {

                typeClass = " alert-danger";
                prefix = '<i class="icon-warning-sign feedbackIcon"></i>';


            } else if (type == 'success') {

                typeClass = " alert-success";
                //prefix = '<img class="feedbackIcon" src="../images/ok1.png">';

            } else if (type == 'warning') {

                //prefix = '<img class="feedbackIcon" src="../images/warning2.png">';

            }

            var html = '<div class="alert alert-block' + typeClass + '">'
                + prefix + '<p>' + message + '</p></div>';

            $('#' + container).html(html);
            $('#' + container).css('display', 'block');
            if (!noAutoTop) {
                $(window).scrollTop(0);
            }
        }
    },
    GoToLogin: function () {
        window.location.href = GLOBAL_domain + "/login.php";
    },
    Logout: function () {
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "logout" }
        }).done(function (data) {
                if (data && !data.error) {
                    Toolbox.GoToLogin();
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
    TransformSpecialTag: function (text) {

        var text2 = "";
        if(text.indexOf("mensualidad")==0){
            text2 = text.substring(13, text.length -1);
            text = "mensualidad";
        }

        switch (text) {
            case "matricula":
                return '<span class="label" style="background-color:#A8BB19;">Matr&iacutecula</span>';
                break;
            case "mensualidad":
                return '<span class="label" style="background-color:#2E5894;">' + text2 + '</span>';
                break;
            case "transferencia_brou":
                return '<span class="label" style="background-color:#72A0C1;">BROU</span>';
                break;
            case "personalmente":
                return '<span class="label" style="background-color:#C46210;">En Persona</span>';
                break;

            //descuentos
            case "Voluntariado":
                return '<span class="label label-success" style="">Voluntariado</span>';
                break;

            //rubros pagos
            case "Cultivo":
                return '<span class="label label-success" style="background-color:#1ba400;">Cultivo</span>';
                break;
            case "Energia":
                return '<span class="label label-warning" style="">Energ&iacute;a</span>';
                break;
            case "Equipamiento":
                return '<span class="label label-danger" style="background-color:#bf0000;">Equipamiento</span>';
                break;
            case "Instalaciones":
                return '<span class="label label-danger" style="background-color:#d91e88;">Instalaciones</span>';
                break;
            case "Administracion":
                return '<span class="label" style="background-color:#e35000;">Administraci&oacute;n</span>';
                break;
            case "Jardineros":
                return '<span class="label" style="background-color:#1e1e1e;">Jardineros</span>';
                break;
            case "Locacion":
                return '<span class="label label-primary" style="background-color:#004098;">Locaci&oacute;n</span>';
                break;
            case "Transporte":
                return '<span class="label label-info" style="">Transporte</span>';
                break;
            case "Otro":
                return '<span class="label label-default">Otro</span>';
                break;
            case "Devoluciones":
                return '<span class="label label-default">Devoluciones</span>';
                break;
            case "Limpieza":
                return '<span class="label" style="background-color:#75f7ff;">Limpieza</span>';
                break;
            case "Manicura":
                return '<span class="label" style="background-color:#e3ff75;">Manicura</span>';
                break;
            case "Seguridad":
                return '<span class="label" style="background-color:#ff75dd;">Seguridad</span>';
                break;
            //ELSE
            default:
                return text;
                break;
        }


    },
    GetFechaHoyLocal: function(){
        var date = new Date();
        var days = date.getDate()>9 ? date.getDate() : '0' + date.getDate();
        var month = date.getMonth() + 1;
        month = month>9 ? month : '0' + month;
        return days + '/' + month + '/' + date.getFullYear();
    }
}
