<?php

require_once(dirname(__FILE__) . '/auth.php');
require_once(dirname(__FILE__) . '/pago.php');
require_once(dirname(__FILE__) . '/../fpdf/fpdf.php');
require_once(dirname(__FILE__) . '/genetica.php');

Auth::connect();

class InformeCosecha {

    public $id;
    public $fecha;
    public $id_genetica;
    public $cantidad_plantas;
    public $peso_total_fresco;
    public $peso_total_seco;
    public $lote;
    public $responsable_tecnico;
    public $responsable_produccion;
    public $aclaraciones;
    public $borrado;

    function __construct($id, $fecha, $id_genetica, $cantidad_plantas, $peso_total_fresco, $peso_total_seco,
                         $lote, $responsable_tecnico, $responsable_produccion, $aclaraciones, $borrado){

        $this->id = $id;
        $this->fecha = $fecha;
        $this->id_genetica = $id_genetica;
        $this->cantidad_plantas = $cantidad_plantas;
        $this->peso_total_fresco = $peso_total_fresco;
        $this->peso_total_seco = $peso_total_seco;
        $this->lote = $lote;
        $this->responsable_tecnico = $responsable_tecnico;
        $this->responsable_produccion = $responsable_produccion;
        $this->aclaraciones = $aclaraciones;
        $this->borrado = $borrado;
    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new InformeCosecha($row['id'], $row['fecha'], $row['id_genetica'], $row['cantidad_plantas'],
                    $row['peso_total_fresco'], $row['peso_total_seco'], $row['lote'], $row['responsable_tecnico'],
                    $row['responsable_produccion'], $row['aclaraciones'], $row['borrado']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_lista_informes()
    {
        $q = mysql_query("SELECT * FROM informes_cosecha WHERE borrado=0 ORDER BY fecha");
        return InformeCosecha::mysql_to_instances($q);
    }

    static public function ingresar_informe($fecha, $id_genetica, $cantidad_plantas, $peso_total_fresco, $peso_total_seco,
                                            $lote, $responsable_tecnico, $responsable_produccion, $aclaraciones){

        $q = mysql_query("INSERT INTO informes_cosecha (fecha, id_genetica, cantidad_plantas, peso_total_fresco, " .
            "peso_total_seco, lote, responsable_tecnico, responsable_produccion, aclaraciones) VALUES (" .
            "'" . htmlspecialchars(mysql_real_escape_string($fecha)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($id_genetica)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($cantidad_plantas)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($peso_total_fresco)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($peso_total_seco)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($lote)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($responsable_tecnico)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($responsable_produccion)) . "', " .
            "'" . htmlspecialchars(mysql_real_escape_string($aclaraciones)) . "')");

        if (mysql_affected_rows() == 1) {
            return array("ok" => true);
        } else {
            return array("error" => "Informe no ingresado");
        }

    }

    static public function borrar_informe($id)
    {
        $q = mysql_query("UPDATE informes_cosecha SET borrado=1 WHERE id=".$id);
        if (mysql_affected_rows() == 1) {
            return array("ok" => true);
        } else {
            return array("error" => "Informe no borrado");
        }
    }

    static public function exportar_informes($idsString,$periodo){

        $ids = explode(",",$idsString);
        $query_operators = "";
        for($i=0;$i<count($ids);$i++){
            if($i > 0){
                $query_operators .= ' OR ';
            }
            $query_operators .= 'id=' . $ids[$i];
        }
        $q = mysql_query("SELECT * FROM informes_cosecha WHERE " . $query_operators . " ORDER BY fecha");
        $informes = InformeCosecha::mysql_to_instances($q);

        //datos geneticas
        $geneticas = Genetica::get_lista_geneticas();
        $geneticasIndex = array();
        for($i=0;$i<count($geneticas);$i++){
            $geneticasIndex[$geneticas[$i]->id] = $geneticas[$i]->nombre;
        }

        //start PDF
        $pdf = new FPDF();
        $pdf->AddPage("P","A4");
        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(10);

        //Title
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,15,'Informe de Cosecha',"",1,'C');
        $pdf->Ln();
        $pdf->Ln();

        //Clud data
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(15,6,'Club:',0);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,6,$GLOBALS["name"],0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(43,6,iconv("UTF-8", "CP1250//TRANSLIT", 'Personeria Jurídica:'),0);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,6,$GLOBALS["personeria_juridica"],0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(43,6,'Identificador IRCCA:',0);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,6,$GLOBALS["id_ircca"],0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(20,6,iconv("UTF-8", "CP1250//TRANSLIT", 'Período:'),0);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,6,$periodo,0,1);
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,6,'Listado por variedad:',0,1);


