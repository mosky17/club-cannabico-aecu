<?php

//error_reporting(E_ALL);

require_once(dirname(__FILE__).'/classes/socio.php');
require_once(dirname(__FILE__).'/classes/pago.php');
require_once(dirname(__FILE__).'/classes/entrega.php');
require_once(dirname(__FILE__).'/classes/genetica.php');
require_once(dirname(__FILE__).'/classes/recordatorio_deuda.php');

function get_tags(){
    $result = Socio::get_tags();
    echo json_encode($result);
}
function get_socio(){
    $result = Socio::get_socio_hash($_POST['hash']);
    echo json_encode($result);
}

//************** PAGO ********************
function get_pagos_socio(){
    $result = Pago::get_pagos_socio($_POST['id_socio']);
    echo json_encode($result);
}
function get_pago(){
    $result = Pago::get_pago($_POST['id']);
    echo json_encode($result);
}

//************** ENTREGA ********************

function get_entregas_socio(){
    $result = Entrega::get_entregas_socio($_POST['id_socio']);
    echo json_encode($result);
}

//************** GENETICAS ********************

function get_lista_geneticas(){
    $result = Genetica::get_lista_geneticas();
    echo json_encode($result);
}

//************** DEUDA ********************

function get_deudas_socio(){
    $result = RecordatorioDeuda::GetDeudasSocio($_POST['id_socio']);
    echo json_encode($result);
}

//************** PROC ********************

    if($_POST["func"]){
        call_user_func($_POST["func"]);
    }
