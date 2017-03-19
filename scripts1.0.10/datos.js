var Datos = {
    DatoEditando:null,
    EditDato: function(codigo){
        if(Datos.DatoEditando == codigo){
            Datos.SaveDato(codigo);
        }else{
            if(Datos.DatoEditando){
                Datos.CancelarEditDato(codigo);
            }
            Datos.DatoEditando = codigo;
            $('.datos-edit-' + codigo).addClass("btn btn-success");
            $('.datos-edit-' + codigo).html("salvar");
            $('.datos-valor-' + codigo).html('<input type="text" class="datos-input datos-input-' + codigo + '" value="' + $('.datos-valor-' + codigo).val() + '">');
        }
    },
    SaveDato: function(codigo){
        Toolbox.ShowLoader();

        $.ajax({
            dataType: 'json',
            type: "POST",
            url: "proc/controller.php",
            data: { func: "update_dato", codigo:codigo, valor:$('.datos-input-' + codigo).val()}
        }).done(function (data) {
            if (data && data.ok) {
                location.reload();
            }else{
                if(data && data.error){
                    Toolbox.ShowFeedback('feedbackContainer', 'error', data.error);
                }else{
                    Toolbox.ShowFeedback('feedbackContainer', 'error', 'No se pudieron salvar los datos del club.');
                }
            }
            Toolbox.StopLoader();
        });
    },
    CancelarEditDato: function(codigo){
        $('.datos-edit-' + codigo).removeClass("btn btn-success");
        $('.datos-edit-' + codigo).html("editar");
        switch(codigo){
            case 'nombre':
                $('.datos-valor-' + codigo).html(DATO_nombre);
                break;
            case 'sigla':
                $('.datos-valor-' + codigo).html(DATO_sigla);
                break;
            case 'personeriajuridica':
                $('.datos-valor-' + codigo).html(DATO_personeriajuridica);
                break;
            case 'idircca':
                $('.datos-valor-' + codigo).html(DATO_idircca);
                break;
            case 'logo':
                $('.datos-valor-' + codigo).html(DATO_logo);
                break;
        }

    }
}

$(document).ready(function () {

    Toolbox.UpdateActiveNavbar('nav_datos');
    var params = Toolbox.GetUrlVars();



});