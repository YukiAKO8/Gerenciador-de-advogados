<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
$response = obter_advogados_da_api();

$advogados = json_decode($response['data'], true);

?>
<script>
    let user_atual=<?php echo get_current_user_id(); ?>;
    </script>

<style>
    canvas.connecting-dots {
    body {
        background-color: #333;
    }
    #p5-canvas-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 0; /* Coloca o canvas atrás do conteúdo */
        z-index: 0;
    }
    .wrap {
        position: relative;
        z-index: 1; /* Garante que o conteúdo fique na frente do canvas */
        background-color: rgba(255, 255, 255, 0.85); /* Fundo semi-transparente para melhor leitura */
        padding: 20px;
        border-radius: 8px;
    }
</style>

<div class="wrap"><div class="aer-plugin-wrapper">
    <div class="main-content-container">
        <div class="animation-container">
            <div class="squares">
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
            </div>
        </div>
        <div class="plugin-header">
            <img src="<?php echo plugin_dir_url( __FILE__ ) . '../views/LogoCracha.png'; ?>" alt="Logo" class="plugin-logo">
            <div class="plugin-header-text">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <h2 class="plugin-subtitle">Organizar, centralizar e facilitar o controle de todas as informações relacionadas aos profissionais da área jurídica</h2>
            </div>
        </div>
        <div class="container-retangulos">
            <div class="retangulo retangulo-total">
                <h3>Total de advogados</h3>
                <p class="numero">0</p>
            </div>
            <div class="retangulo retangulo-ativos">
                <h3>Total Ativos</h3>
                <p class="numero">0</p>
            </div>
            <div class="retangulo retangulo-inativos">
                <h3>Total inativos</h3>
                <p class="numero">0</p>
            </div>
        </div>


        <div class="principal-listagem">
            <div class="listagem-header">
                <h2>Advogados Cadastrados</h2>
                <a href="#" class="botao-adicionar-advogado"><span class="dashicons dashicons-plus-alt"></span>Adicionar Advogado</a>
            </div>
            

            <table class="tabela-advogados">
                <thead>
                    <tr><th>Nome</th>
                    <th>CPF</th>
                    <th>Status</th></tr>
                </thead>
                <tbody>
                    <?php
            
foreach ($advogados as $adv) {
    echo "<tr><td>" . $adv['nome'] . "</td><td>" . $adv['cpf_cnpj'] . "</td><td></td></tr>";
}

?></tbody>
            </table>
        </div>
    </div>
</div></div>

<canvas class='connecting-dots'></canvas>
<!-- Contêiner para a animação p5.js -->
<div id="p5-canvas-container"></div>

<!-- Inclui a biblioteca p5.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.0/p5.js"></script>
<?php

function obter_advogados_da_api() {
    $api_base = get_rest_url(null, 'aer-api/advogados/getAllAdvogados'); 
    echo $api_base;
    $args = array(
        'method'      => 'POST', // Assumindo que a API realmente espera um POST para 'getAll'
        'headers'     => array(
            'Content-Type' => 'application/json; charset=utf-8',
        ),
        'timeout'     => 15, // Tempo limite da requisição em segundos
        'sslverify'   => false, // AVISO: Desabilitar a verificação SSL é um risco de segurança em produção.
                               // Use apenas se você entender as implicações e tiver um motivo específico.
    );
    
    $response = wp_remote_post($api_base, $args);
    // 1. Verifica se houve um erro na requisição HTTP (ex: falha de conexão)
    if ( is_wp_error( $response ) ) {
        error_log( 'Erro ao conectar à API de advogados: ' . $response->get_error_message() );
        return []; // Retorna um array vazio em caso de erro
    }
    
    // 2. Verifica o código de status HTTP da resposta
    $http_status = wp_remote_retrieve_response_code( $response );
    if ( 200 !== $http_status ) {
        error_log( 'API de advogados retornou status HTTP inesperado: ' . $http_status . ' - Body: ' . wp_remote_retrieve_body( $response ) );
        return []; // Retorna um array vazio se o status não for 200 OK
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // 3. Verifica se houve um erro na decodificação do JSON
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        error_log( 'Erro ao decodificar JSON da API de advogados: ' . json_last_error_msg() . ' - Body: ' . $body );
        return []; // Retorna um array vazio em caso de erro de JSON
    }
    
    // Garante que o dado retornado seja um array, caso contrário, retorna um array vazio
    return is_array($data) ? $data : [];
}
