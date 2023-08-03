<?php

namespace App\Http\Controllers;

use App\Models\RecintoElectoral;
use Canton;
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
        if (empty($request->recinto)) {
            return response()->json([
                'message' => "No se permiten campos vacios"
            ], 400);
        }


        if (empty($id)) {

            return response()->json([
                'message' => "El id no puede estar vacio"
            ], 401);
        }
        $recinto = RecintoElectoral::find($id);
        if ($recinto->estado == false) {
            return response()->json([
                'message' => "El Registro ya ha sido eliminado anterioemente"
            ], 204);
        }

        $recinto->recinto = $request->recinto;

        $recinto->save();
        return response()->json([
            'message' => "Recinto Actualizado con Exito"
        ], 200);
    }

    public function parroquia($id)
    {
        $canton = Canton::find($id);

        $canton->parroquias()->estado = false;
        $canton->save();
        return response()->json([
            'message' => "Parroquias Eliminadas"
        ]);
    }
}
