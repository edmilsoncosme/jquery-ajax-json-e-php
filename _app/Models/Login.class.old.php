<?php

/**
 * Login.class [ MODEL ]
 * Responsável por autenticar, validar, e checar usuário do sistema de login!
 * 
 * @copyright (c) 2017, Edmilson Cosme da Silva UPINSIDE TECNOLOGIA
 */
class Login {

    private $Level;
    private $Email;
    private $Senha;
    private $Error;
    private $Result;

    function __construct($Level) {
        $this->Level = (int) $Level;
    }

    public function ExeLogin(array $UserData) {
        $this->Email = (string) strip_tags(trim($UserData['user']));
        $this->Senha = (string) strip_tags(trim($UserData['pass']));
        $this->setLogin();
    }

    private function setLogin() {
        if (!$this->Email || !$this->Senha || !Check::Email($this->Email)):
            $this->Error = ['Informe seu E-mail e senha para efetuar o login!', WS_INFOR];
            $this->Result = false;
        elseif (!$this->getUser()):
            $this->Error = ['Os dados informados não são compatí veis!', WS_ALERT];
            $this->Result = false;
        elseif ($this->Result['user_level'] < $this->Level):
            $this->Error = ["Desculpe, {$this->Result['user_name']} você não tem permissão para acessar esta área!", WS_ERROR];
            $this->Result = false;
        else:
            $this->Execute();
        endif;
    }

    private function getUser() {
        $this->Senha = md5($this->Senha);

        $read = new Read();
        $read->ExeRead("ws_users", "WHERE user_email = :e AND user_password = :p", "e={$this->Email}&p={$this->Senha}");

        if ($read->getResult()):
            $this->Result = $read->getResult()[0];
            return true;
        else:
            return false;
        endif;
    }

    private function Execute() {
        if (!session_id()):
            session_start();

            $_SESSION['userLogin'] = $this->Result;
            $this->Error = ["Olá {$this->Result['user_name']}, seja bem vindo(a). Aguarde redirecionamento", WS_ACCEPT];
            $this->Result = true;

        endif;
    }

    function getError() {
        return $this->Error;
    }

    /**
     * <b>Checar o login: <b/> Execute esse método para verificar a sessão USERLOGIN e realizar o acesso 
     * para proteger telas restritas.
     * @retun BOLEAN $Login = Retorna true ou mata a sessão e retorna false!
     * 
     */
    public function CheckLogin() {
        if (empty($_SESSION['user_login'] || $_SESSION['user_login']['user_level'] < $this->Level)):
            unset($_SESSION['user_login']);
            return false;
        else:
            return true;
        endif;
    }

    function getResult() {
        return $this->Result;
    }

}
