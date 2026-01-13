jQuery(document).ready(function($) {
    
    const cadastroContainer = $('#aer-cadastro-container');


    // CORREÇÃO: A URL deve seguir o padrão da API REST para evitar erro 404
    // Ex: /wp-json/<namespace>/<versao>/<rota>
const fullUrlListar = AerApiSettings.root + 'aer-api/v1/advogados';

    $('.botao-adicionar-advogado').on('click', function(e) {
        e.preventDefault();
        $('#form-novo-advogado')[0].reset(); // Limpa o formulário para um novo cadastro
        $('#advogado_id').val(''); // Garante que o ID está vazio
        $('#form-novo-advogado h1').text(' Adicionar novo funcionário   '); // Reseta o título do formulário
        cadastroContainer.addClass('modal-ativo'); // Abre o modal
    });

    
    $('#botao-voltar-listagem').on('click', function(e) {
        e.preventDefault();
        cadastroContainer.removeClass('modal-ativo'); // Fecha o modal
    });
    
 const fullUrl = AerApiSettings.root +  'aer-api/advogados/saveAdvogados';
    $('#form-novo-advogado').on('submit', function(e) {
        e.preventDefault(); 

        // AJUSTE: Adicionado 'id' para permitir a atualização.
        const payload = {
            id: $('#advogado_id').val(), // Pega o ID do campo oculto
            nome: $('#advogado_nome').val(),
            cpf_cnpj: $('#advogado_cpf_cnpj').val(),
            endereco: $('#advogado_endereco').val(),
            uf: $('#advogado_uf').val(),
            cidade: $('#advogado_cidade').val(),
            bairro: $('#advogado_bairro').val(),
            telefone: $('#advogado_telefone').val(),
            setor: $('#setor').val(),
            status: $('#advogado_status').val(), // Adiciona o status ao payload
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
            window.location.reload(); // Recarrega a página para ver as alterações na lista
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

    // --- LÓGICA PARA ABRIR E PREENCHER O FORMULÁRIO DE EDIÇÃO ---
    // Adiciona um listener de evento na tabela. O evento é delegado para as linhas '.advogado-row'
    $('.tabela-advogados tbody').on('click', '.advogado-row', function() {
        // Pega o objeto do advogado do atributo data-
        const advogadoData = $(this).data('advogado');

        if (advogadoData) {
            // Preenche os campos do formulário com os dados do advogado
            $('#advogado_id').val(advogadoData.id);
            $('#advogado_nome').val(advogadoData.nome);
            $('#advogado_cpf_cnpj').val(advogadoData.cpf_cnpj);
            $('#advogado_endereco').val(advogadoData.endereco);
            $('#advogado_uf').val(advogadoData.uf);
            $('#advogado_cidade').val(advogadoData.cidade);
            $('#advogado_bairro').val(advogadoData.bairro);
            $('#advogado_telefone').val(advogadoData.telefone);
            $('#setor').val(advogadoData.setor);
            $('#advogado_status').val(advogadoData.status || 'ativo'); // Preenche o status
            $('#advogado_email').val(advogadoData.email);

            // Altera o título do formulário
            $('#form-novo-advogado h1').text('Editar Dados do Advogado');

            // Exibe o formulário e esconde a listagem
            cadastroContainer.addClass('modal-ativo'); // Abre o modal com os dados preenchidos
        }
    });

    // --- LÓGICA PARA FILTRAR A LISTA PELOS CARDS DE STATUS ---
    $('.container-retangulos .retangulo').on('click', function() {
        const filtro = $(this).data('filter');
        const todasAsLinhas = $('.tabela-advogados tbody .advogado-row');

        // Se o filtro for 'todos', mostra todas as linhas
        if (filtro === 'todos') {
            todasAsLinhas.show();
            return;
        }

        // Caso contrário, itera sobre cada linha para decidir se deve mostrar ou esconder
        todasAsLinhas.each(function() {
            const linha = $(this);
            const statusDaLinha = linha.data('status');

            if (statusDaLinha === filtro) {
                linha.show(); // Mostra se o status corresponde ao filtro
            } else {
                linha.hide(); // Esconde se não corresponde
            }
        });
    });

    // --- LÓGICA PARA A BARRA DE PESQUISA POR NOME ---
    $('#filtro-nome-advogado').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        const todasAsLinhas = $('.tabela-advogados tbody .advogado-row');

        todasAsLinhas.each(function() {
            const linha = $(this);
            // Pega o nome do advogado do primeiro <td> da linha
            const nomeAdvogado = linha.find('td:first').text().toLowerCase();

            // Verifica se o nome do advogado inclui o termo pesquisado
            if (nomeAdvogado.includes(searchTerm)) {
                linha.show(); // Mostra a linha se corresponder
            } else {
                linha.hide(); // Esconde a linha se não corresponder
            }
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

(function ($) {
// animated hex background
  $(document).ready(function() {
    $('.animated-background').each(function( index ) {
      var $container = $(this);
      var cnv = $("<canvas></canvas>").attr("id", "can"+index);

      var colorToUse = $(this).attr('data-color');
      if (colorToUse === 'red') {
        colorRange = ['rgba(206, 23, 41, 0)', 'rgba(193, 23, 43, 0)'];
        strokeColor = 'rgba(206, 23, 41, 1)';
      } else {
        colorRange = ['rgba(252, 252, 252, alp)', 'rgba(248, 248, 248, alp)'];
        strokeColor = 'rgba(245,245,245, 0.5)';
      }

      $(this).prepend(cnv);

      var can = document.getElementById("can"+index);
      var w = can.width = $(this).width(),
      h = can.height = $(this).height(),
      sum = w + h,
      ctx = can.getContext('2d'),

      opts = {

        side: 16,
        picksParTick: 2, //originally 5
        baseTime: 1000,
        addedTime: 10,
        colors: colorRange,
        addedAlpha: 1,
        strokeColor: strokeColor,
        hueSpeed: .1,
        repaintAlpha: 1
      },

      difX = Math.sqrt(3) * opts.side / 2,
      difY = opts.side * 3 / 2,
      rad = Math.PI / 6,
      cos = Math.cos(rad) * opts.side,
      sin = Math.sin(rad) * opts.side,

      hexs = [],
      tick = 0;

      function loop() {

        window.requestAnimationFrame(loop);

        tick += opts.hueSpeed;

        ctx.shadowBlur = 0;

        var backColor;
        if (colorToUse === 'red') {
          backColor = 'rgba(232, 28, 47, 0.9)';
        }
        else {
          backColor = 'rgba(255, 255, 255, 0.5)';
        }
        ctx.fillStyle = backColor.replace('alp', opts.repaintAlpha);
        ctx.fillRect(0, 0, w, h);

        for (var i = 0; i < opts.picksParTick; ++i)
          hexs[(Math.random() * hexs.length) | 0].pick();

        hexs.map(function(hex) {
          hex.step();
        });
      }

      function Hex(x, y) {

        this.x = x;
        this.y = y;
        this.sum = this.x + this.y;
        // change between false and true to animate from left to right, or all at once
        this.picked = false;
        this.time = 0;
        this.targetTime = 0;

        this.xs = [this.x + cos, this.x, this.x - cos, this.x - cos, this.x, this.x + cos];
        this.ys = [this.y - sin, this.y - opts.side, this.y - sin, this.y + sin, this.y + opts.side, this.y + sin];
      }
      Hex.prototype.pick = function() {

        this.color = opts.colors[(Math.random() * opts.colors.length) | 0];
        this.picked = true;
        this.time = this.time || 0;
        this.targetTime = this.targetTime || (opts.baseTime + opts.addedTime * Math.random()) | 0;
      }
      Hex.prototype.step = function() {

        var prop = this.time / this.targetTime;

        ctx.beginPath();
        ctx.moveTo(this.xs[0], this.ys[0]);
        for (var i = 1; i < this.xs.length; ++i)
          ctx.lineTo(this.xs[i], this.ys[i]);
        ctx.lineTo(this.xs[0], this.ys[0]);

        if (this.picked) {

          ++this.time;

          if (this.time >= this.targetTime) {

            this.time = 0;
            this.targetTime = 0;
            this.picked = false;
          }

          ctx.fillStyle = ctx.shadowColor = this.color.replace('alp', Math.sin(prop * Math.PI));
          ctx.fill();
        } else {

          ctx.strokeStyle = ctx.shadowColor = opts.strokeColor;
          ctx.stroke();
        }
      }

      for (var x = 0; x < w; x += difX * 2) {
        var i = 0;

        for (var y = 0; y < h; y += difY) {
          ++i;
          hexs.push(new Hex(x + difX * (i % 2), y));

        }
      }
      loop();

      const resizeObserver = new ResizeObserver(entries => {
          for (let entry of entries) {
              const newW = entry.contentRect.width;
              const newH = entry.contentRect.height;

              if (Math.abs(w - newW) > 1 || Math.abs(h - newH) > 1) {
                  w = can.width = newW;
                  h = can.height = newH;
                  sum = w + h;

                  hexs.length = 0;
                  for (var x = 0; x < w; x += difX * 2) {
                      var i = 0;
                      for (var y = 0; y < h; y += difY) {
                          ++i;
                          hexs.push(new Hex(x + difX * (i % 2), y));
                      }
                  }
              }
          }
      });
      resizeObserver.observe($container[0]);
    });
  });
})(jQuery);
