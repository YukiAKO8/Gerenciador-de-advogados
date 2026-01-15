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

    echo '<div id="aer-listagem-container">';
    require_once plugin_dir_path( __FILE__ ) . 'views/listagem.php';
    echo '</div>';


    echo '<div id="aer-cadastro-container">'; 
    require_once plugin_dir_path( __FILE__ ) . 'views/cadastro-novo.php';
    echo '</div>';
}
