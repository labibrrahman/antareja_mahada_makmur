<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "status" => false,
                    'error' => 'Username and password do not match'
                ], 400);
            }else{
                $query = User::join('departments', 'departments.id', '=', 'users.departement_id')->get('users.*','department.department');
                $success = true;
                $message = "here is data";
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        $query = $query->map(function($append_data) use($token) {
            $append_data->token = $token;
            return $append_data;
        });
          
        return response()->json([
            "status" => $success,
            "message" => $message,
            "data" => $query
        ]);
        // return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'full_name' => 'required|string',
            'handphone' => 'required|string',
            'departement_id' => 'required|int|exists:App\Models\Departement,id' 
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => false,
                "message" => $validator->errors(),
            ]);
        }

        $user = User::create([
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
            'full_name' => $request->get('full_name'),
            'handphone' => $request->get('handphone'),
            'departement_id' => $request->get('departement_id'),
        ]);
        dd($user->all());

        $token = JWTAuth::fromUser($user);

        $user = $user->map(function($append_data) use($token) {
            $append_data->token = $token;
            return $append_data;
        });

        return response()->json([
            "status" => true,
            "message" => "User created successfully.",
            "data" => compact('user')
        ]);
    }

    public function changePassword(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'status' => false
            ]);
        }

        // $asset = Asset::find($id);   
        // $asset->asset_number = $input['asset_number'];
        // $asset->asset_serial_number = $input['asset_serial_number'];
        // $asset->asset_capitalized_on = $input['asset_capitalized_on'];
        // $asset->asset_manager = $input['asset_manager'];
        // $asset->asset_desc = $input['asset_desc'];
        // $asset->asset_quantity = $input['asset_quantity'];
        // $asset->asset_po = $input['asset_po'];
        // $asset->asset_status = $input['asset_status'];
        // $asset->departement_id = $input['departement_id'];
        // $asset->category_id = $input['category_id'];
        // $asset->count_id = $input['count_id'];
        // $asset->save();

        return response()->json([
            'status' => true,
            'message' => 'updated successfully'
        ]);
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }
}