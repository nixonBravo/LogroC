<?php

namespace App\Http\Controllers;

use App\Models\RecintoElectoral;
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
            ->select('provincias.provincia', 'cantones.canton', 'parroquias.parroquia', 'recintoselectorales.recinto')
            ->get();

        return response()->json([
            'Datos' => $datos
        ], 200);
    }

    public function cantones()
    {
        $datos = DB::table('provincias')
            ->join('cantones', 'provincias.id', '=', 'cantones.provincia_id')
            ->select('cantones.canton', 'provincias.provincia')
            ->orderBy('provincias.provincia')
            ->get();

        return response()->json([
            'Datos' => $datos
        ], 200);
    }

    public function recintos()
    {
        $datos = DB::table('provincias')
            ->join('cantones', 'provincias.id', '=', 'cantones.provincia_id')
            ->join('parroquias', 'cantones.id', '=', 'parroquias.canton_id')
            ->join('recintoselectorales', 'parroquias.id', '=', 'recintoselectorales.parroquia_id')
            ->select('provincias.provincia', 'cantones.canton', 'recintoselectorales.recinto')
            ->get();

        return response()->json([
            'Datos' => $datos
        ], 200);
    }

    public function updateRecinto(Request $request, $id)
    {
        
    }

    public function parroquia()
    {
    }
}
