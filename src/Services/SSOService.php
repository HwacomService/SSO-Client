<?php

namespace Hwacom\ClientSso\Services;

use App\Models\CRM\HasTrack;
use Response;
use View;
use Auth;
use Crypt;
use Gate;
use Log;
use Session;
use DB;

class SSOService
{
    /**
     * base64UrlEncode  https://jwt.io/  中 base64UrlEncode
     * @param string $input 需要解碼的字串
     * @return bool|string
     */
    public function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
