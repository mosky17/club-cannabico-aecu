<?php

require_once(dirname(__FILE__).'/layout.php');
require_once(dirname(__FILE__).'/proc/classes/auth.php');

if(Auth::access_level()<0) { ?>
	<script type="text/javascript">
		Toolbox.GoToLogin();
	</script>
<?php }else{ ?>

<head>
	<script src="scripts1.0.10/index.js"></script>
</head>

<body>
	<div class="container">
		<h2>Lista de Socios</h2>
            <div class="warnings">
                <div id="index-warning-emails" class="alert alert-warning">Algunos de tus socios no tienen una <b>direcci&oacute;n de correo</b> v&aacute;lido, te recomendamos asignarle una individual a cada socio.</div>
                <div id="index-warning-fechanac" class="alert alert-warning">Algunos de tus socios no tienen una <b>fecha de nacimiento</b> asignada, vas a necesitarla para presentar en el IRCCA.</div>
                <div id="index-warning-doc" class="alert alert-warning">Algunos de tus socios no tienen un <b>n&uacute;mero de documento</b> asignado, vas a necesitarlo para presentar en el IRCCA.</div>
            </div>
			<div id="listaSociosControlesContainer" class="controlesLista">
				<div id="listaSociosBtnCrearSocio" class="btn btn-primary">Nuevo Socio</div>
                <!--<div id="listaSociosBtnImportarSocio" class="btn btn-primary">Importar Socio de AECU</div>-->
                <div id="listaSociosBtnArmarListaMails" class="btn btn-success">Armar Lista de Correo</div>
                <!--<div id="listaSociosBtnEnviarEstado" class="btn btn-success" onclick="Index.OpenEnviarEstados();">Enviar Estados</div>-->
                <select class="lista-socios-show" onchange="Index.CambiarSociosAMostrar();">
                    <option value="activos">Mostrar Socios Activos</option>
                    <option value="suspendidos">Mostrar Socios Suspendidos</option>
                </select>
                <div id="exportarListaDropdown" class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        Exportar
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                        <li><a tabindex="-1" href="#" onClick="Index.ExportarListaSociosActivos();">Lista de socios activos</a></li>
                    </ul>
                </div>
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
    <iframe id="exportIframe" src="" style="height:0px;border:0 none;"></iframe>
</body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
