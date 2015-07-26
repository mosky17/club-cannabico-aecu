<?php

require_once(dirname(__FILE__) . '/classes/mandrill.php');
require_once(dirname(__FILE__) . '/classes/socio.php');
require_once(dirname(__FILE__) . '/classes/pago.php');
require_once(dirname(__FILE__).'/../config.php');

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

$socios = Socio::get_socios_activos();

foreach($socios as $socio){

    if($socio->email != null && $socio->email != "" && strpos($socio->email,'@') > 0){

        $hash = $socio->hash;
        if($hash == null || $hash == ""){
            $hash = $socio->generate_hash();
        }

        if($hash != null && $hash != ""){

            $html_link = "<a href=\"" . $GLOBALS['domain'] . "/vista_socio.php?h=" . $hash . "\">Estado de tu membres&iacute;a</a><br><br>";

            //send recordatorio
            $email_text = "";
            $email_html = '<b>Estimado Socio,</b><br><br>' .
                'Este es un recordatorio de que tu membres&iacute;a vence el 15 de ' . $MONTH_NAMES[$current_month] . ', la cuota tiene un valor de <b>$2300</b> y una vez vencida va a incurrir en un recargo del 10%. <b>Recordamos la posibilidad de reducci&oacute;n de la misma a $1700 a cambio de por lo menos 10hr de trabajo mensuales en las diferentes tareas del club. A continuaci&oacute;n se les enviara un correo a los socios habilitados a la cuota reducida.</b><br><br>'.
                '<span style="text-decoration:underline">Formas de Pago:</span><br><br>' .
                '<strong>BROU</strong><br>'.
                'Puedes hacer una transferencia o deposito a la caja de ahorro numero <b>188-0504831</b> del BROU, en tal caso envianos un email con el detalle del pago, ya sea numero de transferencia o referencia.<br><br>'.
                '<strong>Personalmente</strong><br>'.
                'Puedes hacer el pago personalmente en la sede de AECU (Cassinoni esq Maldonado) Lunes o Viernes de 17 a 20hrs.<br><br>'.
                $html_link.
                'Buen comienzo de mes!<br><br>'.
                'Atte,<br>'.
                'La Administraci&oacute;n.<br>'.
                'Club Cannabico El Piso';
            $email_subject = "CCEP - Estado de tu membresía";
            $email_to = array(array("email"=>$socio->email));
            $email_tags = array('CCEP');
            //if($socio->email == 'martin.gaibisso@gmail.com'){
            echo Mandrill::SendDefault($email_text,$email_html,$email_subject,$email_to,$email_tags);
            //}
        }
    }
}