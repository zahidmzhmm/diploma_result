<?php


namespace app;


use Ottosmops\Pdftotext\Extract;
use PdfToText;
use Smalot\PdfParser\Parser;

class Controller
{
    public function uploadFile($data, $file)
    {
        if (isset($file['data'])) {
            $fileName = $file['data']['name'];
            $ex_file_name = explode('_', $fileName);
            $ex_stu_type = explode('.', $ex_file_name[3]);
            $fileTmpName = $file['data']['tmp_name'];
            $semester = $ex_file_name[1];
            $season = $ex_file_name[2];
            $type = $ex_stu_type[0];
            if (file_exists(__DIR__ . '/../file/' . $fileName)) {
                $parser = new Parser();
                $pdf = $parser->parseFile(__DIR__ . '/../file/result_4th_2016_Regulation.pdf');
                $text = $pdf->getText();
                echo $text;
            } else {
                $this::response("File already exists");
            }
        }
    }

    public static function response($message, $type = "error")
    {
        echo json_encode(['message' => $message, 'type' => $type]);
    }
}