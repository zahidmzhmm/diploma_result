<?php


namespace app;


use Smalot\PdfParser\Parser;

class Controller
{
    public function uploadFile($file)
    {
        if (isset($file['data']) && !empty($file['data']['name'])) {
            $fileName = $file['data']['name'];
            $ex_file_name = explode('_', $fileName);
            $ex_stu_type = explode('.', $ex_file_name[3]);
            $fileTmpName = $file['data']['tmp_name'];
            $semester = $ex_file_name[1];
            $season = $ex_file_name[2];
            $type = $ex_stu_type[0];
            if (!file_exists(__DIR__ . '/../file/' . $fileName)) {
                move_uploaded_file($fileTmpName, __DIR__ . '/../file/' . $fileName);
            }
            $parser = new Parser();
            $pdf = $parser->parseFile(__DIR__ . '/../file/' . $fileName);
            $text = $pdf->getText();
            $fopen = fopen(__DIR__ . "/../file/text/" . $season . '-' . $semester . '-' . $type . ".txt", "w") or die("Unable to open file!");
            $count = $this->rows("select id from `section` where `probidhan`='$season' AND `semester`='$semester' AND `type`='$type'");
            if ($count === 0) {
                $this->query("insert into `section` (`probidhan`,`semester`,`type`) values ('$season','$semester','$type')");
            }
            if (fwrite($fopen, $text)) {
                fclose($fopen);
                self::response("Success", 'success', 200);
            } else {
                fclose($fopen);
                self::response("Something went wrong", 'error');
            }
        }
    }

    public function fetchResult($data)
    {
        if (isset($data['roll']) && isset($data['probidhan']) && isset($data['semester']) && isset($data['type']) && !empty($data['roll']) && !empty($data['probidhan']) && !empty($data['semester']) && !empty($data['type'])) {
            $text = file_get_contents(__DIR__ . '/../file/text/' . $data['probidhan'] . '-' . $data['semester'] . '-' . $data['type'] . '.txt');
            if ($text) {
                self::pfDataCheck($data['roll'], $text);
            } else {
                self::response("Data not found!");
            }
        } else {
            self::response("All Field is required");
        }
    }

    public function getFields()
    {
        $data = mysqli_fetch_all($this->query("select * from `section`"), MYSQLI_ASSOC);
        $count = count($data);
        if ($count > 0) {
            self::response("Success", 'success', 200, $data);
        } else {
            self::response("Empty");
        }
    }

    public static function response($message, $type = "danger", $status = 405, $data = [])
    {
        echo json_encode(['message' => $message, 'type' => $type, 'status' => $status, 'data' => $data]);
    }

    public static function pfDataCheck($roll, $text)
    {
        if (preg_match_all("/" . $roll . " \((.*?)\)/", $text, $matches)) {
            self::response('Congrats you passed', 'success', 200, $matches[1][0]);
        } else {
            if (preg_match_all("/" . $roll . " \{(.*?)\}/", $text, $matches)) {
                self::response('Sorry you failed', "danger", 200, $matches[1][0]);
            } else {
                self::response('Incorrect Roll', 400);
            }
        }
    }

    public static function con()
    {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            self::response("Database connection failed");
        }
        return $conn;
    }

    public function query($sql)
    {
        return mysqli_query(self::con(), $sql);
    }

    public function rows($sql)
    {
        return mysqli_num_rows($this->query($sql));
    }
}