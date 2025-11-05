<?php
// Arquivo: Views/servico_listar.php
// (Inclua o head, nav de admin, etc. - similar a inicio_admin.php)
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Serviços</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
    // Recomendo criar um "header_admin.php" e incluir aqui
    // Por enquanto, vou repetir a navegação:
    ?>
    <header>
        <h1>MyBeauty - Painel Administrativo</h1>
        <nav>
             <ul>
                <li>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin'); ?></li>
                <li><a href="Index.php?acao=inicio_admin">Início Admin</a></li>
                <li><a href="Index.php?acao=servico_listar">Gerenciar Serviços</a></li>
                <li><a href="Index.php?acao=funcionario_listar">Gerenciar Profissionais</a></li>
                <li><a href="Index.php?acao=cliente_listar">Gerenciar Clientes</a></li>
                <li><a href="Index.php?acao=logout">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main style="padding: 20px;">
        <h2>Gerenciamento de Serviços</h2>
        
        <a href="Index.php?acao=servico_formulario_cadastrar" class="btn-novo">Novo Serviço</a>

        <?php // Feedback para o usuário
            if(isset($_GET['status'])){
                $status = $_GET['status'];
                if($status == 'sucesso') echo "<p style='color:green;'>Ação realizada com sucesso!</p>";
                if($status == 'sucesso_delete') echo "<p style='color:green;'>Serviço excluído com sucesso!</p>";
                if($status == 'erro') echo "<p style='color:red;'>Ocorreu um erro.</p>";
                if($status == 'erro_fk') echo "<p style'color:red;'>Erro: Este serviço não pode ser excluído pois está vinculado a agendamentos.</p>";
            }
        ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Duração (min)</th>
                    <th>Preço (R$)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($servicos)): ?>
                    <tr>
                        <td colspan="6">Nenhum serviço cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($servicos as $serv): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($serv->getId()); ?></td>
                            <td><?php echo htmlspecialchars($serv->getNome()); ?></td>
                            <td><?php echo htmlspecialchars($serv->getDescricao()); ?></td>
                            <td><?php echo htmlspecialchars($serv->getDuracaoMin()); ?></td>
                            <td>R$ <?php echo htmlspecialchars(number_format($serv->getPreco(), 2, ',', '.')); ?></td>
                            <td class="acoes">
                                <a href="Index.php?acao=servico_formulario_editar&id=<?php echo $serv->getId(); ?>">Editar</a>
                                <a href="Index.php?acao=servico_excluir&id=<?php echo $serv->getId(); ?>" onclick="return confirm('Tem certeza que deseja excluir este serviço?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>