<?php


namespace app;


class Controller
{
    public static function pfDataCheck($roll, $text)
    {
        if (preg_match_all("/" . $roll . " \((.*?)\)/", $text, $matches)) {
            return self::response('Congrats you passed', 200, 'success', true, false, $matches[1][0]);
        } else {
            if (preg_match_all("/" . $roll . " \{(.*?)\}/", $text, $matches)) {
                return self::response('Sorry you failed', 200, "danger", false, true, $matches[1][0]);
            } else {
                return self::response('Incorrect Roll', 400);
            }
        }
    }

    public static function response($message, $status = 400, $type = 'danger', $pass = false, $fail = false, $result = null)
    {
        return ['type' => $type, 'pass' => $pass, 'fail' => $fail, 'status' => $status, 'result' => $result, 'message' => $message];
    }
}