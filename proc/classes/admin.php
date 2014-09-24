<?php
require_once(dirname(__FILE__) . '/auth.php');

Auth::connect();

class Admin {

    public $id;
    public $nombre;
    public $email;
    public $clave;
    public $permiso_pagos;

    function __construct($id, $nombre, $email, $clave, $permiso_pagos) {

        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->clave = $clave;
        $this->permiso_pagos = $permiso_pagos;

    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new Admin($row['id'], $row['nombre'], $row['email'], $row['secreto'], $row['permiso_pagos']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_lista_admins()
    {
        $q = mysql_query("SELECT * FROM admins ORDER BY id;");
        return Admin::mysql_to_instances($q);
    }

    static public function ingresar_admin($nombre, $email, $clave, $permiso_pagos){

        $q = mysql_query("SELECT * FROM admins WHERE email='". $nombre ."'");
        if(mysql_num_rows($q) > 0){
            return array("error" => "Ya existe un administrador con ese usuario/email");
        }

        $q = mysql_query("INSERT INTO admins (id, nombre, email, secreto, permiso_pagos) VALUES (null," .
            '\'' . htmlspecialchars(mysql_real_escape_string($nombre)) . '\',' .
            '\'' . htmlspecialchars(mysql_real_escape_string($email)) . '\',' .
            'MD5(\''.$clave.'\'), ' .
            $permiso_pagos . ')');

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al ingresar admin");
        }

    }

    static public function update_admin($id, $nombre, $email, $clave, $permiso_pagos){

        $q = mysql_query("SELECT * FROM admins WHERE email='". $nombre ."' AND id<>".$id);
        if(mysql_num_rows($q) > 0){
            return array("error" => "Ya existe un administrador con ese usuario/email");
        }

        $q = mysql_query("UPDATE admins SET nombre='" . $nombre . "'," .
            "email='" . $email . "'," .
            "secreto=MD5('" . $clave . "')," .
            "permiso_pagos='" . $permiso_pagos . "' WHERE id=".$id);

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al modificar admin");
        }

    }

    static public function delete_admin($id){

        $q = mysql_query("DELETE FROM admins WHERE id=".$id);

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al borrar admin");
        }

    }


} 