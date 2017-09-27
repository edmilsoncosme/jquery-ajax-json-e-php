<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="mit" content="2017-08-15T18:43:33-03:00+34377">
        <title>Curso Work Series - PHP Orientado a Objetos!</title>

        <style>
            *{margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif}
            .register{display: block; width: 100%; max-width: 600px; border: 15px solid #fff; margin: 0 auto; padding: 20px; background: #eee;}
            .register header{margin-bottom: 20px; text-align: center; padding-bottom: 20px; border-bottom: 2px solid #ccc;}
            .register header p{margin: 5px 0 10px 0;}
            .openform{float: right; background: #00cc33; display: inline-block; font-size: 0.9em; margin-left: 10px;  border: 2px solid #fff; outline: 2px solid #00cc33; padding: 5px 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}
            .openform:before{content: '+';}
            .openform.closeform{background-color: #b25c5c; outline-color: #b25c5c; padding: 5px 12.5px;}
            .openform.closeform:before{content: '-';}

            .register form{display: none; margin-bottom: 30px;}
            .register input{width: 100%; padding: 10px; margin-bottom: 10px;}
            
            .register button{background: #09f; border: 2px solid #fff; outline: 2px solid #09f; padding: 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}
/*            .register button:before{content: 'Cadastrar Usuário--';}*/
            .register .buttonUpdate{background: #09f; border: 2px solid #fff; outline: 2px solid #09f; padding: 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}
            /*.register .buttonUpdate:before{content: 'Alterar Usuário';}*/
            
            
            .register .close{background: #cc0033; font-size: 0.9em; margin-left: 10px;  border: 2px solid #fff; outline: 2px solid #cc0033; padding: 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}
            .user_box{display: block; padding: 10px; background: #fbfbfb; margin-top: 20px; padding-top: 20px; border-top: 1px dotted #000;}
            .action{cursor: pointer; display: inline-block; margin-top: 10px; padding: 5px 10px; font-size: 0.7em; margin-right: 10px; text-transform: uppercase; background: #555; color: #fff;}
            .del{background: #a72626;}
            .edit{background: #006699;}
            .form_load{display: none; vertical-align: middle; margin-left: 15px; margin-top: -2px;}
            .trigger{display: none; text-transform: uppercase; padding: 15px; background: #ccc; color: #000; margin-bottom: 20px; font-size: 0.8em; font-weight: bolder}
            .trigger-error{background: #e4b4b4;}
            .trigger-success{background: #b4e4b9;}

            .loamore{display: inline-block; margin-top: 25px; text-transform: uppercase; font-size: 0.7em; background: #555; color: #fff; padding: 10px; cursor: pointer;}

        </style>
    </head>

    <body>
        <section class="register">
            
            <form style="display: block " method="post" action="" class="j_upload">
                <div class="j_progress" style="display: none ; background: #ccc; color: #fff; font-size: 0.8em; margin-bottom: 15px;">
                    <div class="bar" style="display: block; padding: 5px; background: #09f; text-align: center; width: 0%; max-width: 100% ">0%</div>
                </div>
                
                
<!--                <input type="file" name="imagem">
                <button>UPLOAD</button> -->
            </form>  
            
            <hr style="margin-bottom: 50px;">
            
            <header>
                <a class="j_open openform" rel="usercreate"></a>
                <h1>Usuários</h1>
                <p>Mantendo tela de Usuários!</p>                
            </header>

            <form name="user_register" class="j_formsubmit usercreate" method="post" action="">
                <div class="trigger-box"></div>                              

                <input class="noclear"  type="text" name="action"  value="create"/>
                <input type="text" name="user_name" placeholder="Nome:"/>
                <input type="text" name="user_lastname" placeholder="Sobrenome:"/>
                <input type="email" name="user_email" placeholder="Email:"/>
                <input type="password" name="user_password" placeholder="Senha:"/>
                <input type="number" name="user_level" min="1" max="3" placeholder="Nível de Acesso:"/>
                <button class="j_btncadastro">Cadastrar Usuário</button>    
                              
                <img class="form_load" src="img/load.gif" alt="[CARREGANDO...]" title="CARREGANDO..."/>
            </form>

            <div class="j_list"> 

                <?php
                require './_app/Config.inc.php';
                $Read = new Read;
                $Read->ExeRead('ws_users', 'ORDER BY user_id DESC');
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Users):
                        extract($Users);
                        ?>
                        <article class="user_box" id="<?= $user_id; ?>">
                            <h1><?= $user_name; ?>  <?= $user_lastname; ?></h1>
                            <p><?= $user_email; ?> (Nível<?= $user_level; ?>)</p>
                            <a class="action edit j_edit" rel="<?= $user_id; ?>">Editar</a>
                            <a class="action del j_delete " rel="<?= $user_id; ?>">Deletar</a>
                            <!--<img class="form_load" src="img/load.gif" alt="[CARREGANDO...]" title="CARREGANDO..."/>-->
                        </article>
                        <?php
                    endforeach;
                endif;
                ?>
                <div class="j_insert"></div> 
            
            <a rel="j_list" class="j_load loamore ">Recaregar Usuário</a>
            <img class="form_load" src="img/load.gif" alt="[CARREGANDO...]" title="CARREGANDO..."/>
            </div>
        </section>
        <script src="js/jquery.js"></script>
        <script src="js/script.js"></script>
        <script src="js/jquery.form.js"></script>
    </body>
</html>
