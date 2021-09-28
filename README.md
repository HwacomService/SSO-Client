# SSO Client Package via Hwacom SSO

<a href="https://github.com/mozielin/Client-SSO/actions"><img src="https://github.com/mozielin/Client-SSO/workflows/PHP Composer/badge.svg" alt="Build Status"></a>
[![Total Downloads](http://poser.pugx.org/hwacom/client-sso/downloads)](https://packagist.org/packages/hwacom/client-sso)
[![Latest Stable Version](http://poser.pugx.org/hwacom/client-sso/v)](https://packagist.org/packages/hwacom/client-sso)
## 前言

要用我華電SSO必先安裝客戶端

## 安裝說明

```bash
composer require hwacom/client-sso
```

## Service Provider設定 (Laravel 5.5^ 會自動掛載)

Composer安裝完後要需要修改 `config/app.php` 找到 providers 區域並添加:

```php
\Hwacom\SSO\SSOServiceProvider::class,
```

## Config設定檔發佈 

用下列指定會建立sso.php設定檔，需要在 `.env` 檔案中增加設定.

```bash
php artisan vendor:publish
```

 下列設定會自動增加在 `config/sso.php`

```php
'sso_enable'    => env('SSO_ENABLE',false),
'client_secret' => env("SSO_CLIENT_SECRET"),
'callback'      => env("SSO_CLIENT_CALLBACK"),
'sso_host'      => env("SSO_HOST")
```

## [LoginController] 增加兩個Function
Login

```
/**
 * 登入頁面置換，需自行寫入LoginController中
 *
 */
public function showLoginForm()
{
    setcookie("callback", config('auth.callback'), 0, "/", '.hwacom.com');
    return redirect(config("sso.sso_host") .  "/google/auth");
}
```

Logout

```
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
```
## [Middleware] 增加至`Http/Kernel.php`web Group中

```php
\App\Http\Middleware\SSOAuthenticated::class,
```
