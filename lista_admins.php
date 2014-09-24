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
        <script src="scripts1.0.2/admins.js"></script>
    </head>

    <body>
    <div class="container">
        <h2>Lista de Administradores</h2>
        <div id=feedbackContainer></div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email/Usuario</th>
                    <th>Modificar</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody class="tabla-admins"></tbody>
        </table>
        <div class="btn btn-success pull-right" onclick="Admins.OpenModalNuevoAdmin();">Crear Administrador</div>
    </div>

    <!-- Modal admin -->
    <div id="adminsDatosModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="adminsDatosModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="adminsDatosModalLabel">Nuevo Administrador</h3>
        </div>
        <div class="modal-body">
            <div id="adminsDatosModalFeedback" class="feedbackContainerModal"></div>
            <table>
                <tr>
                    <td>Nombre</td>
                    <td><input type="text" class="admin_datos_nombre"></td>
                </tr>
                <tr>
                    <td>Email/Usuario</td>
                    <td><input type="text" class="admin_datos_email"></td>
                </tr>
                <tr>
                    <td>Clave</td>
                    <td><input type="password" class="admin_datos_clave"></td>
                </tr>
                <tr>
                    <td>Repetir Clave</td>
                    <td><input type="password" class="admin_datos_clave2"></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="adminsDatosModalBtnSalvar" class="btn btn-primary">Salvar</button>
        </div>
    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
