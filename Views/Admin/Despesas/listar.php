<?php
// A variável $despesas é fornecida pelo DespesaController::listar()
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Despesas - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../../../assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="../../../assets/js/navbar.js"></script>
</head>
<body>
    <!-- Burger Menu Overlay -->
    <div class="burger-menu-overlay" onclick="if(!document.body.classList.contains('menu-pinned')) { toggleBurgerMenu(); }"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <button class="navbar-burger" onclick="toggleBurgerMenu()" aria-label="Menu">
                    <i data-lucide="menu"></i>
                </button>
                <a href="Index.php?acao=inicio" class="navbar-brand">
                    <div class="navbar-brand__icon"><i data-lucide="sparkles"></i></div>
                    <span>MyBeauty</span>
                </a>
            </div>
            <div class="burger-menu">
                <div class="burger-menu__header">
                    <span class="burger-menu__title">Menu Admin</span>
                    <button class="burger-menu__pin" onclick="togglePinMenu()" aria-label="Fixar Menu">
                        <i data-lucide="pin"></i>
                    </button>
                </div>
                <div class="burger-menu__content">
                    <a href="Index.php?acao=inicio" class="burger-menu__item">
                        <i data-lucide="home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="burger-menu__item">
                        <i data-lucide="clipboard-list"></i>
                        <span>Gerenciar Agendamentos</span>
                    </a>
                    <a href="Index.php?acao=servico_listar" class="burger-menu__item">
                        <i data-lucide="sparkles"></i>
                        <span>Serviços</span>
                    </a>
                    <a href="Index.php?acao=funcionario_listar" class="burger-menu__item">
                        <i data-lucide="user-check"></i>
                        <span>Profissionais</span>
                    </a>
                    <a href="Index.php?acao=cliente_listar" class="burger-menu__item">
                        <i data-lucide="users"></i>
                        <span>Clientes</span>
                    </a>
                    <a href="Index.php?acao=relatorio_financeiro_mostrar" class="burger-menu__item">
                        <i data-lucide="dollar-sign"></i>
                        <span>Relatório Financeiro</span>
                    </a>
                    <a href="Index.php?acao=relatorio_clientes_mostrar" class="burger-menu__item">
                        <i data-lucide="bar-chart"></i>
                        <span>Relatório de Clientes</span>
                    </a>
                    <a href="Index.php?acao=despesa_listar" class="burger-menu__item burger-menu__item--active">
                        <i data-lucide="trending-down"></i>
                        <span>Gerenciar Despesas</span>
                    </a>
                    <div class="burger-menu__divider"></div>
                    <a href="Index.php?acao=logout" class="burger-menu__item">
                        <i data-lucide="log-out"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
            
            <div class="navbar-actions">
                <a href="Index.php?acao=despesa_formulario_cadastrar" class="btn-header btn-header--success">
                    <i data-lucide="plus-circle"></i>
                    <span>Nova Despesa</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($usuario_nome, 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $usuario_nome)[0]); ?></div>
                        <div class="navbar-user__role">Admin</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="#" class="navbar-user__dropdown-item">
                            <i data-lucide="user"></i>
                            <span>Meu Perfil</span>
                        </a>
                        <div class="navbar-user__dropdown-divider"></div>
                        <a href="Index.php?acao=logout" class="navbar-user__dropdown-item">
                            <i data-lucide="log-out"></i>
                            <span>Sair</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i data-lucide="trending-down"></i>
                Gerenciar Despesas
            </h1>
            <p>Visualize e gerencie as despesas do salão</p>
        </div>

        <!-- Alertas -->
        <?php 
        // Mensagens de feedback baseadas no status da URL
        $mensagens = [
            'sucesso' => ['tipo' => 'success', 'icone' => 'check-circle', 'texto' => 'Despesa cadastrada com sucesso!'],
            'sucesso_update' => ['tipo' => 'success', 'icone' => 'check-circle', 'texto' => 'Despesa atualizada com sucesso!'],
            'sucesso_delete' => ['tipo' => 'success', 'icone' => 'check-circle', 'texto' => 'Despesa excluída com sucesso!'],
            'erro' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Ocorreu um erro ao processar a solicitação.'],
            'erro_update' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Erro ao atualizar a despesa.'],
            'erro_delete' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Erro ao excluir a despesa.'],
            'erro_carregar' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'Erro ao carregar a despesa.'],
            'erro_id' => ['tipo' => 'error', 'icone' => 'alert-circle', 'texto' => 'ID de despesa inválido.']
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

        <!-- Lista de Despesas -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="list"></i>
                    Lista de Despesas
                </h2>
            </div>

            <?php if (isset($despesas) && !empty($despesas)): ?>
                <table class="funcionarios-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Categoria</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($despesas as $desp): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($desp->getId()) ?></strong></td>
                                <td><?= htmlspecialchars($desp->getDescricao()) ?></td>
                                <td>
                                    <span class="badge badge-danger">
                                        <i data-lucide="dollar-sign"></i>
                                        R$ <?= htmlspecialchars(number_format($desp->getValor(), 2, ',', '.')) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <i data-lucide="calendar"></i>
                                        <?= htmlspecialchars(date('d/m/Y', strtotime($desp->getData()))) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($desp->getCategoria()) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="Index.php?acao=despesa_formulario_editar&id=<?= $desp->getId() ?>" class="btn btn-edit">
                                            <i data-lucide="edit"></i>
                                            <span>Editar</span>
                                        </a>
                                        <a href="Index.php?acao=despesa_excluir&id=<?= $desp->getId() ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta despesa?\n\nAtenção: Esta ação não poderá ser desfeita.');">
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
                    <div class="empty-state__text">Nenhuma despesa cadastrada</div>
                    <a href="Index.php?acao=despesa_formulario_cadastrar" class="btn-primary mt-1">
                        <i data-lucide="plus-circle"></i>
                        <span>Cadastrar Primeira Despesa</span>
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