        //tabla cosechas
        $pdf->SetFont('Arial','B',8);
        $pdf->MultiCell(45,4,"\n" . iconv("UTF-8", "CP1250//TRANSLIT", "Genética"),1,0,'L');
        $pdf->MultiCell(17,4,'Cantidad plantas',1,0,'L');
        $pdf->MultiCell(20,4,'Total fresco (gr)',1,0,'L');
        $pdf->MultiCell(20,4,'Total seco (gr)',1,0,'L');
        $pdf->MultiCell(20,4,'x planta fresco (gr)',1,0,'L');
        $pdf->MultiCell(20,4,'x planta seco (gr)',1,0,'L');
        $pdf->MultiCell(30,4,"\n" . 'Lote',1,0,'L');
        $pdf->MultiCell(0,4,"\n" . 'Fecha corte',1,1,"L");

        $pdf->SetFont('Arial','',8);

        $total_plantas  = 0;
        $total_seco  = 0;

        for($i=0;$i<count($informes);$i++){

            //parse date
            $dateParts = explode('-',$informes[$i]->fecha);

            $pdf->Cell(45,4,iconv("UTF-8", "CP1250//TRANSLIT", $geneticasIndex[$informes[$i]->id_genetica]),1);
            $pdf->Cell(17,4,$informes[$i]->cantidad_plantas,1);
            $total_plantas += (int)$informes[$i]->cantidad_plantas;
            $pdf->Cell(20,4,(float)$informes[$i]->peso_total_fresco,1);
            $pdf->Cell(20,4,(float)$informes[$i]->peso_total_seco,1);
            $total_seco += (float)$informes[$i]->peso_total_seco;
            $pdf->Cell(20,4,round((float)$informes[$i]->peso_total_fresco / (float)$informes[$i]->cantidad_plantas,2),1);
            $pdf->Cell(20,4,round((float)$informes[$i]->peso_total_seco / (float)$informes[$i]->cantidad_plantas,2),1);
            $pdf->Cell(30,4,$informes[$i]->lote,1);
            $pdf->Cell(0,4,$dateParts[2] . "/" . $dateParts[1] . "/" . $dateParts[0],1,1);
        }

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(38,6,'Total de plantas:',0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,6,$total_plantas,0,1);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(27,6,'Total seco:',0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,6,$total_seco . " gr.",0,1);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,6,'Aclaraciones:',0,1);


        //firmas
        $pdf->SetY(-50);
        $width = $pdf->w - 20;

        $pdf->SetFont('Arial','',6);
        $pdf->Cell($width/2,6,'Firma',0,0,'C');
        $pdf->Cell($width/2,6,'Firma',0,1,'C');
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell($width/2,5,iconv("UTF-8", "CP1250//TRANSLIT", $informes[count($informes)-1]->responsable_tecnico),'',0,'C');
        $pdf->Cell($width/2,5,iconv("UTF-8", "CP1250//TRANSLIT", $informes[count($informes)-1]->responsable_produccion),'',1,'C');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell($width/2,6,iconv("UTF-8", "CP1250//TRANSLIT", "Responsable Técnico"),'',0,'C');
        $pdf->Cell($width/2,6,iconv("UTF-8", "CP1250//TRANSLIT", "Responsable de Producción"),'',1,'C');

        $pdf->Output();
    }

} 