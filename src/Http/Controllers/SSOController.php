<?php

namespace Hwacom\ClientSso\Http\Controllers;

use App\Http\Controllers\Controller;
use Hwacom\ClientSso\Services\SSOService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Session,Log;

class SSOController extends Controller
{
    private SSOService $SSOService;

    public function __construct()
    {
        $this->SSOService = new SSOService;
    }

    public function callback(Request $request){
        $token      = $_COOKIE['token'] ?? '';

        if($token != ""){
            $tokens     = explode('.', $token);
            list($base64header, $base64payload, $sign) = $tokens;
            $payload    = json_decode($this->SSOService->base64UrlDecode($base64payload));
            $email = $payload->email;
            $user = User::where('email',$email)->first();

            if ($user) {
                Auth::login($user);
                $path = Session::get('redirect');
                return redirect($path);
            }
        }else{
            Auth::logout();
            return redirect(config("sso.sso_host"));
        }
        abort(403);
    }

    /**
     * 登入頁面置換，需自行寫入LoginController中
     *
     */
    public function showLoginForm()
    {
        setcookie("callback", config('auth.callback'), 0, "/", '.hwacom.com');
        return redirect(config("sso.sso_host") .  "/google/auth");
    }

    /**
     * 登出用需自行寫入LoginController中
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        setcookie("token", "", time() - 3600, '/', '.hwacom.com');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect(config("sso.sso_host"));
    }

}
