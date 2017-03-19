<?php

//error_reporting(E_ALL);

require_once(dirname(__FILE__).'/classes/auth.php');
require_once(dirname(__FILE__).'/classes/socio.php');
require_once(dirname(__FILE__).'/classes/pago.php');
require_once(dirname(__FILE__).'/classes/gasto.php');
require_once(dirname(__FILE__).'/classes/exportar.php');
require_once(dirname(__FILE__).'/classes/entrega.php');
require_once(dirname(__FILE__).'/classes/admin.php');
require_once(dirname(__FILE__).'/classes/genetica.php');
require_once(dirname(__FILE__).'/classes/informe_cosecha.php');
require_once(dirname(__FILE__).'/classes/recordatorio_deuda.php');
require_once(dirname(__FILE__).'/classes/dato.php');

//************** AUTH ********************

function login(){
	$username = htmlspecialchars(trim($_POST['email']));
	$passwd = htmlspecialchars(trim($_POST['passwd']));
	//$remember = htmlspecialchars(trim($_POST['remember']));
	echo json_encode(Auth::login($username,$passwd,false));
}
function logout(){
    $result = Auth::logout();
    echo json_encode($result);
}

//************** SOCIO ********************

function get_lista_socios(){
	$result = Socio::get_lista_socios();
	echo json_encode($result);
}
function get_socios_activos(){
    $result = Socio::get_socios_activos();
    echo json_encode($result);
}
function get_socios_suspendidos(){
    $result = Socio::get_socios_suspendidos();
    echo json_encode($result);
}
function get_tags(){
	$result = Socio::get_tags();
	echo json_encode($result);
}
function create_socio(){
	$result = Socio::create_socio($_POST['numero'],
        $_POST['nombre'],
        $_POST['documento'],
        $_POST['email'],
        $_POST['fecha_inicio'],
        $_POST['tags'],
        $_POST['telefono'],
        $_POST['observaciones'],
        $_POST['fecha_nacimiento']);
	echo json_encode($result);
}
function update_socio(){
    $result = Socio::update_socio($_POST['id'],$_POST['numero'], $_POST['nombre'], $_POST['documento'], $_POST['email'], $_POST['fecha_inicio'], $_POST['tags'], $_POST['telefono'], $_POST['observaciones'], $_POST['fecha_nacimiento']);
    echo json_encode($result);
}
function get_socio(){
	$result = Socio::get_socio($_POST['id']);
	echo json_encode($result);
}
function importar_socio_aecu(){
    $result = Socio::importar_socio_aecu($_POST['numero']);
    echo json_encode($result);
}
function eliminar_socio(){
    $result = Socio::eliminar_socio($_POST['id_socio']);
    echo json_encode($result);
}
function update_estado_socio(){
    $result = Socio::update_estado_socio($_POST['id_socio'],$_POST['activo']);
    echo json_encode($result);
}
function get_lista_mails(){
    $result = Socio::get_lista_mails($_POST['all'],$_POST['tags']);
    echo json_encode($result);
}
function send_estados_de_cuenta(){
    $result = Socio::send_estados_de_cuenta($_POST['total'],htmlspecialchars(trim($_POST['texto'])));
    echo json_encode($result);
}

//************** PAGO ********************

function ingresar_pago(){
    $result = Pago::ingresar_pago($_POST['id_socio'],$_POST['valor'],$_POST['fecha_pago'],
        $_POST['razon'],$_POST['tipo'],$_POST['notas'],$_POST['descuento'],$_POST['descuento_json']);
    echo json_encode($result);
}
function salvar_pago_modificar(){
    $result = Pago::salvar_pago_modificar($_POST['id'],$_POST['razon'],$_POST['descuento'],$_POST['descuento_json']);
    echo json_encode($result);
}

function get_pagos_socio(){
    $result = Pago::get_pagos_socio($_POST['id_socio']);
    echo json_encode($result);
}
function get_lista_pagos(){
    $result = Pago::get_lista_pagos();
    echo json_encode($result);
}
function get_pago(){
    $result = Pago::get_pago($_POST['id']);
    echo json_encode($result);
}
function cancelar_pago(){
    $result = Pago::cancelar_pago($_POST['id']);
    echo json_encode($result);
}

//************** LOG ********************

function get_lista_logs(){
    $result = Log::get_lista_logs();
    echo json_encode($result);
}

//************** GASTO ********************

function get_gasto(){
    $result = Gasto::get_gasto($_POST['id']);
    echo json_encode($result);
}
function cancelar_gasto(){
    $result = Gasto::cancelar_gasto($_POST['id']);
    echo json_encode($result);
}function update_rubro_gasto(){
    $result = Gasto::update_rubro_gasto($_POST['id'],$_POST['rubro']);
    echo json_encode($result);
}
function ingresar_gasto(){
    $result = Gasto::ingresar_gasto($_POST['valor'],$_POST['fecha_pago'],$_POST['razon'],$_POST['notas'],$_POST['rubro']);
    echo json_encode($result);
}
function get_lista_gastos(){
    $result = Gasto::get_lista_gastos();
    echo json_encode($result);
}
function get_totales(){
    $result = Gasto::get_totales();
    echo json_encode($result);
}

//************** ENTREGA ********************

