$(function () {

    // monitoramento de dom - quando clicar no j_list vai buscar os j_edit e gerar ação
    // sepre que carregar alguma coisa com jquery tem que seguir essa lógica de monitroamento
    // do dom
    $('.j_alterar_div').on('click', function () {
        $('body').find('.trigger-box-delete').html('<div class="trigger trigger-box-delete">' + 'Oi' + '</div>'); 
        $('body').find('.trigger-box-delete').fadeIn();
    });
    return false;
});



