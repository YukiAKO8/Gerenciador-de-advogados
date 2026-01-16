<?php
/**
 * Plugin Name:       Cadastro de Funcionarios
 * Plugin URI:        https://example.com/
 * Description:       Plugin para gerenciar cadastro de funcionarios.
 * Version:           1.0.0
 * Author:            Yuki
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cadastro-funcionarios
 * Domain Path:       /languages
 */

// Se este arquivo for chamado diretamente, aborte.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Enfileira os scripts e estilos do plugin na página de administração.
 *
 * @param string $hook O hook da página atual.
 */
function includes_scripts_aer_funcionarios( $hook ) {

    if ( 'toplevel_page_aer-gerenciador-funcionarios' !== $hook ) {
        return;
    }


    wp_enqueue_style(
        'aer-funcionarios-style',
        plugin_dir_url( __FILE__ ) . 'views/style-gerenciador-funcionarios.css',
        array(),
        '1.0.1' 
    );


    wp_enqueue_script(
        'aer-funcionarios-js',
        plugin_dir_url( __FILE__ ) . 'Js/java-gerenciador-funcionarios.js',
        array( 'jquery' ),
        '1.0.1',
        true 
    );
    wp_enqueue_script(
        'jshelpers',
        plugins_url('../sna-helpers/includes/functions.js', __FILE__),
        array('jquery'), 
        '1.0', 
        true
    );
    wp_enqueue_script(
        'jsvalidator', 
        plugins_url('../sna-helpers/includes/snaValidatorFront.js', __FILE__),
        array('jquery'), 
        '1.0', 
        true
    );


    wp_localize_script(
        'aer-funcionarios-js', 
        'AerApiSettings',   
        array(             
            'root'    => esc_url_raw(rest_url()), 
            'nonce'   => wp_create_nonce('wp_rest'),
            'user_id' => get_current_user_id(), // Passando ID do usuário para o JS
        )
    );
}
add_action( 'admin_enqueue_scripts', 'includes_scripts_aer_funcionarios' );


function aer_gerenciador_funcionarios_admin_menu() {
    add_menu_page(
        'Funcionários',
        'Cadastro de Funcionários',                
        'manage_options',          
        'aer-gerenciador-funcionarios',
        'aer_gerenciador_funcionarios_page_html', 
        'dashicons-groups'     
    );
}
add_action( 'admin_menu', 'aer_gerenciador_funcionarios_admin_menu' );


function aer_gerenciador_funcionarios_page_html() {

    // --- CONTROLLER: Busca dados e prepara variáveis para a View ---
    $response = obter_funcionarios_da_api();
    // Decodifica o JSON retornado pela API
    $funcionarios = isset($response['data']) ? json_decode($response['data'], true) : [];

    $total_funcionarios = 0;
    $total_ativos = 0;
    $total_inativos = 0;

    if (!empty($funcionarios) && is_array($funcionarios)) {
        $total_funcionarios = count($funcionarios);
        foreach ($funcionarios as $adv) {
            if ((isset($adv['ativo']) && $adv['ativo'] == 0) || (isset($adv['status']) && $adv['status'] === 'inativo')) {
                $total_inativos++;
            } else {
                $total_ativos++;
            }
        }
    }

    echo '<div id="aer-listagem-container">';
    require_once plugin_dir_path( __FILE__ ) . 'views/listagem.php';
    echo '</div>';


    echo '<div id="aer-cadastro-container">'; 
    require_once plugin_dir_path( __FILE__ ) . 'views/cadastro-novo.php';
    echo '</div>';
}

// --- MODEL: Função para buscar dados da API ---
function obter_funcionarios_da_api() {
    $api_base = get_rest_url(null, 'aer-api/funcionarios/getAllFuncionarios'); 
    $args = array(
        'method'      => 'POST', 
        'headers'     => array(
            'Content-Type' => 'application/json; charset=utf-8',
        ),
        'timeout'     => 15, 
        'sslverify'   => false, 
    );
    
    $response = wp_remote_post($api_base, $args);
    
    if ( is_wp_error( $response ) ) {
        error_log( 'Erro ao conectar à API de funcionarios: ' . $response->get_error_message() );
        return []; 
    }
    
    $http_status = wp_remote_retrieve_response_code( $response );
    if ( 200 !== $http_status ) {
        error_log( 'API de funcionarios retornou status HTTP inesperado: ' . $http_status . ' - Body: ' . wp_remote_retrieve_body( $response ) );
        return []; 
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        error_log( 'Erro ao decodificar JSON da API de funcionarios: ' . json_last_error_msg() . ' - Body: ' . $body );
        return []; 
    }
    
    return is_array($data) ? $data : [];
}
