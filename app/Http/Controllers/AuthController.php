<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Info(
 *             title="Logro C",
 *             version="1.0",
 *             description="Api con Datos de Provincias, Cantones Parroquias y Recintos Electorales"
 * )
 *
 * @OA\Server(url="http://127.0.0.1:8000")
 *
 *  @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\POST(
     *  tags={"Sanctum Authentication"},
     *  summary="Registro",
     *  description="Este Endpoint Registra un Usuario.",
     *  path="/api/auth/register",
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", example="IOS"),
     *              @OA\Property(property="email", type="string", example="usuario@example.org"),
     *              @OA\Property(property="password", type="string", example="12345678"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Usuario Registrado con Exito.",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="boolean", example="true"),
     *       @OA\Property(property="message", type="string", example="User Created Successfully"),
     *       @OA\Property(property="token", type="string", example="1|Xz7LHIKavO8qAGvx5CJO2iir8DYVbXOkixCsXOxT")
     *    )
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="Campos Vacios.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Existen Campos Vacios.")
     *    )
     *  ),
     * )
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Existen campos vacios',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
