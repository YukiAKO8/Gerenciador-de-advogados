<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
$response = obter_funcionarios_da_api();

$funcionarios = json_decode($response['data'], true);



// --- LÓGICA PARA CONTAR OS STATUS ---
$total_funcionarios = 0;
$total_ativos = 0;
$total_inativos = 0;

if (!empty($funcionarios) && is_array($funcionarios)) {
    $total_funcionarios = count($funcionarios);
    foreach ($funcionarios as $adv) {
        // Considera 'ativo' como padrão se o status não estiver definido ou for diferente de 'inativo'
        // Verifica também o campo 'ativo' (1 ou 0)
        if ((isset($adv['ativo']) && $adv['ativo'] == 0) || (isset($adv['status']) && $adv['status'] === 'inativo')) {
            $total_inativos++;
        } else {
            $total_ativos++;
        }
    }
}

?>
<script>
    let user_atual=<?php echo get_current_user_id(); ?>;
    </script>

<style>
    /* Estilos para o canvas de fundo p5.js */
    #p5-canvas-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0; /* Coloca o canvas atrás de outro conteúdo */
    }
    /* Ajuste para garantir que o conteúdo do plugin fique na frente do canvas */
    .wrap {
        position: relative; /* Necessário para o z-index funcionar */
        z-index: 1; 
    }
</style>

