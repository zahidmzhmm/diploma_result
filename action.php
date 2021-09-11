<?php

include('PdfToText/PdfToText.phpclass');
include_once "Controller.php";

use app\Controller;

$controller = new Controller();
if (isset($_GET['createPdfToText']) && isset($_GET['admin'])) {
    if ($_GET['admin'] == 'admin12123') {
        createPdfToText("result_4th_2016_Regulation");
        createPdfToText("result_4th_Tour");
        createPdfToText("result_6th_2010_Regulation");
        createPdfToText("result_6th_2016_Regulation");
        createPdfToText("result_6th_Tour");
        createPdfToText("result_8th_2010_Regulation");
        createPdfToText("result_8th_2016_Regulation");
        createPdfToText("result_8th_Irr_2010_Regulation");
        createPdfToText("result_8th_Tour");
        echo "Data Imported Success";
        exit;
    }
}
$result_4th_2016_Regulation = file_get_contents('pdf_text/result_4th_2016_Regulation.txt');
if (isset($_POST['website_ress'])) {
    if (isset($_POST['roll']) && !empty($_POST['roll'])) {
        $roll = $_POST['roll'];
        $passStudent = $controller::pfDataCheck($roll, $result_4th_2016_Regulation);
        echo '<pre>';
        var_dump($passStudent);
        exit;
    }
}
if (isset($_SERVER['HTTP_X_API_KEY']) && !empty($_SERVER['HTTP_X_API_KEY']) && isset($_GET['api_ress'])) {
    if ($_GET['roll'] && !empty($_GET['roll'])) {
        if ($_SERVER['HTTP_X_API_KEY'] === 'admin12123') {
            $response = $controller::pfDataCheck($_GET['roll'], $result_4th_2016_Regulation);
            echo json_encode($response);
            exit;
        }
    }
}
function createPdfToText($pdfFile)
{
    if (!file_exists('pdf_text/' . $pdfFile . '.text')) {
        $pdf = new PdfToText('pdf/' . $pdfFile . '.pdf');
        $fopen = fopen("pdf_text/" . $pdfFile . ".txt", "w") or die("Unable to open file!");
        $text = $pdf->Text;
        fwrite($fopen, $text);
        fclose($fopen);
    }
}