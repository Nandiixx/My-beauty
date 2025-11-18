<?php
// A variável $dados é fornecida pelo RelatorioController::mostrarRelatorioClientes()
$clientes = $dados['clientes'] ?? [];
$total_clientes = $dados['total_clientes'] ?? 0;
$clientes_ativos = $dados['clientes_ativos'] ?? 0;

// Inclui helpers para formatação
require_once __DIR__ . '/../../../helpers.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Clientes - MyBeauty</title>
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
                    <a href="Index.php?acao=relatorio_clientes_mostrar" class="burger-menu__item burger-menu__item--active">
                        <i data-lucide="bar-chart"></i>
                        <span>Relatório de Clientes</span>
                    </a>
                    <a href="Index.php?acao=despesa_listar" class="burger-menu__item">
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
                <a href="Index.php?acao=inicio" class="btn-header btn-header--primary">
                    <i data-lucide="home"></i>
                    <span>Voltar ao Dashboard</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($_SESSION['usuario_nome'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $_SESSION['usuario_nome'] ?? 'Admin')[0]); ?></div>
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
                <i data-lucide="users"></i>
                Relatório de Clientes
            </h1>
            <p>Estatísticas detalhadas de clientes e agendamentos</p>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid stats-grid-two-cols">
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="users"></i></div>
                <div class="stat-card__value"><?= $total_clientes ?></div>
                <div class="stat-card__label">Total de Clientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="user-check"></i></div>
                <div class="stat-card__value"><?= $clientes_ativos ?></div>
                <div class="stat-card__label">Clientes com Agendamentos Ativos</div>
            </div>
        </div>

        <!-- Alertas -->
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

        <!-- Lista de Clientes com Estatísticas -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="clipboard-list"></i>
                    Detalhamento de Clientes
                </h2>
            </div>

            <?php if (!empty($clientes)): ?>
                <table class="funcionarios-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Agendados</th>
                            <th>Concluídos</th>
                            <th>Cancelados</th>
                            <th>Total</th>
                            <th>Primeiro Agend.</th>
                            <th>Último Agend.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clientes as $cliente): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($cliente->id) ?></strong></td>
                                <td><?= htmlspecialchars($cliente->nome) ?></td>
                                <td><?= htmlspecialchars($cliente->email) ?></td>
                                <td><?= htmlspecialchars($cliente->telefone ?? '-') ?></td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?= htmlspecialchars($cliente->agendamentos_ativos) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        <?= htmlspecialchars($cliente->agendamentos_concluidos) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-danger">
                                        <?= htmlspecialchars($cliente->agendamentos_cancelados) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <strong><?= htmlspecialchars($cliente->total_agendamentos) ?></strong>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($cliente->primeiro_agendamento): ?>
                                        <?php 
                                            $data_formatada = formatarData($cliente->primeiro_agendamento);
                                            echo '<small>' . htmlspecialchars($data_formatada['data_completa']) . '</small>';
                                        ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($cliente->ultimo_agendamento): ?>
                                        <?php 
                                            $data_formatada = formatarData($cliente->ultimo_agendamento);
                                            echo '<small>' . htmlspecialchars($data_formatada['data_completa']) . '</small>';
                                        ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state__icon">
                        <i data-lucide="users-x"></i>
                    </div>
                    <div class="empty-state__text">Nenhum cliente encontrado</div>
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
