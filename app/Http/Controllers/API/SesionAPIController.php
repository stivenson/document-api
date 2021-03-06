<?php

namespace document\Http\Controllers\API;

use Illuminate\Http\Request;
use document\Http\Controllers\AppBaseController as InfyOmBaseController;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use JWTAuth;

/**
 * Class SesionController
 * @package document\Http\Controllers\API
 */

class SesionAPIController extends InfyOmBaseController
{


    public function store(Request $request)
    {
        $credentials = $request->only(['email','password']);
        $credentials['email'] = strtolower($credentials['email']);

        if(!$token = JWTAuth::attempt($credentials)){
            return Response::json(ResponseUtil::makeError('failure logging'), 200);
        }

        $user = JWTAuth::toUser($token);

        return $this->sendResponse([
                        'user'=>$user,
                        'token' => compact('token')
                        ],'Sesion saved successfully');
    }


    public function destroy()
    {
        try {
            $token = JWTAuth::getToken();
            if ($token) {
              JWTAuth::setToken($token)->invalidate();
            }
        } catch (Exception $e) {

        }

        return $this->sendResponse('-', 'Sesion deleted successfully');
    }
}
