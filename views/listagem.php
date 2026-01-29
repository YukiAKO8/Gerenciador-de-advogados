<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
?>
<div class="wrap"><div class="container-principal-listagem">
    <div class="container-envolve-conteudo">
        <!-- Fundo animado Light System -->
        <div class="animated-background"></div>

        <div class="titulo-do-plugin">
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
                <h2 class="subtitulo-plugin">Organizar, centralizar e facilitar o controle de todas as informações relacionadas aos profissionais do SNA</h2>
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
            <div class="titulo-listagem">
                <h2>Funcionarios Cadastrados</h2>
                <div class="acao-listagem">
                    <input type="text" id="filtro-nome-funcionario" class="input-da-pesquisa" placeholder="Pesquisar por nome, CPF ou setor...">
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
                            echo "<td><span class='indicador-status " . $status_class . "'>" . esc_html($status_text) . "</span></td></tr>";
                        }
                    }

?></tbody>
            </table>

            <div class="paginacao-container">
                <div class="paginacao-spacer"></div>
                <div class="paginacao-controles">
                    <button type="button" id="btn-anterior" class="botao-paginacao" disabled>&laquo; Anterior</button>
                    <span id="info-paginacao">Página 1 de 1</span>
                    <button type="button" id="btn-proximo" class="botao-paginacao">Próximo &raquo;</button>
                </div>
                <div class="paginacao-acoes">
                    <a href="#" class="botao-adicionar-funcionario"><span class="dashicons dashicons-plus-alt"></span>Novo Funcionario</a>
                </div>
            </div>
        </div>
    </div>
</div></div>

<canvas class='connecting-dots'></canvas>
<!-- Contêiner para a animação p5.js -->
<div id="p5-canvas-container"></div>

<!-- Inclui a biblioteca p5.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.0/p5.js"></script>
