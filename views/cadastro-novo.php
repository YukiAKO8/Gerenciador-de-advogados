<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

?>

<form id="form-novo-funcionario" method="post">
    <button type="button" class="fechar-modal-x" id="botao-fechar-modal">&times;</button>
    <!-- Fundo animado Light System -->
    <div class="animated-background"></div>

    <h1 style="margin-bottom: 30px; font-size: 24px;">Funcionários</h1>
    <!-- Campo oculto para armazenar o ID do funciinario durante a edição -->
    <input type="hidden" id="funcionario_id" name="funcionario_id" value="">

    <div class="linha-formulario">
        <div class="campo-formulario">
            <label for="funcionario_nome">Nome</label>
            <input type="text" id="funcionario_nome" name="funcionario_nome" class="regular-text" value="">
        </div>
        <div class="campo-formulario">
            <label for="funcionario_cpf_cnpj">CPF/CNPJ</label>
            <input type="text" id="funcionario_cpf_cnpj" name="funcionario_cpf_cnpj" class="regular-text" value="">
        </div>
    </div>

    <div class="linha-formulario">
        <div class="campo-formulario">
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
        <div class="campo-formulario">
            <label for="funcionario_status">Status</label>
            <select id="funcionario_status" name="funcionario_status" class="regular-text">
                <option value="ativo" selected>Ativo</option>
                <option value="inativo">Inativo</option>
            </select>
        </div>
    </div>

    <div class="linha-formulario">
        <div class="campo-formulario" style="flex: 3;">
            <label for="funcionario_endereco">Endereço</label>
            <input type="text" id="funcionario_endereco" name="funcionario_endereco" class="regular-text" value="">
        </div>
        <div class="campo-formulario" style="flex: 1;">
            <label for="funcionario_uf">UF</label>
            <input type="text" id="funcionario_uf" name="funcionario_uf" class="regular-text" value="">
                </div>
    </div>

    <div class="linha-formulario">
        <div class="campo-formulario">
            <label for="funcionario_cidade">Cidade</label>
            <input type="text" id="funcionario_cidade" name="funcionario_cidade" class="regular-text" value="">
        </div>
        <div class="campo-formulario">
            <label for="funcionario_bairro">Bairro</label>
            <input type="text" id="funcionario_bairro" name="funcionario_bairro" class="regular-text" value="">
        </div>
    </div>

    <div class="linha-formulario">
        <div class="campo-formulario">
            <label for="funcionario_telefone">Telefone</label>
            <input type="tel" id="funcionario_telefone" name="funcionario_telefone" class="regular-text" value="">
        </div>
        <div class="campo-formulario">
            <label for="funcionario_email">E-mail</label>
            <input type="email" id="funcionario_email" name="funcionario_email" class="regular-text" value="">
        </div>
    </div>

    <div class="botoes-formulario-funcionarios">
        <button type="submit" name="submit" id="submit" class="botao salvar-funcionario"><span class="icone dashicons dashicons-saved"></span> Salvar funcionario</button>
        <button type="button" class="botao voltar-para-lista" id="botao-voltar-listagem"><span class="icone dashicons dashicons-arrow-left-alt"></span> Voltar</button>
    </div>
</form>