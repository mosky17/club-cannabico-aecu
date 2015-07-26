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
        <script src="scripts1.0.7/lista_pagos.js"></script>
    </head>

    <body>
    <div class="container">
        <h2>Lista de Pagos</h2>
        <div class="controlesLista">
            <div class="btn btn-primary" onclick="ListaPagos.OpenModalMacroPago();">Macro Pago</div>
            <div id="exportarListaDropdown" class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    Exportar
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                    <li><a tabindex="-1" href="#" onClick="ListaPagos.ExportarComoListaPagosPorSocio();">Como lista de pagos
                            por socio</a></li>
                    <li><a tabindex="0" href="#" onClick="ListaPagos.ExportarComoListaTotalPagoPorSocio();">Como total pago por socio</a></li>
                    <li><a tabindex="1" href="#" onClick="ListaPagos.ExportarComoListaPagosPorMes();">Como lista de pagos
                            por mes</a></li>
                </ul>
            </div>
        </div>
        <div id=feedbackContainer></div>
        <div class="box">
            <div class="socioListaContenedor">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor $</th>
                        <th>Fecha Pago</th>
                        <th>Raz&oacute;n</th>
                        <th>Notas</th>
                        <th>Tipo</th>
                        <th>Socio</th>
                    </tr>
                    </thead>
                    <tbody id="listaPagosTabla"></tbody>
                </table>
            </div>
        </div>

        <br><br>

        <h2>Lista de Deudas</h2>
<!--        <div class="controlesLista">-->
<!--            <div class="btn btn-primary">Macro Pago</div>-->
<!--            <div id="exportarListaDropdown" class="btn-group">-->
<!--                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">-->
<!--                    Exportar-->
<!--                    <span class="caret"></span>-->
<!--                </a>-->
<!--                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">-->
<!--                    <li><a tabindex="-1" href="#" onClick="ListaPagos.ExportarComoListaPagosPorSocio();">Como lista de pagos-->
<!--                            por socio</a></li>-->
<!--                    <li><a tabindex="0" href="#" onClick="ListaPagos.ExportarComoListaTotalPagoPorSocio();">Como total pago por socio</a></li>-->
<!--                    <li><a tabindex="1" href="#" onClick="ListaPagos.ExportarComoListaPagosPorMes();">Como lista de pagos-->
<!--                            por mes</a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
        <div class="box">
            <div class="socioListaContenedor">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Monto $</th>
                        <th>Raz&oacute;n</th>
                        <th>Socio</th>
                        <th>Cancelar</th>
                    </tr>
                    </thead>
                    <tbody id="listaDeudasTabla"></tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal macro pago -->
    <div id="macroPagoModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="macroPagoModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="macroPagoModalLabel">Nuevo Macro Pago</h3>
        </div>
        <div class="modal-body">
            <div id="macroPagoModalFeedback" class="feedbackContainerModal"></div>
            <table class="macroTabla">
                <tr>
                    <td class="fieldname">Fecha</td>
                    <td>
                        <input type="text" class="macropago_fecha" placeholder="01/01/2015">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="fieldname">Valor ($)</td>
                    <td><input type="text" class="macropago_valor"></td>
                    <td class="fieldname" style="padding-left: 10px">Via</td>
                    <td><select id="macropago_tipo">
                            <option value="personalmente">Personalmente</option>
                            <option value="transferencia_brou">Transferencia BROU</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="fieldname">Raz&oacute;n</td>
                    <td colspan="3"><select id="macropago_razon" onchange="ListaPagos.TogglePagoRazon();">
                            <option value="mensualidad">Mensualidad</option>
                            <option value="matricula">Matr&iacute;cula</option>
                        </select>
                        <select id="macropago_razonMensualidad">
                            <option value="Enero">Enero</option>
                            <option value="Febrero">Febrero</option>
                            <option value="Marzo">Marzo</option>
                            <option value="Abril">Abril</option>
                            <option value="Mayo">Mayo</option>
                            <option value="Junio">Junio</option>
                            <option value="Julio">Julio</option>
                            <option value="Agosto">Agosto</option>
                            <option value="Setiembre">Setiembre</option>
                            <option value="Octubre">Octubre</option>
                            <option value="Noviembre">Noviembre</option>
                            <option value="Diciembre">Diciembre</option>
                        </select>
                        <select id="macropago_razonMensualidadAnio">
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="fieldname">Socios</td>
                    <td colspan="3">
                        <div class="container_macropago_socios">
                            <table class="table table-striped" style="width: 99%;">
                                <tbody id="macropago_tabla_socios"></tbody>
                            </table></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary" onclick="ListaPagos.AgregarMacroPago();">Agregar Pagos</button>
        </div>
    </div>

    <iframe id="exportIframe" src="" style="height:0px;border:0 none;"></iframe>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>