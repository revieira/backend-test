<?php

header("Content-type: application/json");

require_once 'Controllers/AuthController.php';
require_once 'Controllers/InvestmentsController.php';
require_once 'Controllers/UsersController.php';

require_once 'Services/AuthService.php';

class RouterService extends AuthService{

    private $request;
    private $service;
	private $method;
	private $params;

    public function __construct($req) {
        $this->request = $req;
        $this->load();
    }

    public function load()
    {
        // api/classe/
        $this->url = explode("/", $this->request);

        if($this->url[0] === "api"){

            // apaga o primeiro registro do array e começa em ZERO novamente
            array_shift($this->url);

            $this->service = ucfirst($this->url[0])."Controller";
            array_shift($this->url);

            $this->method = strtolower($_SERVER['REQUEST_METHOD']);

            if (isset($this->url)) {
                $this->params = $this->url;
            }

            switch ($this->service) {
                case "AuthController":
                    if($this->method === 'post'){
                        if (isset($this->params) && !empty($this->params)){
                            $arr = get_class_methods($this->service);
                            $find = false;
                            foreach($arr as $func){
                                if(strtolower($func) == $this->params[0]){
                                   $find = true;                                 }
                            }
                            if(isset($find) && ($find === true)){
                                $rq = new AuthController();
                                echo $rq->post();
                            }
                            else{
                                throw new Exception(self::errorMessage(404, "Não encontrado!"));
                            }
                        }
                        else{
                            throw new Exception(self::errorMessage(400,"Operação inválida"));
                        }
                    }
                    else{
                        throw new Exception(self::errorMessage(400,"Operação inválida"));
                    }
                    break;
                
                case "UsersController":
                    if(self::checkAuth()){
                        if($this->method === 'get'){
                            if (isset($this->params[0])){
                                if(count($this->params) == 1){
                                    $rq = new UsersController();
                                    echo $rq->get($this->params);
                                }
                                else{
                                    throw new Exception(self::errorMessage(400,"Operação inválida"));
                                }
                            }
                            else{
                                throw new Exception(self::errorMessage(400,"Operação inválida"));
                            }
                        }
                        else if($this->method === 'post'){
                            if (isset($this->params[0]) && !empty($this->params[0]) && (count($this->params) == 1)){
                                if($this->params[0] == strtolower("create_owner")){
                                    $rq = new UsersController();
                                    echo $rq->post();
                                }
                                else{
                                    throw new Exception(self::errorMessage(404, "Não encontrado!"));
                                }
                            }
                            else{
                                throw new Exception(self::errorMessage(400,"Operação inválida"));
                            }
                        }
                        else{
                            throw new Exception(self::errorMessage(400,"Operação inválida"));
                        }
                    }
                    else{
                        throw new Exception(self::errorMessage(401,"Permissão de autenticação inválida"));
                    }
                    break;
                
                case "InvestmentsController":
                    if(self::checkAuth()){
                        if($this->method === 'get'){
                            if (isset($this->params[0])){
                                if(count($this->params) == 1){
                                    $rq = new InvestmentsController();
                                    echo $rq->get($this->params);
                                }
                                else if((count($this->params) > 1 && count($this->params) < 6) && ($this->params[0] == strtolower("owner"))){
                                    $rq = new InvestmentsController();
                                    echo $rq->get($this->params);
                                }
                            }
                            else{
                                throw new Exception(self::errorMessage(400,"Operação inválida"));
                            }
                        }
                        else if($this->method === 'post'){
                            if (isset($this->params[0]) && !empty($this->params[0]) && (count($this->params) == 1)){
                                if($this->params[0] == strtolower("new")){
                                    $rq = new InvestmentsController();
                                    echo $rq->post();
                                }
                                else{
                                    throw new Exception(self::errorMessage(400,"Operação inválida"));
                                }
                            }
                            else{
                                throw new Exception(self::errorMessage(400,"Operação inválida"));
                            }
                        }
                        else if($this->method === 'put'){
                            if ((isset($this->params[0])) && (count($this->params) == 1) && (empty($this->params[0]))){
                                $rq = new InvestmentsController();
                                echo $rq->put();
                            }
                            else{
                                throw new Exception(self::errorMessage(400,"Operação inválida"));
                            }
                        }
                        else if($this->method === 'delete'){
                            
                            if ((isset($this->params[0])) && (count($this->params) == 1) && ($this->params[0] == strtolower("withdrawal"))){
                                $rq = new InvestmentsController();
                                echo $rq->delete();
                            }
                            else{
                                throw new Exception(self::errorMessage(400,"Operação inválida"));
                            }
                        }
                        else{
                            throw new Exception(self::errorMessage(400,"Operação inválida"));
                        }
                    }
                    else{
                        throw new Exception(self::errorMessage(401,"Permissão de autenticação inválida"));
                    }
                    break;

                default:
                    return self::errorMessage(400,"Operação inválida");
                    break;
            }
        }
    }

    private function errorMessage($code,$msg){
        echo "Error ".$code." - ".$msg;
    }
}

?>