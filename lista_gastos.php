<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 03/08/13
 * Time: 07:12 PM
 * To change this template use File | Settings | File Templates.
 */

require_once(dirname(__FILE__) . '/layout.php');
require_once(dirname(__FILE__) . '/proc/classes/auth.php');

if (Auth::access_level() < 0) {
    ?>
    <script type="text/javascript">
        Toolbox.GoToLogin();
    </script>
<?php } else { ?>

    <head>
        <script src="scripts1.0.10/lista_gastos.js"></script>
    </head>

    <body>
    <div class="container">
        <h2>Caja</h2>

        <div class="totales">
        </div>

        <div class="controlesLista">
            <div id="crearGasto" class="btn btn-danger" onclick="ListaGastos.OpenModalNuevoGasto();">+ Gasto</div>
            <div id="crearHaber" class="btn btn-success" onclick="ListaGastos.OpenModalNuevoHaber();">+ Ingreso</div>
            <div id="exportarListaDropdown" class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    Exportar
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                    <li><a tabindex="-1" href="#" onClick="ListaGastos.ExportarCaja();">Caja</a></li>
                </ul>
            </div>
        </div>
        <div id=feedbackContainer></div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Valor $</th>
                <th></th>
                <th>Fecha Pago</th>
                <th>Raz&oacute;n</th>
                <th>Rubro</th>
                <th>Notas</th>
            </tr>
            </thead>
            <tbody id="listaGastosTabla"></tbody>
        </table>
    </div>

    <iframe id="exportIframe" src="" style="height:0px;border:0 none;"></iframe>

    <!-- Modal ingresar gasto -->
    <div id="listaIngresarGastoModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="listaIngresarGastoModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="listaIngresarGastoModalLabel">Ingresar Gasto</h3>
        </div>
        <div class="modal-body">
            <div id="feedbackContainerModalIngresarGasto" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Valor $</h4>
                <input style="width: 110px;" type="text" placeholder="0.00" id="listaIngresarGastoValor">
            </div>
            <div class="modalListaRow">
                <h4>Fecha</h4>
                <input type="text" placeholder="" id="listaIngresarGastoFecha">
            </div>
            <div class="modalListaRow">
                <h4>Rubro</h4>
                <select id="listaIngresarGastoGrupo">
                    <option value="">- seleccionar rubro -</option>
                    <option value="Cultivo">Cultivo</option>
                    <option value="Energia">Energ&iacute;a</option>
                    <option value="Equipamiento">Equipamiento</option>
                    <option value="Instalaciones">Instalaciones</option>
                    <option value="Administracion">Administraci&oacute;n</option>
                    <option value="Jardineros">Jardineros</option>
                    <option value="Locacion">Locaci&oacute;n</option>
                    <option value="Transporte">Transporte</option>
                    <option value="Devoluciones">Devoluciones</option>
                    <option value="Limpieza">Limpieza</option>
                    <option value="Manicura">Manicura</option>
                    <option value="Seguridad">Seguridad</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="modalListaRow">
                <h4>Raz&oacute;n</h4>
                <textarea style="width: 400px; height: 50px; max-width: 400px; max-height: 100px;"
                          id="listaIngresarGastoRazon"></textarea>
            </div>
            <div class="modalListaRow">
                <h4>Notas</h4>
                <textarea style="width: 400px; height: 50px; max-width: 400px; max-height: 100px;"
                          id="listaIngresarGastoNotas"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="listaIngresarGastoModalBtnIngresar" class="btn btn-primary">Ingresar</button>
        </div>
    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>