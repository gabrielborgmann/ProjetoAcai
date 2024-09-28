$(document).ready(function(){ // $ = referência à biblioteca jQuery, o doc.ready para executar quando o html está carregado
    $('#cadastroForm').submit(function(event){//pegando as informaçoes do html a função passada como argumento será executada.
        event.preventDefault(); 
		//Esta é uma chamada AJAX que envia os dados do formulário para o servidor sem recarregar a página. Estamos passando um objeto com várias configurações para o método 
        var nome = $('input[name=nome]').val();
        var email = $('input[name=email]').val();
        var senha = $('input[name=senha]').val();
        var cargo = $('select[name=cargo]').val();
        
        // Envio do formulário via AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: {
                nome: nome,
                email: email,
                senha: senha,
                cargo: cargo
            },
            success: function(response){
                alert(response); // Exibe a resposta do servidor
            }
        });
    });
});
