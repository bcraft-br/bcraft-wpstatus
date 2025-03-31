<?php
/*
Plugin Name: Bcraft - Server Status
Description: Exibe o status do servidor Minecraft e permite copiar o IP.
Version: 2.1.5
Author: Bcraft
Author URI: https://www.bcraft.com.br
License: GPLv2 or later
Text Domain: minecraft-status
*/

// Evitar acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Função para obter o status do servidor
function get_minecraft_server_status($ip) {
    $url = "https://api.mcsrvstat.us/2/$ip";
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!$data || !isset($data['online']) || !$data['online']) {
        return false;
    }

    return [
        'players' => $data['players']['online'],
        'max_players' => $data['players']['max'],
    ];
}

// Enfileirar arquivos CSS externos
function mc_status_enqueue_assets() {
    // Enfileirar Material Design Icons
    wp_enqueue_style(
        'material-design-icons',
        'https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css',
        [],
        '7.0.96'
    );

    // Enfileirar o CSS personalizado
    wp_enqueue_style(
        'mc-status-style',
        plugins_url('assets/css/style.css', __FILE__),
        [],
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'mc_status_enqueue_assets');

// Incluir arquivos adicionais
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/frontend.php';