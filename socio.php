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
        <title>Club Social de Cannabis #1</title>
        <script src="scripts/socio.js"></script>
    </head>

    <body>
    <div class="container">
        <div class="socioHeader">
            <h2 id="socioNombreTitulo"></h2>
            <span id="socioLabelEstado" class="label"></span>
            <div id=feedbackContainer></div>
        </div>

        <div class="box row-fluid">
            <div class="span6">

                <div style="display:none;" id="socioDatosFieldNombre" class="socioDatosField">
                    <h4>Nombre</h4>

                    <div id="socioDatosValorNombre" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>N&uacute;mero</h4>

                    <div id="socioDatosValorNumero" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Documento</h4>

                    <div id="socioDatosValorDocumento" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Email</h4>

                    <div id="socioDatosValorEmail" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Fecha Inicio</h4>

                    <div id="socioDatosValorFechaInicio" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Tel&eacute;fono</h4>

                    <div id="socioDatosValorTelefono" class="socioDatosValor"></div>
                </div>

            </div>
            <div class="span6">
                <div class="socioDatosField">
                    <h4>Tags</h4>

                    <div id="socioDatosValorTags" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Observaciones</h4>

                    <div id="socioDatosValorObservaciones" class="socioDatosValor"></div>
                </div>
            </div>
            <div style="display:none;" class="span12" id="socioBtnSalvarContainer">
                <div class="btn btn-primary" id="socioBtnSalvar">Salvar</div>
            </div>
        </div>
        <!--Pagos-->
        <h3 id="socioPagoseTitulo">&Uacute;ltimos Pagos <i id="socioBtnNuevoPago" class="icon-plus-sign-alt socioIconBtnTitle" title="Ingresar un nuevo pago"></i></h3>

        <div class="box row-fluid">
            <div class="span12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor $</th>
                        <th>Fecha Pago</th>
                        <th>Raz&oacute;n</th>
                        <th>Notas</th>
                        <th>Tipo</th>
                    </tr>
                    </thead>
                    <tbody id="listaPagosSocioTabla"></tbody>
                </table>
            </div>
        </div>

        <!--Entregas-->
        <h3 id="socioEntregasTitulo">&Uacute;ltimas Entregas <i id="socioBtnNuevaEntrega" class="icon-plus-sign-alt socioIconBtnTitle" title="Registrar nueva entrega" onclick="Socio.OpenModalNuevaEntrega();"></i></h3>

        <div class="box row-fluid">
            <div class="span12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Gramos.</th>
                        <th>Fecha Entregado</th>
                        <th>Variedad</th>
                        <th>Notas</th>
                    </tr>
                    </thead>
                    <tbody id="listaEntregasSocioTabla"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal ingresar pago -->
    <div id="socioIngresarPagoModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="socioIngresarPagoModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="socioIngresarPagoModalLabel">Ingresar Pago</h3>
        </div>
        <div class="modal-body">
            <div id="feedbackContainerModalIngresarPago" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Valor $</h4>
                <input style="width: 110px;" type="text" placeholder="0.00" id="socioIngresarPagoValor">
                <select style="float:right;" id="socioIngresarPagoTipo" placeholder="01/12/2013">
                    <option value="transferencia_brou">Transferencia BROU</option>
                    <option value="personalmente">Personalmente</option>
                </select>
                <h4 style="float:right;">Tipo</h4>
            </div>
            <div class="modalListaRow">
                <h4>Fecha</h4>
                <input type="text" placeholder="" id="socioIngresarPagoFecha">
            </div>
            <div class="modalListaRow">
                <h4>Raz&oacute;n</h4>
                <select id="socioIngresarPagoRazon">
                    <option value="matricula">Matr&iacute;cula</option>
                    <option value="mensualidad">Mensualidad</option>
                    <option value="otra">Otra</option>
                </select>
            </div>
            <div class="modalListaRow">
                <h4>Notas</h4>
                <textarea style="width: 400px; height: 100px; max-width: 400px; max-height: 100px;"
                          id="socioIngresarPagoNotas"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="socioIngresarPagoModalBtnIngresar" class="btn btn-primary">Ingresar</button>
        </div>
    </div>

    <!-- Modal cambiar estado -->
    <div id="socioCambiarEstadoModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="socioCambiarEstadoModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="socioCambiarEstadoModalLabel">Editar Estado de Socio</h3>
        </div>
        <div class="modal-body">
            <div id="feedbackContainerModalCambiarEstado" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Nuevo Estado</h4>
                <select id="socioEditarEstado">
                    <option value="activo">Activo</option>
                    <option value="suspendido">Suspendido</option>
                    <option value="eliminar">Eliminar Socio</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="socioCambiarEstadoModalBtnCambiar" class="btn btn-primary">Cambiar</button>
        </div>
    </div>

    <!-- Modal ingresar entrega -->
    <div id="socioIngresarEntregaModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="socioIngresarEntregaModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="socioIngresarEntregaModalLabel">Ingresar Pago</h3>
        </div>
        <div class="modal-body">
            <div id="socioIngresarEntregaModalFeedback" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Gramos</h4>
                <input type="text" placeholder="0.00" id="socioIngresarEntregaGramos">
            </div>
            <div class="modalListaRow">
                <h4>Fecha</h4>
                <input type="text" placeholder="" id="socioIngresarEntregaFecha">
            </div>
            <div class="modalListaRow">
                <h4>Variedad</h4>
                <select id="socioIngresarEntregaVariedad">
                    <option value="">- seleccionar variedad -</option>
                    <option value="Mix Automaticas SweetSeeds">Mix Automaticas SweetSeeds</option>
                    <option value="Mix Hortilab">Mix Hortilab</option>
                </select>
            </div>
            <div class="modalListaRow">
                <h4>Notas</h4>
                <textarea style="width: 400px; height: 100px; max-width: 400px; max-height: 100px;"
                          id="socioIngresarEntregaNotas"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button id="socioIngresarEntrega" class="btn btn-primary" onclick="Socio.IngresarEntrega();">Ingresar</button>
        </div>
    </div>

    </body>

<?php } ?>
