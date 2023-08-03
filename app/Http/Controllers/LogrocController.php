<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogrocController extends Controller
{
    public function all()
    {
        $datos = DB::table('provincias')
            ->join('cantones', 'provincias.id', '=', 'cantones.provincia_id')
            ->join('parroquias', 'cantones.id', '=', 'parroquias.canton_id')
            ->join('recintoselectorales', 'parroquias.id', '=', 'recintoselectorales.parroquia_id')
            ->select('provincias.*', 'cantones.*', 'parroquias.*', 'recintoselectorales.*')
            ->get();

        return response()->json([
            'Datos' => $datos
        ], 200);
    }

    public function cantones()
    {
    }

    public function recintos()
    {
    }

    public function updateRecinto()
    {
    }

    public function parroquia()
    {
    }
}
