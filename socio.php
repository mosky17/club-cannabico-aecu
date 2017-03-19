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
        <script src="scripts1.0.10/socio.js"></script>
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
                    <h4>Fecha Nacimiento</h4>

                    <div id="socioDatosValorFechaNacimiento" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Tel&eacute;fono</h4>

                    <div id="socioDatosValorTelefono" class="socioDatosValor"></div>
                </div>

            </div>
            <div class="span6">
                <div class="socioDatosField">
                    <h4>Fecha Inicio</h4>

                    <div id="socioDatosValorFechaInicio" class="socioDatosValor"></div>
                </div>
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
        <h3 id="socioPagoseTitulo">Pagos</h3>

        <div class="botoneraTopContainer">
            <div class="btn btn-success" title="Ingresar nuevo pago" onclick="Socio.OpenModalNuevoPago();">Agregar
                Pago
            </div>
            <div class="btn btn-danger" title="Nuevo recordatorio de deuda" onclick="Socio.OpenModalNuevaDeuda();">
                Agregar Deuda
            </div>
        </div>

        <!-- Recordatorio Deuda -->
        <div class="socioRecordatorioDeudaContainer"></div>

        <div class="box row-fluid">
            <div class="span12 socioListaContenedor">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor $</th>
                        <th>Fecha Pago</th>
                        <th>Raz&oacute;n</th>
                        <th>Descuento $</th>
                        <th>Notas</th>
                        <th>Tipo</th>
                    </tr>
                    </thead>
                    <tbody id="listaPagosSocioTabla"></tbody>
                </table>
            </div>
        </div>

        <!--Entregas-->
        <h3 id="socioEntregasTitulo">Entregas <i id="socioBtnNuevaEntrega" class="icon-plus-sign-alt socioIconBtnTitle"
                                                 title="Registrar nueva entrega"
                                                 onclick="Socio.OpenModalNuevaEntrega();"></i></h3>

        <div class="box row-fluid">
            <div class="span12 socioListaContenedor">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Peso (gr)</th>
                        <th>Fecha Entregado</th>
                        <th>Variedad</th>
                        <th>Notas</th>
                        <th>Borrar</th>
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
            <div class="modalListaRow rowNuevoPagoRazon">
                <h4>Raz&oacute;n</h4>
                <select id="socioIngresarPagoRazon" onchange="Socio.TogglePagoRazon();">
                    <option value="mensualidad">Mensualidad</option>
                    <option value="matricula">Matr&iacute;cula</option>
                </select>
                <select id="socioIngresarPagoRazonMensualidadMes">
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
                <select id="socioIngresarPagoRazonMensualidadAnio">
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                </select>
            </div>
            <div class="modalListaRow rowFechaNuevoPago">
                <div class="caja">
                    <h4>Fecha</h4>
                    <input type="text" placeholder="" id="socioIngresarPagoFecha" style="margin-left: 43px;width:130px;">
                </div>
                <div class="caja">
                    <h4>Via</h4>
                    <select style="" id="socioIngresarPagoTipo" placeholder="01/12/2013">
                        <option value="transferencia_brou">Transferencia BROU</option>
                        <option value="personalmente">Personalmente</option>
                        <option value="otra">Otra</option>
                    </select>
                </div>
            </div>
            <div class="modalListaRow">
                <h4>Monto $</h4>
                <input style="width: 110px;" type="text" placeholder="0.00" id="socioIngresarPagoValor" onchange="Socio.OnChangeMonto();">
            </div>
            <div class="modalListaRow rowFechaNuevoPago">
                <div class="caja">
                    <h4>Descuento $</h4>
                    <input type="text" placeholder="0.00" id="socioIngresarPagoDescuento" style="margin-left: 23px;width:100px;">
                </div>
                <div class="caja">
                    <h4>Raz&oacute;n desc.</h4>
                    <select style="margin: 0 0 0 10px;" id="socioIngresarPagoRazonDescuento">
                        <option value="Voluntariado">Voluntariado</option>
                        <option value="Resolucion directiva">Resoluci&oacute;n directiva</option>
                        <option value="Otra">Otra</option>
                    </select>
                </div>
            </div>
            <div class="modalListaRow">
                <h4>Notas</h4>
                <textarea style="width: 400px; height: 50px; max-width: 400px;"
                          id="socioIngresarPagoNotas"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="socioIngresarPagoModalBtnIngresar" class="btn btn-primary">Ingresar</button>
        </div>
    </div>

    <!-- Modal ingresar deuda -->
    <div id="socioIngresarDeudaModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="socioIngresarDeudaModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="socioIngresarDeudaModalLabel">Ingresar Deuda</h3>
        </div>
        <div class="modal-body">
            <div id="feedbacksocioIngresarDeudaModal" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Monto $</h4>
                <input style="width: 110px;" type="text" placeholder="0.00" id="socioIngresarDeudaMonto">
            </div>
            <div class="modalListaRow">
                <h4>Raz&oacute;n</h4>
                <textarea id="socioIngresarDeudaRazon"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary" onclick="Socio.IngresarDeuda();">Ingresar</button>
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
                <select id="socioIngresarEntregaVariedad"></select>
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
            <button id="socioIngresarEntrega" class="btn btn-primary" onclick="Socio.IngresarEntrega();">Ingresar
            </button>
        </div>
    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
