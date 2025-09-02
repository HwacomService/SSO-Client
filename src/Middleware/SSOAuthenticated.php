<?php

namespace Hwacom\ClientSso\Middleware;

use App\Models\User;
use Hwacom\ClientSso\Services\SSOService;
use Closure;
use Illuminate\Support\Facades\Auth;

class SSOAuthenticated
{
    private SSOService $SSOService;

    public function __construct()
    {
        $this->SSOService = new SSOService();
    }
    /**
     * MiddleWare攔截檢查Cookie有無Token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('sso.sso_enable') === true) {
            $token      = $_COOKIE['token'] ?? '';
            $payload    = null;
            if ($token != "") {
                $tokens = explode('.', $token);
                [$base64header, $base64payload, $sign] = $tokens;
                $payload = json_decode($this->SSOService->base64UrlDecode($base64payload));
                if ($payload) {
                    //增加Email判斷取得User登入權限
                    $user = User::where('email', $payload->email)->first();
                    if (!Auth::check()) {
                        Auth::loginUsingId($user->id);
                        //因為資安需求不能有Remember Token故意除後段true屬性
                    }
                    $expire = date('Y-m-d H:i:s', $payload->exp);
                    if ($expire > now()) {
                        return $next($request);
                    }
                }
            }
            setcookie("callback", config("sso.callback"), 0, "/", '.hwacom.com');
            $secret = config('sso.client_secret');
            return redirect(config("sso.sso_host") . "/google/auth/login/" . "$secret");
        } else {
            return $next($request);
        }
    }

}
