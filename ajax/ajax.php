<?php

$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$setPost = array_map('strip_tags', $getPost);
$Post = array_map('trim', $setPost);

$Action = $Post['action'];
unset($Post['action']);
$jSon = array();
sleep(1);


if ($Action):
    require '../_app/Config.inc.php';
    $Create = new Create;
    $Read = new Read;
    $Update = new Update;
    $Delete = new Delete;
endif;


switch ($Action):
    case 'upload':
        $Upload = new Upload;
        $Upload->Image($_FILES['imagem']);
        break;


    case 'create':
        if (in_array('', $Post)):
            $jSon['error'] = "<b>OPSSS:</b>Para Cadastrar um Usuário, preenca todos os campos!";
        elseif (!Check::Email($Post['user_email']) || !filter_var($Post['user_email'], FILTER_VALIDATE_EMAIL)):
            $jSon['error'] = "<b>OPSSS:</b>Favor, Informe um e-mail válido!";
        elseif (strlen($Post['user_password']) < 5 || strlen($Post['user_password']) > 10) :
            $jSon['error'] = "<b>OPSSS:</b>Sua senha deve ter entre 5 e 10 caracteres!";
        else:
            $Read->FullRead("SELECT user_id FROM ws_users WHERE user_email = :email", "email={$Post['user_email']}");
            if ($Read->getResult()):
                $jSon['error'] = "<b>OPSSS:</b>O e-mail <b>{$Post['user_email']}</b> já está em uso!";
            else:
                $Create->ExeCreate('ws_users', $Post);
                $jSon['success'] = "Cadastro com sucesso!";
                $jSon['result'] = "<article style='display:none' class='user_box j_register' id='{$Create->getResult()}'><h1>{$Post['user_name']} {$Post['user_lastname']}</h1><p>{$Post['user_email']} (Nível {$Post['user_level']})</p><a class='action edit j_edit' rel='{$Create->getResult()}'>Editar</a><a class='action del' rel='{$Create->getResult()}'>Deletar</a></article>";
            endif;
        endif;
        break;

    case 'update':
        if (in_array('', $Post)):
            $jSon['error'] = "<b>OPSSS:</b>Para Alterar um Usuário, preenca todos os campos!";
        elseif (!Check::Email($Post['user_email']) || !filter_var($Post['user_email'], FILTER_VALIDATE_EMAIL)):
            $jSon['error'] = "<b>OPSSS:</b>Favor, Informe um e-mail válido!";
        elseif (strlen($Post['user_password']) < 5 || strlen($Post['user_password']) > 10) :
            $jSon['error'] = "<b>OPSSS:</b>Sua senha deve ter entre 5 e 10 caracteres!";
        else:
            $Read->FullRead("SELECT user_id FROM ws_users WHERE user_email = :email AND user_id != :id", "email={$Post['user_email']}&id={$Post['user_id']}");
            if ($Read->getResult()):
                $jSon['error'] = "<b>OPSSS:</b>O e-mail <b>{$Post['user_email']}</b> já está em uso!";
            else:
                $UserId = $Post['user_id'];
                unset($Post['user_id']);
                $Update->ExeUpdate("ws_users", $Post, "WHERE user_id = :id", "id={$UserId}");
                $jSon['success'] = "Usuário atualizado com sucesso!";
                
                $jSon['result'] = "<article style='display:none' class='user_box j_register' id='{$Create->getResult()}'><h1>{$Post['user_name']} {$Post['user_lastname']}</h1><p>{$Post['user_email']} (Nível {$Post['user_level']})</p><a class='action edit j_edit' rel='{$Create->getResult()}'>Editar</a><a class='action del' rel='{$Create->getResult()}'>Deletar</a></article>";
            endif;
        endif;
        break;

    case 'loadmore':
        $jSon['result'] = null;
        $Read->ExeRead('ws_users', "ORDER BY user_id DESC LIMIT :limit OFFSET :offset", "limit=2&offset={$Post['offset']}");
        if ($Read->getResult()):
            foreach ($Read->getResult() as $Users):
                extract($Users);
                $jSon['result'] .= "<article style='display:none' class='user_box' id='{$user_id}'><h1>{$user_name} {$user_lastname}</h1><p>{$user_email} (Nível {$user_level})</p><a class='action edit j_edit' rel='{$user_id}'>Editar</a><a class='action del j_delete' rel='{$user_id}'>Deletar</a></article>";
            endforeach;
        else:
            $jSon['result'] = "<div style='margin: 15px 0 0 0;' class='trigger trigger-error'>Não existem Mais Resultados!</div>";
        endif;
        break;

    case 'deleteuser':
        // antes deletar verificar se o usário é administrador
        $Read->ExeRead('ws_users', "WHERE user_id = :id and user_level = 3", "id={$Post['user_id']}");
        if ($Read->getResult()):
            $jSon['admin'] = true;
        else:
            $Delete->ExeDelete('ws_users', "WHERE user_id = :id", "id={$Post['user_id']}");
            if (!$Delete->getRowCount()):
                $jSon['error'] = true;
            endif;
        endif;

        break;

    case 'readuser':
        $Read->ExeRead('ws_users', "WHERE user_id = :id", "id={$Post['user_id']}");
        if ($Read->getResult()):
            $jSon['user'] = $Read->getResult()[0];
        else:
            $jSon['error'] = true;
        endif;
        break;

    default:
        $jSon['error'] = "Erro ao Selecionar Ação";

endswitch;

echo json_encode($jSon);



