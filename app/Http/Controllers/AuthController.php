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
        // 1. Temel giriş kontrolleri
        if (empty($request->email) || empty($request->password)) {
            return response()->json(["type" => "warning", "message" => "E-posta ve şifre girin!"]);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(["type" => "warning", "message" => "Geçerli bir e-posta girin!"]);
        }

        // Kullanıcıyı manuel bulalım (Status kontrolü için önce giriş yapmadan bakıyoruz)
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(["type" => "error", "message" => "Kullanıcı bulunamadı!"]);
        }

        // 2. Status Kontrolü (0 ise giriş yasak)
        if ($user->status == 0) {
            return response()->json(["type" => "error", "message" => "Hesabınız pasif durumdadır. Lütfen yöneticiyle iletişime geçin."]);
        }

        // 3. Kimlik Doğrulama
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials, false)) {
            
            $agent = new Agent();
            $deviceType = $agent->isTablet() ? 'Tablet' : ($agent->isMobile() ? 'Mobile' : 'Desktop');
            
            $currentIp = $request->ip();
            $currentBrowser = $agent->browser();
            $currentPlatform = $agent->platform();

            // 4. Aktif Oturum ve Last Login Kontrolü
            // Hem ActiveSession hem de LastLogin (çıkış yapılmamış olanlar) için kontrol
            $existingSession = \App\Models\ActiveSession::where('user_id', $user->id)->first();
            
            if ($existingSession) {
                // Cihaz/Tarayıcı/IP bilgilerinden biri bile farklı mı?
                if ($existingSession->ip_address !== $currentIp || 
                    $existingSession->browser !== $currentBrowser || 
                    $existingSession->platform !== $currentPlatform) {
                    
                    auth()->logout(); // Girişi iptal et
                    return response()->json([
                        "type" => "error", 
                        "message" => "Farklı bir cihazda aktif oturumunuz bulunuyor. Önce oradan çıkış yapmalısınız."
                    ]);
                } else {
                    // Cihaz aynı ise eski kayıtları temizle (Yenisi açılacak)
                    \App\Models\ActiveSession::where('user_id', $user->id)->delete();
                    // LastLogin'de açık kalan (ended_at null olan) aynı cihaz oturumlarını da kapatabilirsin
                    \App\Models\LastLogin::where('user_id', $user->id)
                        ->whereNull('ended_at')
                        ->update(['ended_at' => now()]);
                }
            }

            // 5. Yeni Oturum Kayıtlarını Oluştur
            \App\Models\ActiveSession::create([
                'user_id'    => $user->id,
                'ip_address' => $currentIp,
                'user_agent' => $request->userAgent(),
                'device'     => $deviceType,         
                'platform'   => $currentPlatform,        
                'browser'    => $currentBrowser,        
            ]);

            \App\Models\LastLogin::create([
                'user_id'    => $user->id,
                'ip_address' => $currentIp,
                'user_agent' => $request->userAgent(),
                'device'     => $deviceType,         
                'platform'   => $currentPlatform,        
                'browser'    => $currentBrowser,
                'login_at'   => now(),
            ]);

            $welcomeMessage = "Hoş geldin, " . $user->firstname . "!";

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

        return response()->json(["type" => "error", "message" => "E-posta veya şifre hatalı!"]);
    }
}
