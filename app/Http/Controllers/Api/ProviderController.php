<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;
use Validator;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all();
        if ($providers->count() > 0) {
            return response()->json([
                'status' => 200,
                'providers' => $providers
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Records Found'
            ], 404);
        }

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:191',
            'password' => 'required|string|max:191'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);

        } else {
            $provider = Provider::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);
            if ($provider) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Provider added successfully!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'messsage' => 'Something went wrong!'
                ], 500);
            }
        }
    }

    public function show($id)
    {
        $provider = Provider::find($id);
        if ($provider) {
            return response()->json([
                'status' => 200,
                'provider' => $provider
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such provider found!'
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $provider = Provider::find($id);
        if ($provider) {
            $provider->username = $request->username;
            $provider->password = bcrypt($request->password);
            $provider->save();
            return response()->json([
                'status' => 200,
                'message' => 'Provider updated successfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such provider found!'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $provider = Provider::find($id);
        if ($provider) {
            $provider->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Provider deleted successfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such provider found!'
            ], 404);
        }

    }
}
