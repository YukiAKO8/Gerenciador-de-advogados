<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

?>

<form id="form-novo-advogado" method="post">
    <!-- Fundo animado Hexagonal -->
    <div class="animated-background" data-color="white"></div>

    <h1 style="margin-bottom: 30px; font-size: 24px;">Funcionários</h1>
    <!-- Campo oculto para armazenar o ID do advogado durante a edição -->
    <input type="hidden" id="advogado_id" name="advogado_id" value="">

    <div class="form-row">
        <div class="form-field">
            <label for="advogado_nome">Nome</label>
            <input type="text" id="advogado_nome" name="advogado_nome" class="regular-text" value="">
        </div>
        <div class="form-field">
            <label for="advogado_cpf_cnpj">CPF/CNPJ</label>
            <input type="text" id="advogado_cpf_cnpj" name="advogado_cpf_cnpj" class="regular-text" value="">
        </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label for="setor">Setor</label>
            <select id="setor" name="setor" class="regular-text">
                <option value="">Selecione...</option>
                <option value="Apoio">Apoio</option>
                <option value="Financeiro">Financeiro</option>
                <option value="TI">TI</option>
                <option value="Infraestrutura">Infraestrutura</option>
                <option value="RH">RH</option>
                <option value="Administrativo/Beneficios">Administrativo/Beneficios</option>
                <option value="Arquivo">Arquivo</option>
                <option value="Safety">Safety</option>
                <option value="Juridico">Juridico</option>
                <option value="Diretoria">Diretoria</option>
                <option value="Secretariado">Secretariado</option>
                <option value="Comunicação">Comunicação</option>
                <option value="Funcionario Externo">Funcionario Externo</option>
            </select>
        </div>
        <div class="form-field">
            <label for="advogado_status">Status</label>
            <select id="advogado_status" name="advogado_status" class="regular-text">
                <option value="ativo" selected>Ativo</option>
                <option value="inativo">Inativo</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field" style="flex: 3;">
            <label for="advogado_endereco">Endereço</label>
            <input type="text" id="advogado_endereco" name="advogado_endereco" class="regular-text" value="">
        </div>
        <div class="form-field" style="flex: 1;">
            <label for="advogado_uf">UF</label>
            <input type="text" id="advogado_uf" name="advogado_uf" class="regular-text" value="">
                </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label for="advogado_cidade">Cidade</label>
            <input type="text" id="advogado_cidade" name="advogado_cidade" class="regular-text" value="">
        </div>
        <div class="form-field">
            <label for="advogado_bairro">Bairro</label>
            <input type="text" id="advogado_bairro" name="advogado_bairro" class="regular-text" value="">
        </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label for="advogado_telefone">Telefone</label>
            <input type="tel" id="advogado_telefone" name="advogado_telefone" class="regular-text" value="">
        </div>
        <div class="form-field">
            <label for="advogado_email">E-mail</label>
            <input type="email" id="advogado_email" name="advogado_email" class="regular-text" value="">
        </div>
    </div>

    <div class="form-buttons">
        <button type="submit" name="submit" id="submit" class="button salvar-adv"><span class="dashicons dashicons-saved"></span> Salvar Advogado</button>
        <button type="button" class="button voltar-para-lista" id="botao-voltar-listagem"><span class="dashicons dashicons-arrow-left-alt"></span> Voltar</button>
    </div>
</form>