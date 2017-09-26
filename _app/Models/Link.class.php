<?php

/**
 * Link.class [ MODEL ]
 * Classe responsável por organizar o SEO do sistema e realizar a navegação
 * @copyright (c) 2017, Edmilson Cosme da Silva UPINSIDE TECNOLOGIA
 */
class Link {

    private $File; // identifica o arquivo (index, categoria ou artigo)
    private $Link; // identifica o name para objter os dados no banco  
    
      /** Data */
    private $Local; // url completa que vai estar acessando 
    private $Patch; // caminho e o arquivo de inclusão para fazer a navegação.
    private $Data;
    private $Tags;

    /** @var Seo */
    private $Seo;
    
    
    function __construct() {
        $this->Local = strip_tags(trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
        $this->Local = ($this->Local ? $this->Local : 'index');
        $this->Local = explode('/', $this->Local); // tranforma em um array
        $this->File = (isset($this->Local[0]) ? $this->Local[0] : 'index');
        $this->Link = (isset($this->Local[1]) ? $this->Local[1] : null);
        $this->Seo = new Seo($this->File, $this->Link);
        ;
    }

    public function getTags() {
        $this->Tags = $this->Seo->getTags();
        echo $this->Tags;
    }

    public function getData() {
        $this->Data = $this->Seo->getData();
        return $this->Data;
    }

    function getLocal() {
        return $this->Local;
    }

    function getPatch() {
        $this->setPatch();
        return $this->Patch;
    }

    // PRIVATES
    private function setPatch() {
        // procurando por um arquivo se não existir procura dentro da pasta
        if (file_exists(REQUIRE_PATH . DIRECTORY_SEPARATOR . $this->File . '.php')):
            $this->Patch = REQUIRE_PATH . DIRECTORY_SEPARATOR . $this->File . '.php';
        elseif (file_exists(REQUIRE_PATH . DIRECTORY_SEPARATOR . $this->File . DIRECTORY_SEPARATOR . $this->Link . '.php')) :
            echo '2';
            $this->Patch = REQUIRE_PATH . DIRECTORY_SEPARATOR . $this->File . DIRECTORY_SEPARATOR . $this->Link . '.php';
        else :
            echo '3';
            $this->Patch = REQUIRE_PATH . DIRECTORY_SEPARATOR . '404.php';
        endif;

//        $url = ( isset($_GET['url']) ? strip_tags(trim($_GET['url'])) : 'index');
//        $url = explode('/', $url);
//        $url[0] = ($url[0] == null ? 'index' : $url[0]);
//        $url[1] = ( empty($url[1]) ? null : $url[1]); //EVITA NOCICE
//        
//        //var_dump($url);
//
//        if (file_exists(REQUIRE_PATH . '/' . $url[0] . '.php')) :
//            require_once(REQUIRE_PATH . '/' . $url[0] . '.php');
//        elseif (file_exists(REQUIRE_PATH . '/' . $url[0] . '/' . $url[1] . '.php')) :
//            require_once(REQUIRE_PATH . '/' . $url[0] . '/' . $url[1] . '.php');
//        else:
//            if (file_exists(REQUIRE_PATH . '/404.php')):
//                require_once(REQUIRE_PATH . '/404.php');
//            else:
//                echo "<p style=\"text-align:center; padding:50px 0;\">404 Erro - Arquivo não existe!</p>";
//            endif;
//        endif;
    }

}
