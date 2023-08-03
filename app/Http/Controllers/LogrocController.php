<?php

namespace App\Http\Controllers;

use App\Models\RecintoElectoral;
use Canton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogrocController extends Controller
{
    /**
     * Listado de todo
     * @OA\Get (
     *     path="/api/all",
     *     tags={"Logro C"},
     *      security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="Datos",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="provincia",
     *                         type="string",
     *                         example="Manabi"
     *                     ),
     *                     @OA\Property(
     *                         property="canton",
     *                         type="string",
     *                         example="Chone"
     *                     ),
     *                     @OA\Property(
     *                         property="parroquia",
     *                         type="string",
     *                         example="santa rita"
     *                     ),
     *                     @OA\Property(
     *                         property="recinto",
     *                         type="string",
     *                         example="cascsacas"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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
    /**
     * Listado de todo
     * @OA\Get (
     *     path="/api/cantones",
     *     tags={"Logro C"},
     *      security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="Datos",
     *                 @OA\Items(
     *                     type="object",
     *                      @OA\Property(
     *                         property="canton",
     *                         type="string",
     *                         example="Chone"
     *                     ),
     *                     @OA\Property(
     *                         property="provincia",
     *                         type="string",
     *                         example="Manabi"
     *                     ),
     *
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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
    /**
     * Listado de todo
     * @OA\Get (
     *     path="/api/cantones",
     *     tags={"Logro C"},
     *      security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="Datos",
     *                 @OA\Items(
     *                     type="object",
     *                      @OA\Property(
     *                         property="provincia",
     *                         type="string",
     *                         example="Manabi"
     *                     ),
     *                      @OA\Property(
     *                         property="canton",
     *                         type="string",
     *                         example="Chone"
     *                     ),
     *                     @OA\Property(
     *                         property="parroquia",
     *                         type="string",
     *                         example="san antonio"
     *                     ),
     *
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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
    /**
     * Actualizar la información de un Persona
     * @OA\Put (
     *     path="/api/personas/{id}",
     *     tags={"Persona"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="recinto",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "recinto": "san antonio",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Persona Update."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Campos Vacios",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No se permiten campos vacios."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Id vacio",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="El id no puede estar vacio."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Ya eliminado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="El Registro ya ha sido eliminado anterioemente."),
     *          )
     *      )
     * )
     */
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