<div class="wrap"><div class="aer-plugin-wrapper">
    <div class="main-content-container">
        <!-- Fundo animado Light System -->
        <div class="animated-background"></div>

        <div class="plugin-header">
            <div class="scene">
                <div class="badge">
                    <!-- FRENTE -->
                    <div class="face front">
                        <svg width="160" height="260" viewBox="0 0 160 260">
                            <defs>
                                <linearGradient id="holo" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#cdb4ff"/>
                                    <stop offset="50%" stop-color="#a2d2ff"/>
                                    <stop offset="100%" stop-color="#ffc8dd"/>
                                </linearGradient>
                            </defs>
                            <rect x="10" y="40" width="140" height="200" rx="24" fill="url(#holo)" />
                            <circle cx="80" cy="55" r="6" fill="rgba(255,255,255,0.6)"/>
                            <!-- Bonequinho MSN -->
                            <circle cx="80" cy="120" r="16" fill="#fff"/>
                            <circle cx="80" cy="165" r="24" fill="#fff"/>
                            <!-- Gravata -->
                            <polygon points="80,142 72,160 80,170 88,160" fill="rgba(0,0,0,0.18)"/>
                        </svg>
                    </div>
                    <!-- VERSO COM ENGRENAGEM -->
                    <div class="face back">
                        <svg width="160" height="260" viewBox="0 0 160 260">
                            <defs>
                                <radialGradient id="gearGlow" cx="50%" cy="50%" r="60%">
                                    <stop offset="0%" stop-color="rgba(0,0,0,0.1)"/>
                                    <stop offset="100%" stop-color="rgba(0,0,0,0.05)"/>
                                </radialGradient>
                            </defs>
                            <rect x="10" y="40" width="140" height="200" rx="24" fill="rgba(0,0,0,0.05)" />
                            <g transform="translate(80 140)">
                                <!-- DENTES GROSSOS -->
                                <g fill="rgba(0,0,0,0.2)">
                                    <rect x="-7" y="-64" width="14" height="26" rx="3"/>
                                    <rect x="-7" y="-64" width="14" height="26" rx="3" transform="rotate(45)"/>
                                    <rect x="-7" y="-64" width="14" height="26" rx="3" transform="rotate(90)"/>
                                    <rect x="-7" y="-64" width="14" height="26" rx="3" transform="rotate(135)"/>
                                    <rect x="-7" y="-64" width="14" height="26" rx="3" transform="rotate(180)"/>
                                    <rect x="-7" y="-64" width="14" height="26" rx="3" transform="rotate(225)"/>
                                    <rect x="-7" y="-64" width="14" height="26" rx="3" transform="rotate(270)"/>
                                    <rect x="-7" y="-64" width="14" height="26" rx="3" transform="rotate(315)"/>
                                </g>
                                <!-- CORPO DA ENGRENAGEM -->
                                <circle r="40" fill="url(#gearGlow)" />
                                <circle r="26" fill="rgba(255,255,255,0.35)" />
                                <circle r="8"  fill="rgba(0,0,0,0.45)" />
                            </g>
                        </svg>
                    </div>
                    <div class="glow"></div>
                </div>
            </div>
            <div class="plugin-header-text">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <h2 class="plugin-subtitle">Organizar, centralizar e facilitar o controle de todas as informações relacionadas aos profissionais do SNA</h2>
            </div>
        </div>
        <div class="container-retangulos">
            <div class="retangulo retangulo-total" data-filter="todos">
                <h3>Total de funcionários</h3>
                <p class="numero"><?php echo esc_html($total_funcionarios); ?></p>
            </div>
            <div class="retangulo retangulo-ativos" data-filter="ativo">
                <h3>Total Ativos</h3>
                <p class="numero"><?php echo esc_html($total_ativos); ?></p>
            </div>
            <div class="retangulo retangulo-inativos" data-filter="inativo">
                <h3>Total inativos</h3>
                <p class="numero"><?php echo esc_html($total_inativos); ?></p>
            </div>
        </div>


        <div class="principal-listagem">
            <div class="listagem-header">
                <h2>Funcionarios Cadastrados</h2>
                <div class="listagem-actions">
                    <input type="text" id="filtro-nome-funcionario" class="search-input" placeholder="Pesquisar por nome...">
                    <a href="#" class="botao-adicionar-funcionario"><span class="dashicons dashicons-plus-alt"></span>Novo Funcionario</a>
                </div>
            </div>
            

            <table class="tabela-funcionarios">
                <thead>
                    <tr><th>Nome</th>
                    <th>CPF</th>
                    <th>Setor</th>
                    <th style="width: 120px;">Status</th></tr>
                </thead>
                <tbody>
                    <?php
            
                    if (!empty($funcionarios)) {
                        foreach ($funcionarios as $adv) {
                            // Define a classe e o texto do status
                            $is_inativo = (isset($adv['ativo']) && $adv['ativo'] == 0) || (isset($adv['status']) && $adv['status'] === 'inativo');
                            $status_class = $is_inativo ? 'status-inativo' : 'status-ativo';
                            $status_text  = $is_inativo ? 'Inativo' : 'Ativo';
                            $status_value = $is_inativo ? 'inativo' : 'ativo';

                            // Armazena os dados do funcionario como um atributo data-
                            echo "<tr class='funcionario-row' data-status='" . esc_attr($status_value) . "' data-funcionario='" . esc_attr(json_encode($adv)) . "'>";
                            echo "<td>" . esc_html($adv['nome']) . "</td><td>" . esc_html($adv['cpf_cnpj']) . "</td><td>" . esc_html($adv['setor'] ?? '-') . "</td>";
                            echo "<td><span class='status-indicator " . $status_class . "'>" . esc_html($status_text) . "</span></td></tr>";
                        }
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

function obter_funcionarios_da_api() {
    $api_base = get_rest_url(null, 'aer-api/funcionarios/getAllFuncionarios'); 
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
        error_log( 'Erro ao conectar à API de funcionarios: ' . $response->get_error_message() );
        return []; // Retorna um array vazio em caso de erro
    }
    
    // 2. Verifica o código de status HTTP da resposta
    $http_status = wp_remote_retrieve_response_code( $response );
    if ( 200 !== $http_status ) {
        error_log( 'API de funcionarios retornou status HTTP inesperado: ' . $http_status . ' - Body: ' . wp_remote_retrieve_body( $response ) );
        return []; // Retorna um array vazio se o status não for 200 OK
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // 3. Verifica se houve um erro na decodificação do JSON
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        error_log( 'Erro ao decodificar JSON da API de funcionarios: ' . json_last_error_msg() . ' - Body: ' . $body );
        return []; // Retorna um array vazio em caso de erro de JSON
    }
    
    // Garante que o dado retornado seja um array, caso contrário, retorna um array vazio
    return is_array($data) ? $data : [];
}
