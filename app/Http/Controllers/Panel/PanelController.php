<?php

namespace App\Http\Controllers\Panel;


use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PanelController extends Controller
{
    public function index(){


        return view('panel.index');
    }

    public function logs(){
        $logs = Message::where("user_id", auth()->user()->id)->get();
        return view('panel.logs.index', compact("logs"));
    }


    public function profile(){
        $user = auth()->user();
        return view('panel.profile.index', compact("user"));
    }


    public function profileUpdate(Request $request){
        $data = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email," . auth()->user()->id,
            "username" => "required|unique:users,username," . auth()->user()->id,
            "password" => "nullable",
        ]);

        $user = auth()->user();
        $user->name = $data["name"];
        $user->email = $data["email"];
        $user->username = $data["username"];
        if($data["password"] != null){
            $user->password = bcrypt($data["password"]);
        }
        $user->save();
        return redirect()->route("panel.profile.index")->with("success", "Profile updated successfully");
    }



    public function authKey(){
        $user = auth()->user();
        return view('panel.auth_key.index', compact("user"));
    }

    public function authKeyUpdate(){
        $user = auth()->user();

        $randomString = bin2hex(random_bytes(32));
        
        $user->auth_key = $randomString;
        $user->save();
        return redirect()->route("panel.auth_key.index")->with("success", "Auth key updated successfully");
    }
}
