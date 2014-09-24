<?php

require_once(dirname(__FILE__).'/layout.php');
require_once(dirname(__FILE__).'/proc/classes/auth.php');

if(Auth::access_level()<0) { ?>
	<script type="text/javascript">
		Toolbox.GoToLogin();
	</script>
<?php }else{ ?>

<head>
	<script src="scripts1.0.2/index.js"></script>
</head>

<body>
	<div class="container">
		<h2>Lista de Socios</h2>
			<div id="listaSociosControlesContainer" class="controlesLista">
				<div id="listaSociosBtnCrearSocio" class="btn btn-primary">Nuevo Socio</div>
                <!--<div id="listaSociosBtnImportarSocio" class="btn btn-primary">Importar Socio de AECU</div>-->
                <div id="listaSociosBtnArmarListaMails" class="btn btn-success">Armar Lista de Correo</div>
                <!--<div id="listaSociosBtnEnviarEstado" class="btn btn-success" onclick="Index.OpenEnviarEstados();">Enviar Estados</div>-->
                <h5 id="totalRegistrosSocios" class="totalRegistros"></h5>
			</div>
			<div id=feedbackContainer></div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Nombre</th>
						<th>Email</th>
						<th>Fecha Inicio</th>
						<th>Tags</th>
					</tr>
				</thead>
				<tbody id="listaSociosTabla"></tbody>
			</table>
	</div>

    <!-- Modal import from AECU -->
    <div id="listaSociosModalImport" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="listaSociosModalImportLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="listaSociosModalImportLabel">Importar Socio de AECU</h3>
        </div>
        <div class="modal-body">
            <div id="feedbackContainerModalImport" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Numero de Socio</h4>
                <input type="text" placeholder="" id="listaSociosModalImportarNumero">
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button id="listaSociosModalImportarBtn" class="btn btn-primary">Importar</button>
        </div>
    </div>

    <!-- Modal armar lista mails -->
    <div id="socioArmarListaMailsModal" class="modal hide fade" tabindex="-1" role="dialog"
         aria-labelledby="socioArmarListaMailsModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="socioArmarListaMailsModalLabel">Armar Lista de Correo</h3>
        </div>
        <div class="modal-body">
            <div id="feedbackContainerModalArmarListaMails" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Enviarle a:</h4>
                <table id="socioArmarListaMailsModalGruposTable">
                    <tbody></tbody>
                </table>
            </div>
            <div class="modalListaRow">
                <textarea id="socioArmarListaMailsModalLista"></textarea>
                <div id="socioArmarListaMailsModalBtnArmar" class="btn btn-success" onClick="Index.ArmarListaMails();">Armar Lista</div>
                <p id="socioArmarListaMailsModalCommentLista"></p>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        </div>
    </div>

    <!-- Modal enviar estados -->
    <div id="listaSociosModalEstados" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="listaSociosModalEstadosLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="listaSociosModalEstadosLabel">Enviar estados de cuenta</h3>
        </div>
        <div class="modal-body">
            <div id="feedbackContainerModalEstados" class="feedbackContainerModal"></div>
            <div class="modalListaRow">
                <h4>Total pago requerido:</h4>
                <input type="text" placeholder="" id="listaSociosModalEstadosTotal">
            </div>
            <div class="modalListaRow">
                <h4>Texto inicial:</h4>
                <textarea id="listaSociosModalEstadosTexto"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <img src="images/loaderModal.gif" class="loaderModal">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button id="listaSociosModalEstadosEnviarBtn" class="btn btn-primary" onclick="Index.EnviarEstadosDeCuenta();">Enviar</button>
        </div>
    </div>

</body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
