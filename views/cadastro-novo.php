<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

?>

<div class="wrap">
    <h1>Adicionar Novo Advogado</h1>

    <div class="container-novo-advogado-cadastro">
        <form id="form-novo-advogado" method="post">
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
    </div>
</div>