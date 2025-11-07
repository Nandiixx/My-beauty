<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_cargo'], ['PROPRIETARIO', 'GERENTE_FINANCEIRO'])) {
    header('Location: Index.php?acao=login_mostrar');
    exit;
}

// A variável $servicos é fornecida pelo ServicoController::listar()
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Serviços - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../../../assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="Index.php?acao=inicio" class="navbar-brand">
                <div class="navbar-brand__icon"><i data-lucide="sparkles"></i></div>
                <span>MyBeauty</span>
            </a>
            
            <div class="navbar-actions">
                <a href="Index.php?acao=servico_formulario_cadastrar" class="btn-header btn-header--success">
                    <i data-lucide="plus-circle"></i>
                    <span>Novo Serviço</span>
                </a>
                <a href="Index.php?acao=inicio" class="btn-header btn-header--primary">
                    <i data-lucide="home"></i>
                    <span>Voltar ao Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i data-lucide="sparkles"></i>
                Gerenciar Serviços
            </h1>
            <p>Visualize e gerencie os serviços oferecidos pelo salão</p>
        </div>

        <!-- Alertas -->
        <?php 
        // Mensagens de feedback baseadas no status da URL
        $mensagens = [
            'sucesso' => ['tipo' => 'success', 'icone' => 'check-circle', 'texto' => 'Serviço cadastrado com sucesso!'],
            'sucesso_update' => ['tipo' => 'success', 'icone' => 'check-circle', 'texto' => 'Serviço atualizado com sucesso!'],
            'sucesso_delete' => ['tipo' => 'success', 'icone' => 'check-circle', 'texto' => 'Serviço excluído com sucesso!'],
            'erro' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Ocorreu um erro ao processar a solicitação.'],
            'erro_update' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Erro ao atualizar o serviço.'],
            'erro_delete' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Erro ao excluir o serviço.'],
            'erro_fk' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Este serviço não pode ser excluído pois está vinculado a agendamentos.'],
            'erro_carregar' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Erro ao carregar o serviço.'],
            'erro_id' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'ID de serviço inválido.']
        ];
        
        if (isset($_GET['status']) && isset($mensagens[$_GET['status']])):
            $msg = $mensagens[$_GET['status']];
        ?>
            <div class="alert alert-<?php echo $msg['tipo']; ?>" role="alert">
                <i data-lucide="<?php echo $msg['icone']; ?>"></i>
                <p><?php echo htmlspecialchars($msg['texto']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success" role="alert">
                <i data-lucide="check-circle"></i>
                <p><?php echo htmlspecialchars($_SESSION['sucesso']); ?></p>
            </div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-error" role="alert">
                <i data-lucide="alert-circle"></i>
                <p><?php echo htmlspecialchars($_SESSION['erro']); ?></p>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <!-- Lista de Serviços -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="list"></i>
                    Lista de Serviços
                </h2>
            </div>

            <?php if (isset($servicos) && !empty($servicos)): ?>
                <table class="funcionarios-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Duração</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($servicos as $serv): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($serv->getId()) ?></strong></td>
                                <td><?= htmlspecialchars($serv->getNome()) ?></td>
                                <td><?= htmlspecialchars($serv->getDescricao()) ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <i data-lucide="clock"></i>
                                        <?= htmlspecialchars($serv->getDuracaoMin()) ?> min
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        <i data-lucide="dollar-sign"></i>
                                        R$ <?= htmlspecialchars(number_format($serv->getPreco(), 2, ',', '.')) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="Index.php?acao=servico_formulario_editar&id=<?= $serv->getId() ?>" class="btn btn-edit">
                                            <i data-lucide="edit"></i>
                                            <span>Editar</span>
                                        </a>
                                        <a href="Index.php?acao=servico_excluir&id=<?= $serv->getId() ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este serviço?\n\nAtenção: Esta ação não poderá ser desfeita.');">
                                            <i data-lucide="trash-2"></i>
                                            <span>Excluir</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state__icon">
                        <i data-lucide="package-x"></i>
                    </div>
                    <div class="empty-state__text">Nenhum serviço cadastrado</div>
                    <a href="Index.php?acao=servico_formulario_cadastrar" class="btn-primary" style="margin-top: 1rem;">
                        <i data-lucide="plus-circle"></i>
                        <span>Cadastrar Primeiro Serviço</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Inicializar ícones Lucide após o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Auto-fechar alertas após 5 segundos
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>
