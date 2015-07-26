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
        <script src="scripts1.0.7/informes.js"></script>
    </head>

    <body>
    <div class="container">
        <div id=feedbackContainer></div>
        <h2>Informes de Cosecha</h2>
        <div class="controlesLista">
            <div class="btn btn-success" onclick="Informes.OpenModalNuevoInformeCosecha();">Nuevo Informe</div>
            <div class="btn btn-default" onclick="Informes.OpenModalExportar();">Exportar</div>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Variedad</th>
                <th>Lote</th>
                <th>Total Seco (gr)</th>
                <th>Responsable T&eacute;cnico</th>
                <th>Responsable Producci&oacute;n</th>
                <th>Aclaraciones</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody class="tabla-informes-cosecha"></tbody>
        </table>
    </div>

    <!-- Modal informe cosecha -->
    <div id="informeCosechaModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="informeCosechaModalModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3>Nuevo Informe Cosecha</h3>
        </div>
        <div class="modal-body">
            <div id="informeCosechaModalFeedback" class="feedbackContainerModal"></div>
            <table>
                <tr>
                    <td>Fecha</td>
                    <td><input type="text" class="informeCosechaFecha" placeholder="00/00/0000"></td>
                </tr>
                <tr>
                    <td>Gen&eacute;tica</td>
                    <td><select class="informeCosechaGenetica"></select></td>
                </tr>
                <tr>
                    <td>Cantidad de Plantas</td>
                    <td><input type="text" class="informeCosechaCantidadPlantas"></td>
                </tr>
                <tr>
                    <td>Peso Total Fresco (gr)</td>
                    <td><input type="text" class="informeCosechaPesoFresco" placeholder="0.00"></td>
                </tr>
                <tr>
                    <td>Peso Total Seco (gr)</td>
                    <td><input type="text" class="informeCosechaPesoSeco" placeholder="0.00"></td>
                </tr>
                <tr>
                    <td>Lote</td>
                    <td><input type="text" class="informeCosechaLote"></td>
                </tr>
                <tr>
                    <td>Responsable T&eacute;cnico</td>
                    <td><input type="text" class="informeCosechaResponsableTecnico"></td>
                </tr>
                <tr>
                    <td>Responsable Producci&oacute;n</td>
                    <td><input type="text" class="informeCosechaResponsableProduccion"></td>
                </tr>
                <tr>
                    <td>Aclaraciones</td>
                    <td><textarea class="informeCosechaAclaraciones"></textarea></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="informeCosechaSalvar" class="btn btn-primary" onclick="Informes.SalvarNuevoInformeCosecha();">Crear Informe</button>
        </div>
    </div>

    <!-- Modal export -->
    <div id="exportarInformeCosechaModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="exportarInformeCosechaModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3>Exportar Informes Cosecha</h3>
        </div>
        <div class="modal-body">
            <div id="exportarInformeCosechaModalFeedback" class="feedbackContainerModal"></div>
            <table>
                <tr>
                    <td>Mes</td>
                    <td><select class="exportarInformeCosechaMes">
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Setiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                    </select></td>
                </tr>
                <tr>
                    <td>A&ntilde;o</td>
                    <td><select class="exportarInformeCosechaAnio">
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                    </select></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button id="exportarInformeCosechaBtn" class="btn btn-primary" onclick="Informes.ExportInformeCosecha();">Exportar</button>
        </div>
    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>