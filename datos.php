<?php

require_once(dirname(__FILE__) . '/layout.php');
require_once(dirname(__FILE__) . '/proc/classes/auth.php');

if (Auth::access_level() < 0) {
    ?>
    <script type="text/javascript">
        Toolbox.GoToLogin();
    </script>
<?php } else { ?>

    <head>
        <script src="scripts1.0.10/datos.js"></script>
    </head>

    <body>
    <div class="container">
        <div id=feedbackContainer></div>
        <h2>Datos del Club</h2>
        <table class="table-datos">
            <tr>
                <td class="datos-codigo-row">Nombre del Club</td>
                <td class="datos-valor-row datos-valor-nombre"><?php echo $datos["nombre"]; ?></td>
                <td class="datos-edit-row"><a href="#" onclick="Datos.EditDato('nombre');" class="datos-edit-nombre">editar</a></td>
            </tr>
            <tr>
                <td class="datos-codigo-row">Siglas</td>
                <td class="datos-valor-row datos-valor-sigla"><?php echo $datos["sigla"]; ?></td>
                <td class="datos-edit-row"><a href="#" onclick="Datos.EditDato('sigla');" class="datos-edit-sigla">editar</a></td>
            </tr>
            <tr>
                <td class="datos-codigo-row">Personeria Jur&iacute;dica</td>
                <td class="datos-valor-row datos-valor-personeriajuridica"><?php echo $datos["personeriajuridica"]; ?></td>
                <td class="datos-edit-row"><a href="#" onclick="Datos.EditDato('personeriajuridica');" class="datos-edit-personeriajuridica">editar</a></td>
            </tr>
<!--            <tr>-->
<!--                <td class="datos-codigo-row">Identificador IRCCA</td>-->
<!--                <td class="datos-valor-row datos-valor-idircca">--><?php //echo $datos["idircca"]; ?><!--</td>-->
<!--                <td class="datos-edit-row"><a href="#" onclick="Datos.EditDato('idircca');" class="datos-edit-idircca">editar</a></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="datos-codigo-row">Logo</td>-->
<!--                <td class="datos-valor-row datos-valor-logo">--><?php //echo $datos["logo"]; ?><!--</td>-->
<!--                <td class="datos-edit-row"><a href="#" onclick="Datos.EditDato('logo');" class="datos-edit-logo">editar</a></td>-->
<!--            </tr>-->
        </table>
    </div>
    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>