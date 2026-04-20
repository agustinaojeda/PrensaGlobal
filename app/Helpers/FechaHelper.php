<?php

namespace App\Helpers;

class FechaHelper
{
    public static function fechaString($fecha)
    {
        if (empty($fecha)) return "Sin fecha";

        $meses = [
            "01" => "enero",
            "02" => "febrero",
            "03" => "marzo",
            "04" => "abril",
            "05" => "mayo",
            "06" => "junio",
            "07" => "julio",
            "08" => "agosto",
            "09" => "septiembre",
            "10" => "octubre",
            "11" => "noviembre",
            "12" => "diciembre"
        ];

        $timestamp = strtotime($fecha);
        $dia = (int)date("d", $timestamp);
        $mes = $meses[date("m", $timestamp)];
        $anio = date("Y", $timestamp);

        return "$dia de $mes de $anio";
    }
}
