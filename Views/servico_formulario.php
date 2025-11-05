<?php
// Arquivo: Views/servico_formulario.php
// (Esta view é usada tanto para cadastrar quanto para editar)
// As variáveis $servico, $acao e $titulo são definidas pelo Controller.
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
    // Inclua seu header de admin aqui
    ?>
    <header>
        <h1>MyBeauty - Painel Administrativo</h1>
        <nav>
             <ul>
                <li><a href="Index.php?acao=inicio_admin">Início Admin</a></li>
                <li><a href="Index.php?acao=servico_listar">Gerenciar Serviços</a></li>
             </ul>
        </nav>
    </header>

    <main style="padding: 20px;">
        <h2><?php echo $titulo; ?></h2>

        <?php
            // Define a action do formulário (para qual rota enviar)
            $action_url = ($acao == 'cadastrar') 
                ? 'Index.php?acao=servico_cadastrar' 
                : 'Index.php?acao=servico_editar&id=' . htmlspecialchars($servico->getId());
        ?>

        <form action="<?php echo $action_url; ?>" method="POST">
            <div class="form-grupo">
                <label for="nome">Nome do Serviço:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($servico->getNome() ?? ''); ?>" required>
            </div>

            <div class="form-grupo">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($servico->getDescricao() ?? ''); ?></textarea>
            </div>

            <div class="form-grupo">
                <label for="duracao_min">Duração (em minutos):</label>
                <input type="number" id="duracao_min" name="duracao_min" value="<?php echo htmlspecialchars($servico->getDuracaoMin() ?? ''); ?>" required>
            </div>

            <div class="form-grupo">
                <label for="preco">Preço (R$):</label>
                <input type="text" id="preco" name="preco" placeholder="Ex: 50.00" value="<?php echo htmlspecialchars($servico->getPreco() ?? ''); ?>" required>
            </div>

            <button type="submit">Salvar</button>
            <a href="Index.php?acao=servico_listar">Cancelar</a>
        </form>
    </main>
</body>
</html>