<?php

require_once 'Models/Users.php';

class AuthService extends Users{

	private static $key = 'codeRockr';
	private $token;

	// gera o token para inserir no "Authorization
	protected function gerarToken(){
		$_POST = file_get_contents('php://input');
		$dados=json_decode($_POST);

		// Header token
		$header = ['typ' => 'JWT', 'alg' => 'HS256'];

		// Payload - Content
		$payload = ['owner' => strtoupper($dados->owner), 'email' => $dados->email];

		// JSON
		$header = json_encode($header);
		$payload = json_encode($payload);

		// Base 64 URL
		// JSON
		$header = self::base64UrlEncode($header);
		$payload = self::base64UrlEncode($payload);

		// Sign
		$sign = hash_hmac('sha256', $header.".".$payload, self::$key, true);
		$sign = self::base64UrlEncode($sign);

		// Token
		$token = $header.".".$payload.".".$sign;

		return json_encode(array("status"=>http_response_code(200),"message"=>$token));
	}

	public function getToken(){
		return self::gerarToken();
	}

	// verifica o token no "Authorization
	public static function checkAuth(){

		$http_header = apache_request_headers();

		if(isset($http_header['Authorization']) && $http_header['Authorization'] != null){
			$bearer = explode(" ", $http_header['Authorization']);
			$bearer[0] = 'Bearer';
			//$bearer[1] é o token
			$token = explode(".", $bearer[1]);

			$header = $token[0];
			$payload = $token[1];
			$sign = $token[2];

			// Conferir Sign
			$valid = hash_hmac('sha256', $header.".".$payload, self::$key, true);
			$valid = self::base64UrlEncode($valid);

			if($valid === $sign){
				return true;
			}
			else{
				return false;
			}
		}else{
			return false;
		}
	}

	/*O jwt.io agora recomenda o uso do 'base64url_encode' no lugar do 'base64_encode'*/
    private static function base64UrlEncode($data)
    {
        $b64 = base64_encode($data);

        if ($b64 === false) {
            return false;
        }

        $url = strtr($b64, '+/', '-_');

        return rtrim($url, '=');
    }
}

?>