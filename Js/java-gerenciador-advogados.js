jQuery(document).ready(function($) {

    var listagemContainer = $('#aer-listagem-container');
    var cadastroContainer = $('#aer-cadastro-container');


    // CORREÇÃO: A URL deve seguir o padrão da API REST para evitar erro 404
    // Ex: /wp-json/<namespace>/<versao>/<rota>
const fullUrlListar = AerApiSettings.root + 'aer-api/v1/advogados';

    $('.botao-adicionar-advogado').on('click', function(e) {
        e.preventDefault(); 
        listagemContainer.hide();
        cadastroContainer.show();
    });


    $('#botao-voltar-listagem').on('click', function(e) {
        e.preventDefault();
        cadastroContainer.hide();
        listagemContainer.show();
    });
    
 const fullUrl = AerApiSettings.root +  'aer-api/advogados/saveAdvogados';
    $('#form-novo-advogado').on('submit', function(e) {
        e.preventDefault(); 

      
        // AJUSTE: Removido 'id' e 'user_atual' do payload de criação.
        const payload = {
            nome: $('#advogado_nome').val(),
            cpf_cnpj: $('#advogado_cpf_cnpj').val(),
            endereco: $('#advogado_endereco').val(),
            uf: $('#advogado_uf').val(),
            cidade: $('#advogado_cidade').val(),
            bairro: $('#advogado_bairro').val(),
            telefone: $('#advogado_telefone').val(),
            email: $('#advogado_email').val(),
            createdBy: user_atual,
            updatedBy: user_atual,


        };
const validationResult = validateAdvogado(payload);
if (validationResult.erro) {
    alert(validationResult.msg_erro);
    return;
}

        
        fetch(fullUrl, {
        method: 'POST',
        headers: {
            'X-WP-Nonce': AerApiSettings.nonce,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Erro na API: Status ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            advogados = JSON.parse(data.data)  
         
            // AJUSTE: Tratamento de sucesso mais claro para o usuário.
            alert('Advogado salvo com sucesso!');
            $('#form-novo-advogado')[0].reset(); // Limpa o formulário
            $('#botao-voltar-listagem').click(); // Volta para a listagem
        })
        .catch(error => {
            console.error(':x_vermelho: Erro na requisição AJAX:', error);
            console.error('Erro na requisição AJAX:', error);
            alert('Ocorreu um erro ao salvar. Verifique o console para mais detalhes.');
        })
        .finally(() => {
           
           console.log('Requisição finalizada.');
        });
});
});
function validateAdvogado(advogado) {
    // Instancia a classe que contém os métodos de validação
    const validator = new snaValidatorFrontJs();
    console.log('teste ' + advogado.nome);
    let data = {};
    data.msg_erro = '';
    data.erro = false;
    // ----------------------------------------------------------------------
    // 1. VALIDAÇÃO: NOME COMPLETO (Campo preenchido)
    // ----------------------------------------------------------------------
    if (advogado.nome === '') {
        data.msg_erro = 'O nome completo deve ser preenchido';
        data.campo = 'nome';
        data.erro = true;
        return data;
    }
    // ----------------------------------------------------------------------
    // 2. VALIDAÇÃO: NOME COMPLETO (Formato: Nome e Sobrenome)
    // ----------------------------------------------------------------------
    // Utiliza o método nomeCompleto(str) da classe
    if (!validator.nomeCompleto(advogado.nome)) {
        data.msg_erro = 'O nome deve conter nome e sobrenome (pelo menos duas palavras)';
        data.campo = 'nome';
        data.erro = true;
        return data;
    }
    // ----------------------------------------------------------------------
    // 3. VALIDAÇÃO: SITUAÇÃO (situ)
    // ----------------------------------------------------------------------
       // ----------------------------------------------------------------------
    // 3. CPF/CNPJ OBRIGATÓRIO
    // ----------------------------------------------------------------------
    if (advogado.cpf_cnpj === '') {
        data.msg_erro = 'Digite o CPF ou o CNPJ do advogado.';
        data.campo = 'advogado_cpf_cnpj';
        data.erro = true;
        return data;
    }

    // ----------------------------------------------------------------------
    // 4. VALIDAÇÃO CPF/CNPJ
    // ----------------------------------------------------------------------
    if (!validator.cpfOuCnpj(advogado.cpf_cnpj)) {
        data.msg_erro = 'Digite um CPF ou CNPJ válido.';
        data.campo = 'advogado_cpf_cnpj';
        data.erro = true;
        return data;
    }

    // ----------------------------------------------------------------------
    // 5. TELEFONE (opcional, mas válido)
    // ----------------------------------------------------------------------
if (!validator.fone(advogado.telefone)) {
    data.msg_erro = 'O telefone deve conter apenas números e ter 10 ou 11 dígitos.';
    data.campo = 'advogado_telefone';
    data.erro = true;
    return data;
}

    // ----------------------------------------------------------------------
    // 6. EMAIL (opcional)
    // ----------------------------------------------------------------------
 
        if (!validator.email(advogado.email)) {
            data.msg_erro = 'O e-mail informado é inválido.';
            data.campo = 'advogado_email';
            data.erro = true;
            return data;
        }


    // ----------------------------------------------------------------------
    // 7. UF (obrigatória + 2 letras)
    // ----------------------------------------------------------------------
    function validarUF(uf) {
    if (!uf) return false;
    uf = uf.trim().toUpperCase();
    const ufs = [
        'AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG',
        'PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'
    ];

    return ufs.includes(uf);
}

    if (!validarUF(advogado.uf)) {
        data.msg_erro = 'Digite uma UF válida com 2 letras (ex: SP, RJ, MG).';
        data.campo = 'advogado_uf';
        data.erro = true;
        return data;
    }

    if (!advogado.cidade || advogado.cidade.trim().length < 2) {
        data.msg_erro = 'Informe uma cidade válida.';
        data.campo = 'advogado_cidade';
        data.erro = true;
        return data;

    // Tudo OK
    return data;
}
   if (!advogado.bairro || advogado.bairro.trim().length < 2) {
        data.msg_erro = 'Informe um bairro válido.';
        data.campo = 'advogado_bairro';
        data.erro = true;
        return data;
    }

    if (!advogado.endereco || advogado.endereco.trim().length < 5) {
        data.msg_erro = 'Informe um endereço válido.';
        data.campo = 'advogado_endereco';
        data.erro = true;
        return data;
    }


return data;

}





