<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 

class AuthController extends Controller
{
    public function register(Request $request)
    {
       
        $data = $request->validate([
            'fullname' => 'required',  
            'username' => 'required|unique:users',         
            'contact_number' => 'required',
            'email' => 'required|email|unique:users',
            'user_img' => 'nullable',
            'password'  =>  ['required', 'string'],
            'password_confirmation'  =>  'required|string',            
        ]);

        
        if ($request->password !== $request->password_confirmation) {
            return response()->json(['status' => 'error', 'message' => 'Password does not match!'], 400);
        }        

        $data['password'] = Hash::make($request->password);       
            
        $user = User::create($data);

        if($user){   
            return $this->onSuccessfulLogin($user, false);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Unable to register!'], 400);
        }            
       
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'username'     =>  'required|string',
            'password'  =>  'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) return response()->json(['status' => 'error', 'message' => 'Username does not exist!'], 400);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 400);
        } else {
            if (auth()->attempt($request->only('username', 'password'))) {
                return $this->onSuccessfulLogin($user);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Bad credentials'], 400);
            }
        }
    }

    private function onSuccessfulLogin($user, $isLogin = true)
    {
        $token = $user->createToken('Bearer')->plainTextToken;

        $response = [
            'status'    =>  'success',
            'message'   =>  $isLogin ? 'Login successful!' : "Registration successful!",
            'data'      =>  [
                'user'              =>  $user,
                'token'             =>  $token,
                'uid'               =>  $user->id
            ]
        ];

        return response()->json($response);
    }



    public function getUser(Request $request)
    {
        $response = [
            'status'    =>  'success',
            'message'   =>  'Fetch successful!',
            'data'      =>  [
                'user'              =>  $request->user(),
                'uid'               =>  auth()->id()
            ]
        ];

        return response()->json($response);
    }

    public function logOut(Request $request)
    {
        $user = $request->user();
        if ($user) {
            // $user->tokens()->delete();
            $user->currentAccessToken()->delete();

            return response()->json([
                'status'    =>  'success',
                'message'   =>  'Logged Out'
            ]);
        }
        return response()->json([
            'status'    =>  'error',
            'message'   =>  'User not logged in'
        ], 400);
    }

    public function updateProfile(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'fullname'        => 'required|string|max:255',
        'username'        => 'required|string|max:255',
        'contact_number'  => 'required|string|max:20',
        'email'           => 'required|email|max:255',
        'user_img'        => 'nullable',
        'twitter'         => 'nullable|string|max:255',
        'instagram'       => 'nullable|string|max:255',
        'linkedin'        => 'nullable|string|max:255',
        'github'          => 'nullable|string|max:255',
        'website'         => 'nullable|string|max:255',
        'portfolio'       => 'nullable|string|max:255',
    ]);

    
    if ($request->hasFile('user_img')) {
        $imageData = base64_decode($request->user_img);
        $fileName = $user->fullname . '-' . time() . '.jpg';
        Storage::put("public/files/images/{$fileName}", $imageData);
        $validated['user_img'] = url("storage/files/images/{$fileName}");
    }

   
    $user->update($validated);

    
    return response()->json([
        'status'  => 'success',
        'message' => 'Profile updated successfully!',
        'data'    => $user->fresh() 
    ]);
}


    public function show()
    {
        //
        $userid = auth()->user()->id;
        $user = User::findOrFail($userid);

        if ($user) {

            $response = [
                'status' => 'success',
                'message' => 'User Information fetched successfully',
                'data' => $user
            ];

            return response()->json($response);

        } else {
            $response = [
                'status' => 'failed',
                'message' => 'No view for User found'
            ];
            return response()->json($response, 404);
        }
    }

    public function destroy($id)
    {
        $userid = auth()->user()->id;
        $subject = User::findOrFail($userid);
        $del =  $subject->delete();

        if ($del) {
            $response = [
                'status' => 'success',
                'message' => 'Account deleted successfully',
            ];
            return response()->json($response);
        } else {
            $response = [
                'status' => 'failed',
                'message' => 'unable to delete Account'
            ];
            return response()->json($response, 404);
        }
    }
}
