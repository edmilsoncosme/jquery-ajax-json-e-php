$(function () {

    $('.j_open').click(function () {
        $(this).toggleClass('closeform');
        $('.j_userid').remove();
        $('.' + $(this).attr('rel')).slideToggle();
        $('.j_formsubmit').find('input[class !="noclear"]').val('');
        $('.j_formsubmit').find('input[name="action"]').val('create');
    });

    $('.j_upload').submit(function () {
        var form = $(this);
        form.ajaxSubmit({
            url: 'ajax/ajax.php',
            data: {action: 'upload'},
            beforeSubmit: function () {

            },
            uploadProgress: function (evento, posicao, total, completo) {
                form.find('.j_progress').fadeIn();
                $('.j_progress .bar').text(completo + "%").width(completo + "%");
            },
            success: function () {

            }
        });
        // pra não enviar via http e sim via ajax
        return false;
    });


    $('.j_load').click(function () {

        var destino = $('.' + $(this).attr('rel'));
        var loaded = destino.find('article').length;
        $.ajax({
            url: 'ajax/ajax.php',
            data: {action: 'loadmore', offset: loaded},
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $('.j_list').find('.form_load').fadeIn();
                $('.j_list').find('.trigger').fadeOut(400, function () {
                    $(this).remove();
                });
            },
            success: function (data) {
                $(data.result).appendTo(destino.find('.j_insert'));
                $('.register').find('.trigger, article').fadeIn(400, function () {
                    $('.register').find('.form_load').fadeOut();
                });
            }
        });
    });
    //SELETOR, EVENTO/EFEITO, CALLBACK , AÇÃO
    $('.j_formsubmit').submit(function () {
        var form = $(this);
        var data = $(this).serialize();
        $.ajax({
            url: 'ajax/ajax.php',
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                form.find('.form_load').fadeIn(500);
                form.find('.trigger-error').fadeOut(500, function () {
                    $(this).remove();
                });
            },
            success: function (resposta) {
                if (resposta.error) {
                    form.find('.trigger-box').html('<div class="trigger trigger-error">' + resposta.error + '</div>');
                    form.find('.trigger-error').fadeIn();
                } else {
                    form.find('.trigger-box').html('<div class="trigger trigger-success">' + resposta.success + '</div>');
                    form.find('.trigger-success').fadeIn();
                    form.find('input[class !="noclear"]').val('');

                    //removendo o item da lista que foi alterado
                    if (resposta.user_id) {
                        $('#' + resposta.user_id).fadeOut(400, function () {
                            $(this).remove();
                        });
                        $('.j_formsubmit').find('.j_btncadastro').text("Cadastrar Usuário");
                        $('.j_formsubmit').find('input[name="action"]').val('create');
                        $('.j_formsubmit').find('.j_userid').remove();
                    }
                    // adicionando o item que foi incluído ou alterado
                    $(resposta.result).prependTo($('.register').find('.j_list'));
                    $('.j_register').fadeIn(400);
                }
                form.find('.form_load').fadeOut(500);

            }
        });
        return false;
    });
    // monitoramento de dom - quando clicar no j_list vai buscar os j_edit e gerar ação
    // sepre que carregar alguma coisa com jquery tem que seguir essa lógica de monitroamento
    // do dom
    $('.j_list').on('click', '.j_edit', function () {
        var user_id = $(this).attr('rel');
        $.ajax({

            url: 'ajax/ajax.php',
            data: {action: 'readuser', user_id: user_id},
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $('.j_userid').remove();
                $('.j_formsubmit').find('.j_btncadastro').text("Alterar Usuário");
                $('.j_formsubmit').find('.trigger-box').fadeOut();                
            },
            success: function (data) {
                if (!$('.j_formsubmit').is(':visible') && !data.error) {
                    $('.j_open').click();
                }

                if (data.error) {
                    alert('Erro ao selecionar ou usuário não exite');
                } else {
                    // percorendo o array retornado
                    $.each(data.user, function (key, value) {
                        $('.j_formsubmit').find('input[name="' + key + '"]').val(value);
                    });
                    $('.j_formsubmit').find('input[name="action"]').val('update');
                    $('<input type="hidden" class="j_userid" name="user_id" value="' + data.user.user_id + '"/>').prependTo('.j_formsubmit');
                }
            }
        });
    });

    $('.j_list').on('click', '.j_delete', function () {
        var form = $(this);
        var user_id = $(this).attr('rel');
        $.ajax({
            url: 'ajax/ajax.php',
            data: {action: 'deleteuser', user_id: user_id},
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.error) {
                    alert('Erro ao deletar. Favor recarregue a página!');
                } else if (data.admin) {
                    alert('Usuário com perfil de adminstrador! Não pode ser deletado.!');
                } else {
                    //Carregando a mensagem na tela                     
                    $('.j_list').find('.trigger-box-delete').html('<div class="trigger trigger-box-delete">' + data.success + '</div>');                    
                    $('.j_list').find('.trigger-box-delete').fadeIn();
                    $('#' + user_id).fadeOut(400, function () {
                        $(this).remove();
                    });                                        
                }
            }
        });
    });

});



