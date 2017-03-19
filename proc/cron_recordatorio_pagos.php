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

            $html_link = "<a ".
                "style=\"".
                "background: #7ed934;".
                "background-image: -webkit-linear-gradient(top, #7ed934, #67b023);".
                "background-image: -moz-linear-gradient(top, #7ed934, #67b023);".
                "background-image: -ms-linear-gradient(top, #7ed934, #67b023);".
                "background-image: -o-linear-gradient(top, #7ed934, #67b023);".
                "background-image: linear-gradient(to bottom, #7ed934, #67b023);".
                "font-family: Arial;".
                "color: #ffffff;".
                "font-size: 20px;".
                "padding: 10px 20px 10px 20px;".
                "text-decoration: none;\"".
                "href=\"" . $GLOBALS['domain'] . "/vista_socio.php?h=" . $hash .
                "\">Estado de tu membres&iacute;a</a><br><br>";

            //send recordatorio
            $email_text = "";
            $email_html = '<b>Estimado Socio,</b><br><br>' .
                'Este es un recordatorio de que tienes tiempo hasta el 15 de ' . $MONTH_NAMES[$current_month] . ' para pagar tu cuota, de lo contrario una vez vencida va a incurrir en un recargo del 10%.<br><br>'.
                'A partir del mes de agosto del 2016 la cuota tendrá un valor de $3000. Recordamos que dedicando 10hr de trabajo tendras un descuento de $1000 para tu próxima mensualidad.<br><br>'.
                '<span style="text-decoration:underline">Formas de Pago:</span><br><br>' .
                '<strong>BROU</strong><br>'.
                'Puedes hacer una transferencia o deposito a la caja de ahorro numero <b>188-0504831</b> del BROU, en tal caso envianos un email con el detalle del pago, ya sea n&uacute;mero de transferencia o referencia.<br><br>'.
                '<strong>Personalmente</strong><br>'.
                'Puedes hacer el pago personalmente en nuestra sede solo con previo aviso a la administración.<br><br><br>'.
                $html_link.
                '<br><br>Buen comienzo de mes!<br><br>'.
                'Atte,<br>'.
                'La Administraci&oacute;n.<br>'.
                'Club Cann&aacute;bico El Piso';
            $email_subject = "CCEP - Estado de tu membresía";
            $email_to = $socio->email;
            $email_tags = array('CCEP');
            //if($socio->email == 'martin.gaibisso@gmail.com'){
            echo Mandrill::SendDefault($email_text,$email_html,$email_subject,$email_to,$email_tags);
            //}
        }
    }
    usleep(200000);
}
