<?php
// Adicionar menu no painel de administração
function mc_status_menu() {
    add_menu_page(
        'Server Status Minecraft', // Título da página
        'Bcraft Status',       // Nome do menu
        'manage_options',         // Permissão necessária
        'mc-status-settings',     // Slug
        'mc_status_settings_page',// Função de callback
        'dashicons-admin-generic',// Ícone
        100                       // Posição no menu
    );
}
add_action('admin_menu', 'mc_status_menu');

// Renderizar a página de configuração
function mc_status_settings_page() {
    ?>
    <div class="wrap">
        <h1>Server Status Minecraft</h1>
        <p>Configure as opções abaixo para personalizar o widget do servidor Minecraft em seu site.</p>
        <form method="post" action="options.php">
            <?php
            settings_fields('mc_status_group');
            do_settings_sections('mc-status-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar as configurações
function mc_status_register_settings() {
    register_setting('mc_status_group', 'mc_server_ip', ['default' => '']);
    register_setting('mc_status_group', 'mc_bg_color', ['default' => '#2c3e50']);
    register_setting('mc_status_group', 'mc_height', ['default' => 'auto']);
    register_setting('mc_status_group', 'mc_width', ['default' => '400px']);
    register_setting('mc_status_group', 'mc_icon', ['default' => 'mdi-play-circle']);

    add_settings_section(
        'mc_status_main_section',
        'Configurações do Widget',
        null,
        'mc-status-settings'
    );

    // Campo para o IP do servidor
    add_settings_field(
        'mc_server_ip',
        'IP do Servidor',
        'mc_server_ip_callback',
        'mc-status-settings',
        'mc_status_main_section'
    );

    // Campo para cor de fundo
    add_settings_field(
        'mc_bg_color',
        'Cor de Fundo',
        'mc_bg_color_callback',
        'mc-status-settings',
        'mc_status_main_section'
    );

    // Campo para altura
    add_settings_field(
        'mc_height',
        'Altura do Widget',
        'mc_height_callback',
        'mc-status-settings',
        'mc_status_main_section'
    );

    // Campo para largura
    add_settings_field(
        'mc_width',
        'Largura do Widget',
        'mc_width_callback',
        'mc-status-settings',
        'mc_status_main_section'
    );

    // Campo para ícone
    add_settings_field(
        'mc_icon',
        'Ícone',
        'mc_icon_callback',
        'mc-status-settings',
        'mc_status_main_section'
    );
}
add_action('admin_init', 'mc_status_register_settings');

// Callbacks para os campos
function mc_server_ip_callback() {
    $ip = get_option('mc_server_ip');
    echo '<input type="text" name="mc_server_ip" value="' . esc_attr($ip) . '" style="width: 300px;" placeholder="mc.bcraft.com.br">';
}

function mc_bg_color_callback() {
    $color = get_option('mc_bg_color');
    echo '<input type="color" name="mc_bg_color" value="' . esc_attr($color) . '">';
}

function mc_height_callback() {
    $height = get_option('mc_height');
    echo '<input type="text" name="mc_height" value="' . esc_attr($height) . '" style="width: 100px;" placeholder="auto">';
}

function mc_width_callback() {
    $width = get_option('mc_width');
    echo '<input type="text" name="mc_width" value="' . esc_attr($width) . '" style="width: 100px;" placeholder="400px">';
}

function mc_icon_callback() {
    $icon = get_option('mc_icon');
    echo '<input type="text" name="mc_icon" value="' . esc_attr($icon) . '" style="width: 300px;" placeholder="mdi-play-circle">';
    echo '<p><small>Insira o nome da classe do ícone (ex.: mdi-play-circle). Consulte os ícones disponíveis em <a href="https://materialdesignicons.com/" target="_blank">Material Design Icons</a>.</small></p>';
}