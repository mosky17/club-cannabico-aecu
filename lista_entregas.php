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
        <script src="scripts1.0.10/lista_entregas.js"></script>
    </head>

    <body>
    <div class="container">
        <div id=feedbackContainer></div>
        <h2>Gen&eacute;ticas del Club</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Origen</th>
                <th>Detalles</th>
                <th>Producido (gr)</th>
                <th>Modificar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody class="tabla-geneticas"></tbody>
        </table>
        <div class="btn btn-success pull-right" onclick="ListaEntregas.OpenModalNuevaGenetica();">Agregar Genetica</div>

        <br><br><br><br>

        <h2>Lista de Entregas</h2>
        <div class="controlesLista">
            <div id="listaEntregasMacroEntrega" class="btn btn-primary" onclick="ListaEntregas.OpenModalMacroEntrega();">Macro Entrega</div>
            <div id="exportarListaDropdown" class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    Exportar
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                </ul>
            </div>
        </div>
        <div class="box">
            <div class="listaGrandeContenedor">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Peso (gr)</th>
                        <th>Variedad</th>
                        <th>Notas</th>
                    </tr>
                    </thead>
                    <tbody id="listaEntregasTabla"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal genetica -->
    <div id="geneticaDatosModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="geneticaDatosModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="geneticaDatosModalLabel">Agregar Genetica</h3>
        </div>
        <div class="modal-body">
            <div id="geneticaDatosModalFeedback" class="feedbackContainerModal"></div>
            <table>
                <tr>
                    <td>Nombre</td>
                    <td><input type="text" class="genetica_datos_nombre"></td>
                </tr>
                <tr>
                    <td>Origen</td>
                    <td><input type="text" class="genetica_datos_origen"></td>
                </tr>
                <tr>
                    <td>Detalles</td>
                    <td><textarea class="genetica_datos_detalles"></textarea></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="geneticaDatosModalBtnSalvar" class="btn btn-primary">Salvar</button>
        </div>
    </div>

    <!-- Modal macro entrega -->
    <div id="macroEntregaModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="geneticaDatosModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="macroEntregaModalLabel">Nueva Macro Entrega</h3>
        </div>
        <div class="modal-body">
            <div id="macroEntregaModalFeedback" class="feedbackContainerModal"></div>
            <table class="macroTabla">
                <tr>
                    <td class="fieldname">Fecha</td>
                    <td><input type="text" class="macroentrega_fecha" placeholder="01/01/2015"></td>
                </tr>
                <tr>
                    <td class="fieldname">Peso (gr)</td>
                    <td><input type="text" class="macroentrega_peso" placeholder="0.00"></td>
                </tr>
                <tr>
                    <td class="fieldname">Variedad</td>
                    <td><select id="macroentrega_variedades"></select></td>
                </tr>
                <tr>
                    <td class="fieldname">Socios</td>
                    <td>
                        <div class="container_macroentrega_socios">
                            <table id="macroentrega_tabla_socios" class="table table-striped" style="width: 99%;"></table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="macroEntregaBtnAgregar" class="btn btn-primary" onclick="ListaEntregas.AgregarMacroEntrega();">Agregar Entregas</button>
        </div>
    </div>

    <iframe id="exportIframe" src="" style="height:0px;border:0 none;"></iframe>



    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>