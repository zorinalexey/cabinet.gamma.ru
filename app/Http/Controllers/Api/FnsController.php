<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FnsController extends Controller
{

    public function getInn(Request $request)
    {
        $url = "https://service.nalog.ru/inn-proc.do";
        $data = array(
            "fam" => $request->surname,
            "nam" => $request->name,
            "otch" => $request->patronymic,
            "bdate" => $request->birthdate,
            "bplace" => "",
            "doctype" => 21,
            "docno" => preg_replace('~^(\d{2})(\d{2})(\d+)$~u', '$1 $2 $3', $request->docnumber),
            "docdt" => $request->docdate,
            "c" => "innMy",
            "captcha" => "",
            "captchaToken" => ""
        );
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Content-type: application/x-www-form-urlencoded',
                ),
                'content' => http_build_query($data)
            ),
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }
}
