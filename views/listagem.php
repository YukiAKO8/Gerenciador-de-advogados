<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
$advogados = obter_advogados_da_api();
?>
<script>
    let user_atual=<?php echo get_current_user_id(); ?>;
    </script>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

 
    <div class="container-retangulos">
        <div class="retangulo">
            <h3>Total de advogados</h3>
            <p class="numero">0</p>
        </div>
        <div class="retangulo">
            <h3>Total Ativos</h3>
            <p class="numero">0</p>
        </div>
        <div class="retangulo">
            <h3>Total inativos</h3>
            <p class="numero">0</p>
        </div>
    </div>


    <div class="principal-listagem">
        <h2>Advogados Cadastrados</h2>
        <p></p>
        

        <table class="tabela-advogados">
            <thead>
                <tr><th>Nome</th>
                <th>CPF</th>
                <th>Status</th></tr>
            </thead>
            <tbody><tr><td colspan="3">
            <i>Sem advogados cadastrados.</i>
        </td></tr></tbody>
        </table>
    </div>

    <a href="#" class="botao-adicionar-advogado">Adicionar Advogado</a>
</div>

<?php

function obter_advogados_da_api() {
    $api_base = get_rest_url(null, 'aer-api/advogados/getAll'); // define antes

    $args = array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/json; charset=utf-8',
        ),
        'timeout' => 15,
        'sslverify' => false,
    );

    $response = wp_remote_post($api_base, $args);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return is_array($data) ? $data : [];
}
