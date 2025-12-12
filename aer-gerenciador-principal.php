<?php
/**
 * Plugin Name:       Cadastro de Advogados
 * Plugin URI:        https://example.com/
 * Description:       Plugin para gerenciar cadastro de advogados.
 * Version:           1.0.0
 * Author:            Yuki
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cadastro-advogados
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
function includes_scripts_aer_advogados( $hook ) {

    if ( 'toplevel_page_aer-gerenciador-advogados' !== $hook ) {
        return;
    }


    wp_enqueue_style(
        'aer-advogados-style',
        plugin_dir_url( __FILE__ ) . 'views/style-gerenciador-advogados.css',
        array(),
        '1.0.1' 
    );


    wp_enqueue_script(
        'aer-advogados-js',
        plugin_dir_url( __FILE__ ) . 'Js/java-gerenciador-advogados.js',
        array( 'jquery' ),
        '1.0.1',
        true 
    );
    wp_enqueue_script(
        'jshelpers', // ID único
        plugins_url('../sna-helpers/includes/functions.js', __FILE__),
        array('jquery'), // Dependências: jQuery
        '1.0', // Versão
        true
    );
    wp_enqueue_script(
        'jsvalidator', // ID único
        plugins_url('../sna-helpers/includes/snaValidatorFront.js', __FILE__),
        array('jquery'), // Dependências: jQuery
        '1.0', // Versão
        true
    );

    // Passa dados do PHP para o JavaScript (URL da API, nonce, etc.)
    // Esta função deve ser chamada DEPOIS de wp_enqueue_script
    wp_localize_script(
        'aer-advogados-js', // 1. O handle do script que receberá os dados
        'AerApiSettings',   // 2. O nome do objeto JavaScript que será criado
        array(              // 3. Os dados a serem passados
            'root'    => esc_url_raw(rest_url()), // URL base da API (ex: /wp-json/)
            'nonce'   => wp_create_nonce('wp_rest'),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'includes_scripts_aer_advogados' );


function aer_gerenciador_advogados_admin_menu() {
    add_menu_page(
        'Gerenciador de Advogados',
        'Cadastro de Advogados',                
        'manage_options',          
        'aer-gerenciador-advogados',
        'aer_gerenciador_advogados_page_html', 
        'dashicons-groups'     
    );
}
add_action( 'admin_menu', 'aer_gerenciador_advogados_admin_menu' );


function aer_gerenciador_advogados_page_html() {

    echo '<div id="aer-listagem-container">';
    require_once plugin_dir_path( __FILE__ ) . 'views/listagem.php';
    echo '</div>';


    echo '<div id="aer-cadastro-container" style="display: none;">';
    require_once plugin_dir_path( __FILE__ ) . 'views/cadastro-novo.php';
    echo '</div>';

            wp_localize_script(
        'java-gerenciador-advogados',
        'aer-advogados-js',
        'AerApiSettings',
        array(
            'root' => esc_url_raw(rest_url()),
            'endpoint' => 'aer-api',
            'nonce' => wp_create_nonce('wp_rest'),
        )
    );
}


