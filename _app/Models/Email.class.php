<?php

require('_app/Library/PHPMailer/class.phpmailer.php');

/**
 * Email.class [MODEL]
 * Modelo responsável por configurar a PHPMailer, validar os dados e disparar e-mails do sistema. 
 * @copyright (c) 2017, Edmilson Cosme da Silva UPINSIDE TECNOLOGIA
 */
class Email {

    /** @var PHPMailer */
    private $Mail;

    /*     * EMAIL DATA */
    private $Data;

    /*     * CORPO DO EMAIL */
    private $Assunto;
    private $Mensagem;

    /*     * REMETENTE */
    private $RemetenteNome;
    private $RemetenteEmail;

    /*     * DESTINO */
    private $DestinoNome;
    private $DestinoEmail;

    /*     * CONTROLE */
    private $Erro;
    private $Result;

    function __construct() {
        $this->Mail = new PHPMailer;
        $this->Mail->Host = MAILHOST;
        $this->Mail->Port = MAILPORT;
        $this->Mail->Username = MAILUSER;
        $this->Mail->Password = MAILPASS;
        $this->Mail->CharSet = 'UTF-8';
    }

    public function Enviar(array $Data) {
        $this->Data = $Data;
        $this->Clear();          
        
        if (in_array("", $this->Data)):
            $this->Erro = ['Erro ao enviar mensagem: Para enviar esse e-mail. Preencha os campos requisitados', WS_ALERT];
            $this->Result = false;
        elseif (!Check::Email($this->Data['RemetenteEmail'])):
            $this->Erro = ['Erro ao enviar mensagem: O e-mail que você informou não tem um formato válido! Informe seu e-mail. ', WS_ALERT];
            $this->Result = false;
        else:
            $this->setMail();
            $this->Config();
            $this->sendMail();

        endif;
    }

    function getResult() {
        return $this->Result;
    }

    function getErro() {
        return $this->Erro;
    }

    //PRIVATES
    private function Clear() {
        array_map('strip_tags', $this->Data);
        array_map('trim', $this->Data);
    }

    private function setMail() {
        $this->Assunto = $this->Data['Assunto'];
        $this->Mensagem = $this->Data['Mensagem'];
        $this->RemetenteNome = $this->Data['RemetenteNome'];
        $this->RemetenteEmail = $this->Data['RemetenteEmail'];
        $this->DestinoNome = $this->Data['DestinoNome'];
        $this->DestinoEmail = $this->Data['DestinoEmail'];

        $this->Data = null;
        $this->setMsg();
    }

    private function setMsg() {
        $this->Mensagem = "{$this->Mensagem}<hr><small>Recebida em:  " . date("d/m/Y H:s:i") . "</small>";
    }

    private function Config() {
        // SMTP AUTH
        $this->Mail->IsSMTP();
        $this->Mail->SMTPAuth = true;
        $this->Mail->IsHTML();

        // REMETENTE E RETONO
        $this->Mail->From = MAILUSER;
        $this->Mail->FromNamem = $this->RemetenteNome;
        $this->Mail->AddReplyTo($this->RemetenteEmail, $this->RemetenteNome);

        // ASSUNTO, MENSAGEM E DESTINO
        $this->Mail->Subject = $this->Assunto;
        $this->Mail->Body = $this->Mensagem;
        $this->Mail->AddAddress($this->DestinoEmail, $this->DestinoNome);
    }

    
    private function sendMail() {
        if ($this->Mail->Send()):
            $this->Erro = ['Obrigado por entrar em contato: Retornaremos sua mensagem e estaremos respondento em breve   ', WS_ACCEPT];
            $this->Result = true;
        else:
            $this->Erro = ["Erro ao enviar: Entre em contato com o admin. ( {$this->Mail->ErrorInfo} )   ", WS_ERROR];
            $this->Result = false;
        endif;
    }
}
