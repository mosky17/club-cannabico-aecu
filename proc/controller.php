<?php

//error_reporting(E_ALL);

require_once(dirname(__FILE__).'/classes/auth.php');
require_once(dirname(__FILE__).'/classes/socio.php');
require_once(dirname(__FILE__).'/classes/pago.php');
require_once(dirname(__FILE__).'/classes/gasto.php');
require_once(dirname(__FILE__).'/classes/exportar.php');
require_once(dirname(__FILE__).'/classes/entrega.php');

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
function get_tags(){
	$result = Socio::get_tags();
	echo json_encode($result);
}
function create_socio(){
	$result = Socio::create_socio($_POST['numero'], $_POST['nombre'], $_POST['documento'], $_POST['email'], $_POST['fecha_inicio'], $_POST['tags'], $_POST['telefono'], $_POST['observaciones']);
	echo json_encode($result);
}
function update_socio(){
    $result = Socio::update_socio($_POST['id'],$_POST['numero'], $_POST['nombre'], $_POST['documento'], $_POST['email'], $_POST['fecha_inicio'], $_POST['tags'], $_POST['telefono'], $_POST['observaciones']);
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
    $result = Pago::ingresar_pago($_POST['id_socio'],$_POST['valor'],$_POST['fecha_pago'],$_POST['razon'],$_POST['tipo'],$_POST['notas']);
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
