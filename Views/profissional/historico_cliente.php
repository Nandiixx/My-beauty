<?php
/**
 * View: Histórico de Serviços de Cliente
 * Recebe dados processados do AgendamentoController::mostrarHistoricoCliente()
 * 
 * Variáveis esperadas:
 * - $dados['lista_clientes'] (array de clientes)
 * - $dados['cliente_selecionado'] (ID do cliente selecionado)
 * - $dados['cliente_nome'] (nome do cliente selecionado)
 * - $dados['historico'] (array de agendamentos concluídos)
 */

// Inclui as funções auxiliares
require_once __DIR__ . '/../../helpers.php';

// Extrai os dados fornecidos pelo Controller
$lista_clientes = $dados['lista_clientes'] ?? [];
$cliente_selecionado = $dados['cliente_selecionado'] ?? null;
$cliente_nome = $dados['cliente_nome'] ?? '';
$historico = $dados['historico'] ?? [];

// Obtém o nome do usuário da sessão
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Profissional';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Cliente - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="../../assets/js/navbar.js"></script>
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
                    <span class="burger-menu__title">Menu</span>
                    <button class="burger-menu__pin" onclick="togglePinMenu()" aria-label="Fixar Menu">
                        <i data-lucide="pin"></i>
                    </button>
                </div>
                <div class="burger-menu__content">
                    <a href="Index.php?acao=inicio" class="burger-menu__item">
                        <i data-lucide="home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="Index.php?acao=agenda_profissional_mostrar" class="burger-menu__item">
                        <i data-lucide="calendar"></i>
                        <span>Minha Agenda</span>
                    </a>
                    <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="burger-menu__item">
                        <i data-lucide="clipboard-list"></i>
                        <span>Gerenciar Agendamentos</span>
                    </a>
                    <a href="Index.php?acao=profissional_historico_cliente" class="burger-menu__item burger-menu__item--active">
                        <i data-lucide="history"></i>
                        <span>Histórico de Clientes</span>
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
                    <span>Dashboard</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($usuario_nome, 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $usuario_nome)[0]); ?></div>
                        <div class="navbar-user__role">Profissional</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="#" class="navbar-user__dropdown-item">
                            <i data-lucide="user"></i>
                            <span>Meu Perfil</span>
                        </a>
                        <a href="Index.php?acao=agenda_profissional_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="calendar"></i>
                            <span>Minha Agenda</span>
                        </a>
                        <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="clipboard-list"></i>
                            <span>Gerenciar Agendamentos</span>
                        </a>
                        <a href="Index.php?acao=profissional_historico_cliente" class="navbar-user__dropdown-item">
                            <i data-lucide="history"></i>
                            <span>Histórico de Clientes</span>
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

    <div class="dashboard-container">
        <!-- Page Header -->
        <div class="dashboard-header">
            <div class="dashboard-header__greeting">
                <h1>Histórico de Serviços <i data-lucide="history" class="inline-icon"></i></h1>
                <p>Consulte o histórico de serviços realizados para clientes</p>
            </div>
        </div>

        <!-- Formulário de Seleção de Cliente -->
        <div class="section-card mb-2">
            <div class="section-card__header">
                <h2 class="section-card__title">Selecionar Cliente</h2>
            </div>
            <form method="GET" action="Index.php" class="form-horizontal">
                <input type="hidden" name="acao" value="profissional_historico_cliente">
                <div class="form-group">
                    <label for="cliente_id" class="form-label">
                        <i data-lucide="user"></i>
                        Cliente
                    </label>
                    <select name="cliente_id" id="cliente_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">Selecione um cliente...</option>
                        <?php foreach ($lista_clientes as $cliente): ?>
                            <option value="<?php echo $cliente->id; ?>" <?php echo ($cliente_selecionado == $cliente->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cliente->nome); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <!-- Histórico de Serviços -->
        <?php if ($cliente_selecionado): ?>
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">
                        <i data-lucide="clipboard-check"></i>
                        Histórico de <?php echo htmlspecialchars($cliente_nome); ?>
                    </h2>
                </div>

                <?php if (!empty($historico)): ?>
                    <div class="table-responsive">
                        <table class="agendamentos-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data do Serviço</th>
                                    <th>Serviços Realizados</th>
                                    <th>Profissional</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historico as $agendamento): 
                                    $data = formatarData($agendamento->data_hora);
                                    $status = statusBadge($agendamento->status);
                                ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($agendamento->id); ?></strong></td>
                                        <td>
                                            <div class="date-info">
                                                <div class="date-info__main"><?php echo $data['data_completa']; ?></div>
                                                <div class="date-info__time"><?php echo $data['hora']; ?></div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($agendamento->servicos); ?></td>
                                        <td><?php echo htmlspecialchars($agendamento->profissional_nome); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $status['class']; ?>">
                                                <i data-lucide="check-circle"></i>
                                                <?php echo $status['texto']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="section-card__footer">
                        <p class="text-muted">
                            <i data-lucide="info"></i>
                            Total de serviços concluídos: <strong><?php echo count($historico); ?></strong>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state__icon">
                            <i data-lucide="clipboard-x"></i>
                        </div>
                        <div class="empty-state__text">
                            Nenhum serviço concluído encontrado para este cliente
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
