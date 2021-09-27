# SSO Client Package via Hwacom SSO

<a href="https://github.com/yajra/laravel-oci8/actions"><img src="https://github.com/yajra/laravel-oci8/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/yajra/laravel-oci8"><img src="https://poser.pugx.org/yajra/laravel-oci8/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/yajra/laravel-oci8"><img src="https://poser.pugx.org/yajra/laravel-oci8/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/yajra/laravel-oci8"><img src="https://poser.pugx.org/yajra/laravel-oci8/license.svg" alt="License"></a>

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

Powered By Ryan
