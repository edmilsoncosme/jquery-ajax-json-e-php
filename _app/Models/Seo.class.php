<?php

/**
 * Seo.class [ MODEL ]
 * Classe de apoio para o modelo LINK. Pode ser utilizada para gerar Search Engine Optimization -  SEO (em português Otimizaão para buscas) para as páginas do sistema. 
 * @copyright (c) 2017, Edmilson Cosme da Silva UPINSIDE TECNOLOGIA
 */
class Seo {

    private $File;
    private $Link;
    private $Data;
    private $Tags;

    /** DADOS POVOADOS */
    private $seoTags;
            private$seoData;

    function __construct($File, $Link) {
        $this->File = strip_tags(trim($File));
        $this->Link = strip_tags(trim($Link));
    }

    public function getTags() {
        $this->checkData();
        return $this->seoTags;
    }

    public function getData() {
        $this->checkData();
        return $this->seoData;
    }

    //PRIVATES
    private function checkData() {
        if (!$this->seoData): // significa que não iniciou o sistema
            $this->getSeo();
        endif;
    }

    private function getSeo() {
        $ReadSeo = new Read;

        //var_dump($this->File);
        // identifica qual arquivio está sendo acessado para obeter os dados
        switch ($this->File):
            case 'artigo':
                $Admin = (isset($_SESSION['user_login']['user_level']) && $_SESSION['user_login']['user_level'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'post_status = 1 AND');

                $ReadSeo->ExeRead("ws_posts", "WHERE {$Check} post_name = :link", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    extract($ReadSeo->getResult()[0]);
                    $this->seoData = $ReadSeo->getResult()[0];
                    $this->Data = [$post_title . ' - ' . SITENAME, $post_content, HOME . "/artigo/{$post_name}", HOME . "uploads/{$post_cover}"];

                    //post:: conta views do post
                    $ArrUpdade = ['post_views' => $post_views + 1];
                    $Update = new Update();
                    $Update->ExeUpdate('ws_posts', $ArrUpdade, "WHERE post_id = :postid", "postid={$post_id}");
                endif;
                break;

            //SEO:: CATEGORIA   
            case 'categoria':
                $ReadSeo->ExeRead("ws_categories", "WHERE category_name = :link", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    extract($ReadSeo->getResult()[0]);
                    $this->seoData = $ReadSeo->getResult()[0];
                    $this->Data = [$category_title . ' - ' . SITENAME, $category_content, HOME . "/category/{$category_name}", INCLUDE_PATH . '/images/site.png'];

                    //category:: conta views da categorya
                    $ArrUpdade = ['category_views' => $category_views + 1];
                    $Update = new Update();
                    $Update->ExeUpdate('ws_categories', $ArrUpdade, "WHERE category_id = :cattid", "cattid={$category_id}");
                endif;
                break;

            case 'pesquisa':
                $ReadSeo->ExeRead("ws_posts", "WHERE post_status = 1 AND (post_title LIKE '%' :link '%' OR post_content LIKE '%' :link '%' ) ", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    $this->seoData['count'] = $ReadSeo->getRowCount();
                    $this->Data = ["Pesquisa por: {$this->Link}" . ' - ' . SITENAME, "Sua pesquisa por {$this->Link} retornou {$this->seoData['count']} resultados", HOME . "/pesquisa/{$this->Link}", INCLUDE_PATH . '/images/site.png'];
                endif;
                break;

            // SEO:: LISTA EMPRESAS
            case 'empresas':
                $Name = ucwords(str_replace("-", " ", $this->Link));
                $this->seoData = ["empresa_link" => $this->Link, "empresa_cat" => $Name];
                $this->Data = ["Empresas {$this->Link}" . SITENAME, "Confira o guia completo de usa cidade, e encontre empresas {$this->Link}.", HOME . '/empresas/' . $this->Link, INCLUDE_PATH . '/images/site.png'];
                break;

            // SEO:: EMPRESA SINGLE
            case 'empresa':
                // quando a empresa estiver inativa consegue acessar como administrador
                $Admin = (isset($_SESSION['user_login']['user_level']) && $_SESSION['user_login']['user_level'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'empresa_status = 1 AND');

                $ReadSeo->ExeRead("app_empresas", "WHERE {$Check} empresa_name = :link", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    extract($ReadSeo->getResult()[0]);
                    $this->seoData = $ReadSeo->getResult()[0];
                    $this->Data = [$empresa_title . ' - ' . SITENAME, $empresa_sobre, HOME . "/empresa/{$empresa_name}", HOME . "uploads/{$empresa_capa}"];

                    //empresa:: conta views da empresa
                    $ArrUpdade = ['empresa_views' => $empresa_views + 1];
                    $Update = new Update();
                    $Update->ExeUpdate('app_empresas', $ArrUpdade, "WHERE empresa_id = :empresaid", "empresaid={$empresa_id}");
                endif;
                break;


            //SEO:: CADASTRA EMPRESA
            case 'cadastra-empresa':
                $this->Data = ["Cadastre sua Empresa - " . SITENAME, "Página modelo para cadastro de empresas via Front-End do curso Work Series - PHP Orientado a Objetos!", HOME . '/cadastra-empresa/' . $this->Link, INCLUDE_PATH . '/images/site.png'];
                break;

            //SEO:: INDEX   
            case 'index':
                $this->Data = [SITENAME . ' - Seu guia de empresas, eventos e baladas', SITEDESC, HOME, INCLUDE_PATH . '/images/site.png'];
                break;

            //SEO:: 404
            default :
                $this->Data = ['Ops..., Nada encontrado', SITEDESC, HOME . '/404', INCLUDE_PATH . '/images/site.png'];
        endswitch;


        if ($this->Data):
            $this->setTags();
        endif;
    }

    private function setTags() {
        $this->Tags['Title'] = $this->Data[0];
        $this->Tags['Content'] = Check::Words(html_entity_decode($this->Data[1]), 25);
        $this->Tags['Link'] = $this->Data[2];
        $this->Tags['Image'] = $this->Data[3];

        $this->Tags = array_map('strip_tags', $this->Tags);
        $this->Tags = array_map('trim', $this->Tags);

        $this->Data = null;

        //NORMAL PAGE
        $this->seoTags = '<title>' . $this->Tags['Title'] . '</title> ' . "\n";
        $this->seoTags .= '<meta name="description" content="' . $this->Tags['Content'] . '"/>' . "\n";
        $this->seoTags .= '<meta name="robots" content="index, follow" />' . "\n";
        $this->seoTags .= '<link rel="canonical" href="' . $this->Tags['Link'] . '">' . "\n";
        $this->seoTags .= "\n";

        //FACEBOOK
        $this->seoTags .= '<meta property="og:site_name" content="' . SITENAME . '" />' . "\n";
        $this->seoTags .= '<meta property="og:locale" content="pt_BR" />' . "\n";
        $this->seoTags .= '<meta property="og:title" content="' . $this->Tags['Title'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:description" content="' . $this->Tags['Content'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:image" content="' . $this->Tags['Image'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:url" content="' . $this->Tags['Link'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:type" content="article" />' . "\n";
        $this->seoTags .= "\n";

        //ITEM GROUP (TWITTER)
        $this->seoTags .= '<meta itemprop="name" content="' . $this->Tags['Title'] . '">' . "\n";
        $this->seoTags .= '<meta itemprop="description" content="' . $this->Tags['Content'] . '">' . "\n";
        $this->seoTags .= '<meta itemprop="url" content="' . $this->Tags['Link'] . '">' . "\n";

        $this->Tags = null;
    }

//        switch ($this->File):
//            case 'artigo':
//                $Admin = (isset($_SESSION['userlogin']['user_level']) && $_SESSION['userlogin']['user_level'] == 3 ? true : false);
//                $Check = ($Admin ? '' : 'post_status = 1 AND');
//
//                $ReadSeo->ExeRead("ws_posts", "WHERE {$Check} post_name = :link", "link={$this->Link}");
//                if (!$ReadSeo->getResult()):
//                    $this->seoData = null;
//                    $this->seoTags = null;
//                else:
//                    extract($ReadSeo->getResult()[0]);
//                    $this->seoData = $ReadSeo->getResult()[0];
//                    $this->Data = [$post_title . ' - ' . SITENAME, $post_content, HOME . "/artigo/{$post_name}", HOME . "/uploads/{$post_cover}"];
//                    
//                    //post:: conta views do post
//                    $ArrUpdate = ['post_views' => $post_views + 1];
//                    $Update = new Update();
//                    $Update->ExeUpdate("ws_posts", $ArrUpdate, "WHERE post_id = :postid", "postid={$post_id}");
//                    
//                endif;
//                break;
//            case '404':
//                $this->Data = ['404 Oppss, Nada encontrado!', SITEDESC, HOME . '/404', INCLUDE_PATH . 'images/site.png'];
//                break;
//            default:
//               $this->Data = [SITENAME . ' - Seu guia de empresas, eventos e baladas', SITEDESC, HOME, INCLUDE_PATH . 'images/site.png'];
//        endswitch;
//        if ($this->Data):
//            $this->setTags();
//        endif;
    //   }

    /*
      public function setTags() {
      $this->Tags['Title'] = $this->Data[0];
      $this->Tags['Content'] = Check::Words(html_entity_decode($this->Data[1]), 25);
      $this->Tags['Link'] = $this->Data[2];
      $this->Tags['Image'] = $this->Data[3];

      $this->Tags = array_map('strip_tags', $this->Tags);
      $this->Tags = array_map('trim', $this->Tags);

      $this->Data = null;

      //NORMAL PAGE
      $this->seoTags = '<title>' . $this->Tags['Title'] . '</title> ' . "\n";
      $this->seoTags .= '<meta name="description" content="' . $this->Tags['Content'] . '"/>' . "\n";
      $this->seoTags .= '<meta name="robots" content="index, follow" />' . "\n";
      $this->seoTags .= '<link rel="canonical" href="' . $this->Tags['Link'] . '">' . "\n";
      $this->seoTags .= "\n";

      //FACEBOOK
      $this->seoTags .= '<meta property="og:site_name" content="' . SITENAME . '" />' . "\n";
      $this->seoTags .= '<meta property="og:locale" content="pt_BR" />' . "\n";
      $this->seoTags .= '<meta property="og:title" content="' . $this->Tags['Title'] . '" />' . "\n";
      $this->seoTags .= '<meta property="og:description" content="' . $this->Tags['Content'] . '" />' . "\n";
      $this->seoTags .= '<meta property="og:image" content="' . $this->Tags['Image'] . '" />' . "\n";
      $this->seoTags .= '<meta property="og:url" content="' . $this->Tags['Link'] . '" />' . "\n";
      $this->seoTags .= '<meta property="og:type" content="article" />' . "\n";
      $this->seoTags .= "\n";

      //ITEM GROUP (TWITTER)
      $this->seoTags .= '<meta itemprop="name" content="' . $this->Tags['Title'] . '">' . "\n";
      $this->seoTags .= '<meta itemprop="description" content="' . $this->Tags['Content'] . '">' . "\n";
      $this->seoTags .= '<meta itemprop="url" content="' . $this->Tags['Link'] . '">' . "\n";

      $this->Tags = null;
      }
     * 
     */
}
