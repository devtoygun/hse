<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

use App\Models\User;
use App\Models\ActiveSession;
use App\Models\LastLogin;

class AuthController extends Controller
{
    public function login(){
        return view('layout.auth.login');
    }
    public function reset_password(){
        return view('layout.auth.reset-password');
    }
    public function screen_lock(){
        return view('layout.auth.screen-lock');
    }

    public function confirm_login(Request $request){
        // Giriş alanlarını kontrol et
        if (empty($request->email) || empty($request->password)) {
            return response()->json(["type" => "warning", "message" => "E-posta ve şifre girin!"]);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(["type" => "warning", "message" => "Geçerli bir e-posta girin!"]);
        }

        // "remember" parametresini sildik, sadece email ve password alıyoruz
        $credentials = $request->only('email', 'password');

        // attempt fonksiyonundaki ikinci parametreyi false yaparak kalıcı oturumu kapattık
        if (auth()->attempt($credentials, false)) {
        $user = auth()->user();
        $agent = new Agent();

        $deviceType = 'Desktop';
        if ($agent->isTablet()) {
            $deviceType = 'Tablet';
        } elseif ($agent->isMobile()) {
            $deviceType = 'Mobile';
        }

        \App\Models\ActiveSession::create([
            'user_id'    => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device'     => $deviceType,         
            'platform'   => $agent->platform(),        
            'browser'    => $agent->browser(),        
        ]);

        \App\Models\LastLogin::create([
            'user_id'    => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device'     => $deviceType,         
            'platform'   => $agent->platform(),        
            'browser'    => $agent->browser(),        
        ]);

            $welcomeMessage = "Hoş geldin, " . $user->firstname . "!";

            // İlk giriş mi yoksa normal giriş mi kontrolü
            if ($user->is_first_login == 1) {
                return response()->json([
                    "type" => "success", 
                    "message" => $welcomeMessage . " Lütfen şifrenizi güncelleyin.",
                    "redirect" => url('/auth/reset-password')
                ]);
            }

            return response()->json([
                "type" => "success", 
                "message" => $welcomeMessage,
                "redirect" => url('/app')
            ]);
        }

        // Kimlik doğrulama başarısız
        return response()->json([
            "type" => "error", 
            "message" => "E-posta veya şifre hatalı!"
        ]);
    }
}
