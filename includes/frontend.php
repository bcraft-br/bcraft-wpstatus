<?php
// Shortcode para exibir o status do servidor
function mc_status_shortcode() {
    $server_ip = get_option('mc_server_ip');
    if (!$server_ip) {
        return '<p>Por favor, configure o IP do servidor nas configurações.</p>';
    }

    $status = get_minecraft_server_status($server_ip);

    if (!$status) {
        return '<p>Servidor offline ou IP inválido.</p>';
    }

    // Obter configurações personalizadas
    $bg_color = get_option('mc_bg_color', '#2c3e50');
    $height = get_option('mc_height', 'auto');
    $width = get_option('mc_width', '400px');
    $icon = get_option('mc_icon', 'mdi-play-circle');
    $custom_icon = get_option('mc_custom_icon', '');

    // Determinar o ícone final
    $final_icon = ($icon === 'custom' && !empty($custom_icon)) ? $custom_icon : $icon;

    ob_start();
    ?>
    <style>
        :root {
            --spacing: 10px;
            --color-accent: #3DD1FF;
            --white: #ffffff;
            --border: #000000;
        }

        .server {
            display: flex;
            align-items: center;
            gap: var(--spacing);
            cursor: pointer;
            padding: 0px 10px 0px 10px;
            background: <?php echo esc_attr($bg_color); ?>;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: <?php echo esc_attr($width); ?>;
            height: <?php echo esc_attr($height); ?>;
            margin: 0 auto;
        }

        .server__icon {
            color: #ffffff;
            font-size: 5em;
            animation: float 6s ease-in-out infinite;
        }

        .server__icon>span:before {
            text-shadow: 0px 0px 7px #3DD1FF33;
        }

        .server__title {
            color: var(--white);
            font-weight: bold;
            font-size: 1.3em;
        }

        .server__name {
            color: var(--white);
            font-weight: 500;
            font-size: 1.1em;
        }

        .server__online {
            color: #ffffff;
            font-weight: bold;
        }

        .server__online>.highlighted {
            background: #ffffff;
            border-radius: 25px;
            padding: 0 var(--spacing);
            color: var(--border);
        }

        /* Animação de flutuação */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>

    <div class="server" data-clipboard-text="<?php echo esc_attr($server_ip); ?>">
        <div class="server__icon">
            <span class="<?php echo esc_attr($final_icon); ?>"></span>
        </div>
        <div class="server__info">
            <div class="server__online">
                <span id="minecraft-online" class="highlighted"><?php echo esc_html($status['players']); ?></span> players online
            </div>
            <div class="server__title">Conecte-se pelo IP abaixo</div>
            <div class="server__name"><?php echo esc_html($server_ip); ?></div>
        </div>
    </div>

    <!-- Modal de confirmação -->
    <div id="mc-copy-modal">IP copiado com sucesso!</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const serverElement = document.querySelector('.server');
            const modal = document.getElementById('mc-copy-modal');

            if (serverElement && modal) {
                const ip = serverElement.getAttribute('data-clipboard-text');

                // Copiar ao clicar no widget
                serverElement.addEventListener('click', function () {
                    navigator.clipboard.writeText(ip).then(function () {
                        modal.style.display = 'block';
                        setTimeout(() => modal.style.display = 'none', 2000);
                    }).catch(function (error) {
                        console.error('Erro ao copiar o IP:', error);
                    });
                });
            }
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('minecraft_status', 'mc_status_shortcode');