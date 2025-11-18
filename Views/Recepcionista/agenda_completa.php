<?php
/**
 * View: Agenda Completa do Salão (Visão da Recepcionista)
 * Recebe dados processados do AgendamentoController::mostrarAgendaRecepcionista()
 * 
 * Variáveis esperadas:
 * - $dados['lista_agenda_completa'] (array de agendamentos)
 * - $dados['usuario_nome']
 */

// Inclui as funções auxiliares
require_once __DIR__ . '/../../helpers.php';

// Extrai os dados fornecidos pelo Controller
$lista_agenda_completa = $dados['lista_agenda_completa'] ?? [];
$usuario_nome = $dados['usuario_nome'] ?? 'Recepcionista';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Completa do Salão - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="../../assets/js/navbar.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(96, 165, 250, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 20%, rgba(59, 130, 246, 0.08) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            33% { transform: translateY(-20px) translateX(10px); }
            66% { transform: translateY(10px) translateX(-10px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
            width: 100%;
            margin-bottom: 3rem;
            animation: slideDown 0.5s ease;
        }

        .navbar-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 clamp(1rem, 4vw, 2rem);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .navbar-brand__icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(96, 165, 250, 0.2));
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
        }

        .navbar-brand__icon i {
            width: 1.725rem;
            height: 1.725rem;
            color: #60a5fa;
        }

        .navbar-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-header {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-header i {
            width: 1.2rem;
            height: 1.2rem;
        }

        .btn-header--primary {
            background: rgba(59, 130, 246, 0.2);
            backdrop-filter: blur(10px);
            color: #ffffff;
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .btn-header--primary:hover {
            background: rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25);
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 clamp(1rem, 4vw, 2rem) 3rem;
            animation: fadeIn 0.6s ease;
            position: relative;
            z-index: 1;
        }

        .page-header {
            margin-bottom: 3rem;
            text-align: center;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin: 0 0 0.75rem 0;
            color: #ffffff;
            letter-spacing: -0.03em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, rgba(96, 165, 250, 0.9) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-header h1 i {
            width: 3rem;
            height: 3rem;
        }

        .page-header p {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 2.5rem;
            animation: slideUp 0.8s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .section-card__header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .section-card__title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-card__title::before {
            content: '';
            width: 4px;
            height: 1.75rem;
            background: linear-gradient(180deg, #3b82f6, #60a5fa);
            border-radius: 2px;
        }

        .section-card__title i {
            width: 1.75rem;
            height: 1.75rem;
        }

        .agendamentos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .agendamentos-table thead {
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .agendamentos-table th {
            padding: 1rem;
            text-align: left;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .agendamentos-table td {
            padding: 1.25rem 1rem;
            color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .agendamentos-table tbody tr {
            transition: all 0.3s ease;
            animation: slideIn 0.5s ease backwards;
        }

        .agendamentos-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .agendamentos-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .agendamentos-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .agendamentos-table tbody tr:nth-child(4) { animation-delay: 0.4s; }

        .agendamentos-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            border: 2px solid;
        }

        .status-badge i {
            width: 0.85rem;
            height: 0.85rem;
        }

        .status-badge--agendado {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(59, 130, 246, 0.15));
            border-color: rgba(59, 130, 246, 0.4);
            color: #60a5fa;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .status-badge--concluido {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.25), rgba(34, 197, 94, 0.15));
            border-color: rgba(34, 197, 94, 0.4);
            color: #22c55e;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
        }

        .status-badge--cancelado {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.25), rgba(239, 68, 68, 0.15));
            border-color: rgba(239, 68, 68, 0.4);
            color: #ef4444;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .empty-state__icon {
            margin-bottom: 1.5rem;
            color: rgba(59, 130, 246, 0.5);
            animation: pulse 3s infinite;
            display: flex;
            justify-content: center;
        }

        .empty-state__icon i {
            width: 5rem;
            height: 5rem;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .empty-state__text {
            font-size: 1.2rem;
            margin: 0;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .page-header h1 i {
                width: 2rem;
                height: 2rem;
            }

            .agendamentos-table {
                font-size: 0.85rem;
            }

            .agendamentos-table th,
            .agendamentos-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
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
                    <span class="burger-menu__title">Menu Recepcionista</span>
                    <button class="burger-menu__pin" onclick="togglePinMenu()" aria-label="Fixar Menu">
                        <i data-lucide="pin"></i>
                    </button>
                </div>
                <div class="burger-menu__content">
                    <a href="Index.php?acao=inicio" class="burger-menu__item">
                        <i data-lucide="home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="Index.php?acao=agendamento_recepcionista_mostrar" class="burger-menu__item">
                        <i data-lucide="calendar-plus"></i>
                        <span>Agendar Serviço</span>
                    </a>
                    <a href="Index.php?acao=recepcionista_agenda_mostrar" class="burger-menu__item">
                        <i data-lucide="calendar-search"></i>
                        <span>Consultar Agenda</span>
                    </a>
                    <a href="Index.php?acao=funcionario_cadastro_mostrar" class="burger-menu__item">
                        <i data-lucide="user-plus"></i>
                        <span>Cadastrar Profissional</span>
                    </a>
                    <a href="Index.php?acao=cliente_listar" class="burger-menu__item">
                        <i data-lucide="users"></i>
                        <span>Visualizar Clientes</span>
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
                        <div class="navbar-user__role">Recepcionista</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="Index.php?acao=perfil_mostrar" class="navbar-user__dropdown-item">
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

    <div class="dashboard-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i data-lucide="calendar-search"></i>
                Agenda Completa do Salão
            </h1>
            <p>Visualize todos os agendamentos do salão</p>
        </div>

        <!-- Agendamentos -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="clipboard-list"></i>
                    Todos os Agendamentos
                </h2>
            </div>

            <?php if (isset($lista_agenda_completa) && !empty($lista_agenda_completa)): ?>
                <table class="agendamentos-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Profissional</th>
                            <th>Serviços</th>
                            <th>Data e Hora</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_agenda_completa as $a): 
                            $status = statusBadge($a->status);
                        ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($a->id) ?></strong></td>
                                <td><?= htmlspecialchars($a->cliente_nome) ?></td>
                                <td><?= htmlspecialchars($a->profissional_nome) ?></td>
                                <td><?= htmlspecialchars($a->servicos) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($a->data_hora)) ?></td>
                                <td>
                                    <span class="status-badge <?= $status['class'] ?>">
                                        <?php
                                        $iconMap = [
                                            'AGENDADO' => 'clock',
                                            'CONCLUIDO' => 'check-circle',
                                            'CANCELADO' => 'x-circle'
                                        ];
                                        $iconName = $iconMap[$a->status] ?? 'circle';
                                        ?>
                                        <i data-lucide="<?= $iconName ?>"></i>
                                        <?= $status['texto'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state__icon">
                        <i data-lucide="calendar-x"></i>
                    </div>
                    <div class="empty-state__text">Nenhum agendamento encontrado</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Inicializa os ícones Lucide
        lucide.createIcons();
    </script>
</body>
</html>
