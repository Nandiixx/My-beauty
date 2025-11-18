<?php
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
                    <a href="Index.php?acao=servico_listar" class="burger-menu__item burger-menu__item--active">
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
                <a href="Index.php?acao=servico_formulario_cadastrar" class="btn-header btn-header--success">
                    <i data-lucide="plus-circle"></i>
                    <span>Novo Serviço</span>
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
                    <a href="Index.php?acao=servico_formulario_cadastrar" class="btn-primary mt-1">
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
