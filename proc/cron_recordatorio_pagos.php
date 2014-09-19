<?php

require_once(dirname(__FILE__) . '/classes/mandrill.php');
require_once(dirname(__FILE__) . '/classes/socio.php');
require_once(dirname(__FILE__) . '/classes/pago.php');

date_default_timezone_set('America/Montevideo');

$MONTH_NAMES = array("01"=>'Enero',
                    "02"=>'Febrero',
                    "03"=>'Marzo',
                    "04"=>'Abril',
                    "05"=>'Mayo',
                    "06"=>'Junio',
                    "07"=>'Julio',
                    "08"=>'Agosto',
                    "09"=>'Setiembre',
                    "10"=>'Octubre',
                    "11"=>'Noviembre',
                    "12"=>'Diciembre');

$current_month = date('m');
$current_year = date('Y');
$current_month_name = $MONTH_NAMES[$current_month];
$razon_string_this_month = "mensualidad (" . $current_month_name . '/' . $current_year . ')';

$socios = Socio::get_socios_activos();

foreach($socios as $socio){

    $pagos_socio = Pago::get_pagos_socio($socio->id);
    $pago_este_mes = false;
    foreach($pagos_socio as $pago){
        if($pago->razon == $razon_string_this_month){
            $pago_este_mes = true;
        }
    }
    if($pago_este_mes==true){
        //pago recibido

    }else{
        //no ha pagado tdv



    }

    $html_ultimos_pagos = '<span style="text-decoration:underline">&Uacute;ltimos pagos registrados:</span><br><br>'.
        '<table class="table-pagos"><thead><tr><th>Fecha</th><th>Monto</th><th>Raz&oacute;n</th></tr></thead><tbody>';
    for($i = count($pagos_socio)-1;$i>=0;$i--){
        $html_ultimos_pagos .= '<tr><td>' . $pagos_socio[$i]->fecha_pago . '</td>'.
                               '<td>$' . $pagos_socio[$i]->valor . '</td>'.
                               '<td>' . $pagos_socio[$i]->razon . '</td></tr>';
    }
    $html_ultimos_pagos .= '</tbody></table><br><br>';

    $style = '<style>.table-pagos { border:1px solid #aaa; }.table-pagos td { border:1px solid #aaa; text-align: left; padding: 0px 10px 0 0;}</style>';

    //send recordatorio
    $email_text = "";
    $email_html = '<b>Estimado Socio,</b><br><br>' .
        'Este es un recordatorio de que tu membres&iacute;a vence el 15 de ' . $MONTH_NAMES[$current_month] . ', la cuota tiene un valor de $1500 y una vez vencida va a incurrir en un recargo del 10%.<br><br>'.
        '<span style="text-decoration:underline">Formas de Pago:</span><br><br>' .
        '<strong>BROU</strong><br>'.
        'Puedes hacer una transferencia o deposito a la caja de ahorro numero <b>188-0504831</b> del BROU, en tal caso envianos un email con el detalle del pago, ya sea numero de transferencia o referencia.<br><br>'.
        '<strong>Personalmente</strong><br>'.
        'Puedes hacer el pago personalmente en la sede de AECU (Cassinoni esq Maldonado) Lunes o Viernes de 17 a 20hrs.<br><br>'.
        $html_ultimos_pagos.$style.
        'Buen comienzo de mes!<br><br>'.
        'Atte,<br>'.
        'La Administraci&oacute;n.<br>'.
        'Club Cannabico El Piso';
    $email_subject = "Recordatorio de pago - CCEP";
    $email_to = array(array("email"=>$socio->email));
    $email_tags = array('CCEP');
    //if($socio->email == 'martin.gaibisso@gmail.com'){
        echo Mandrill::SendDefault($email_text,$email_html,$email_subject,$email_to,$email_tags);
    //}
}