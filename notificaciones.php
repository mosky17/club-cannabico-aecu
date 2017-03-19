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
        <script src="scripts1.0.10/notificaciones.js"></script>
    </head>

    <body>
    <div class="container">

        <h2>Notificaci&oacute;n de Estado</h2>


    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