function get_entregas_socio(){
    $result = Entrega::get_entregas_socio($_POST['id_socio']);
    echo json_encode($result);
}
function get_lista_entregas(){
    $result = Entrega::get_lista_entregas();
    echo json_encode($result);
}
function ingresar_entrega(){
    $result = Entrega::ingresar_entrega($_POST['fecha'],$_POST['id_socio'],$_POST['gramos'],$_POST['variedad'],$_POST['notas']);
    echo json_encode($result);
}
function cancelar_entrega(){
    $result = Entrega::cancelar_entrega($_POST['id']);
    echo json_encode($result);
}


//************** ADMINS ********************

function get_lista_admins(){
    $result = Admin::get_lista_admins();
    echo json_encode($result);
}
function ingresar_admin(){
    $result = Admin::ingresar_admin($_POST['nombre'],$_POST['email'],$_POST['clave'],$_POST['permiso_pagos']);
    echo json_encode($result);
}
function update_admin(){
    $result = Admin::update_admin($_POST['id'],$_POST['nombre'],$_POST['email'],$_POST['clave'],$_POST['permiso_pagos']);
    echo json_encode($result);
}
function delete_admin(){
    $result = Admin::delete_admin($_POST['id']);
    echo json_encode($result);
}

//************** GENETICAS ********************

function get_lista_geneticas(){
    $result = Genetica::get_lista_geneticas();
    echo json_encode($result);
}
function ingresar_genetica(){
    $result = Genetica::ingresar_genetica($_POST['nombre'],$_POST['origen'],$_POST['detalles']);
    echo json_encode($result);
}
function update_genetica(){
    $result = Genetica::update_genetica($_POST['id'],$_POST['nombre'],$_POST['origen'],$_POST['detalles']);
    echo json_encode($result);
}
function delete_genetica(){
    $result = Genetica::delete_genetica($_POST['id']);
    echo json_encode($result);
}

function get_producido_por_genetica(){
    $result = Genetica::get_producido_por_genetica($_POST['id']);
    echo json_encode($result);
}

//************** INFORMES COSECHA ********************
function get_informes_cosecha(){
    $result = InformeCosecha::get_lista_informes();
    echo json_encode($result);
}
function ingresar_informe_cosecha(){
    $result = InformeCosecha::ingresar_informe($_POST['fecha'],
                                            $_POST['id_genetica'],
                                            $_POST['cantidad_plantas'],
                                            $_POST['peso_total_fresco'],
                                            $_POST['peso_total_seco'],
                                            $_POST['lote'],
                                            $_POST['responsable_tecnico'],
                                            $_POST['responsable_produccion'],
                                            $_POST['aclaraciones']);

    echo json_encode($result);
}
function borrar_informe_cosecha(){
    $result = InformeCosecha::borrar_informe($_POST['id']);
    echo json_encode($result);
}

function exportar_informe_cosecha(){
    InformeCosecha::exportar_informes($_GET['ids'],$_GET['periodo']);
}

//************** DATOS ********************

function update_dato(){
    $result = Dato::actualizar_dato($_POST['codigo'],$_POST['valor']);
    echo json_encode($result);
}

//************** DEUDA ********************

function get_all_deudas(){
    $result = RecordatorioDeuda::GetAllDeudas();
    echo json_encode($result);
}
function get_deudas_socio(){
    $result = RecordatorioDeuda::GetDeudasSocio($_POST['id_socio']);
    echo json_encode($result);
}
function ingresar_deuda(){
    $result = RecordatorioDeuda::IngresarDeuda($_POST['id_socio'],$_POST['monto'],$_POST['razon']);
    echo json_encode($result);
}
function cancelar_deuda(){
    $result = RecordatorioDeuda::CancelarDeuda($_POST['id']);
    echo json_encode($result);
}

function generar_hash(){
    $socio =Socio::get_socio($_POST['id']);
    $hash = $socio->generate_hash();
    echo $hash;
}

function ingresar_cuota_costo(){
    $cuotaCostos = Pago::ingresar_cuota_costo($_POST['valor'],$_POST['fecha_inicio'],$_POST['fecha_fin']);
    echo json_encode($cuotaCostos);
}
function borrar_cuota_costo(){
    $result = Pago::delete_cuota_costo($_POST['id']);
    echo json_encode($result);
}
function get_costos_cuotas(){
    $result = Pago::get_cuota_costos();
    echo json_encode($result);
}


//************** EXPORTAR ********************

function exportar_pagos_por_socio(){
    Exportar::exportar_pagos_por_socio();
}
function exportar_caja(){
    Exportar::exportar_caja();
}
function exportar_pago_total_por_socio(){
    Exportar::exportar_pago_total_por_socio();
}
function exportar_pagos_por_mes(){
    Exportar::exportar_pagos_por_mes();
}
function exportar_socios_activos(){
    Exportar::exportar_socios_activos();
}
function exportar_deudas(){
    Exportar::exportar_deudas();
}

function exportar_descuentos_por_socio(){
    Exportar::exportar_descuentos_por_socio();
}

//************** PROC ********************

//auth
if(Auth::access_level()>=0){

	if($_POST["func"]){
		call_user_func($_POST["func"]);
	}else{
        if($_GET["exportar"]){
            call_user_func($_GET["exportar"]);
        }
    }
}else{
	if($_POST["func"]){
		if($_POST["func"] == 'login'){
			login();
		}
	}
}
