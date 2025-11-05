<?php
// Verifica se o usuário está logado e é profissional
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'PROFISSIONAL') {
    header('Location: Index.php?acao=login_mostrar');
    exit;
}

// Busca agendamentos do profissional
require_once __DIR__ . '/../Models/Agendamento.php';
require_once __DIR__ . '/../Models/ConexaoDB.php';

$agendamentoModel = new Agendamento();
$funcionario_id = $_SESSION['funcionario_id'] ?? null;
$agendamentos = [];

if ($funcionario_id) {
    $agendamentos = $agendamentoModel->listarAgendaPorProfissional($funcionario_id);
}

$usuario_nome = $_SESSION['usuario_nome'] ?? 'Profissional';
$total_agendamentos = count($agendamentos);

// Filtros
$hoje = new DateTime();
$hoje->setTime(0, 0, 0);
$agendamentos_hoje = array_filter($agendamentos, function($ag) use ($hoje) {
    $data = new DateTime($ag->data_hora);
    $data->setTime(0, 0, 0);
    return $data == $hoje && $ag->status === 'AGENDADO';
});

$agendamentos_proximos = array_filter($agendamentos, function($ag) {
    $data = new DateTime($ag->data_hora);
    return $data > new DateTime() && $ag->status === 'AGENDADO';
});

function formatarData($data_hora) {
    $dt = new DateTime($data_hora);
    return [
        'dia' => $dt->format('d'),
        'mes' => $dt->format('M'),
        'hora' => $dt->format('H:i'),
        'data_completa' => $dt->format('d/m/Y'),
        'dia_semana' => ucfirst($dt->format('l'))
    ];
}

function statusBadge($status) {
    $classes = [
        'AGENDADO' => 'status-badge--agendado',
        'CONCLUIDO' => 'status-badge--concluido',
        'CANCELADO' => 'status-badge--cancelado'
    ];
    $textos = [
        'AGENDADO' => 'Agendado',
        'CONCLUIDO' => 'Concluído',
        'CANCELADO' => 'Cancelado'
    ];
    return [
        'class' => $classes[$status] ?? '',
        'texto' => $textos[$status] ?? $status
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profissional - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeIn 0.6s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-header__greeting h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            color: #ffffff;
            text-shadow: 0 2px 12px rgba(255, 255, 255, 0.4), 0 4px 20px rgba(0, 0, 0, 0.2);
            letter-spacing: -0.02em;
            animation: fadeIn 0.8s ease;
        }

        .dashboard-header__greeting p {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-weight: 500;
            letter-spacing: 0.02em;
            animation: fadeIn 0.8s ease 0.2s backwards;
        }

        .dashboard-header__actions {
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
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            color: #ffffff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 16px rgba(18, 22, 55, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .btn-header--primary:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 32px rgba(18, 22, 55, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .btn-header--primary:active {
            transform: translateY(-1px) scale(0.98);
        }

        .btn-header--secondary {
            background: linear-gradient(135deg, #ffffff, #f8f9ff);
            color: #121637;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .btn-header--secondary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 1);
            background: linear-gradient(135deg, #ffffff, #f0f2ff);
        }

        .btn-header--secondary:active {
            transform: translateY(-1px) scale(0.98);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 24px;
            padding: 2rem;
            color: #ffffff;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: slideUp 0.6s ease backwards;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(18, 22, 55, 0.4);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.4));
            opacity: 0.8;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-card:hover {
            transform: translateY(-12px) scale(1.02);
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.35);
            box-shadow: 0 16px 40px rgba(18, 22, 55, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .stat-card:hover::before {
            opacity: 1;
            animation: shimmer 1.5s infinite;
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .stat-card__icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.95);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card__icon i {
            width: 2.5rem;
            height: 2.5rem;
        }

        .stat-card:hover .stat-card__icon {
            transform: scale(1.1) rotate(5deg);
            filter: drop-shadow(0 6px 12px rgba(255, 255, 255, 0.3));
        }

        .stat-card__value {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0.5rem 0;
            color: #ffffff;
            text-shadow: 0 2px 12px rgba(255, 255, 255, 0.4);
            letter-spacing: -0.02em;
        }

        .stat-card__label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 24px;
            padding: 2rem;
            animation: fadeIn 0.8s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(18, 22, 55, 0.4);
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.4));
            opacity: 0.8;
        }

        .section-card__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-card__title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            text-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
            letter-spacing: -0.01em;
        }

        .agendamentos-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .agendamento-item {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: slideIn 0.5s ease backwards;
            position: relative;
            overflow: hidden;
        }

        .agendamento-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .agendamento-item:nth-child(1) { animation-delay: 0.1s; }
        .agendamento-item:nth-child(2) { animation-delay: 0.2s; }
        .agendamento-item:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .agendamento-item:hover {
            background: rgba(255, 255, 255, 0.16);
            transform: translateX(12px) scale(1.01);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 12px 28px rgba(18, 22, 55, 0.5);
        }

        .agendamento-item:hover::before {
            opacity: 1;
        }

        .agendamento-item__header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .agendamento-item__date {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .date-badge {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            padding: 0.75rem 1rem;
            text-align: center;
            min-width: 80px;
            box-shadow: 0 4px 12px rgba(18, 22, 55, 0.3);
            transition: all 0.3s ease;
        }

        .agendamento-item:hover .date-badge {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 6px 16px rgba(18, 22, 55, 0.4);
        }

        .date-badge__day {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .date-badge__month {
            font-size: 0.75rem;
            color: #fff;
            opacity: 0.9;
            text-transform: uppercase;
            margin-top: 0.25rem;
        }

        .agendamento-item__info {
            flex: 1;
            margin-left: 1rem;
        }

        .agendamento-item__title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 0.35rem 0;
            letter-spacing: -0.01em;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        .agendamento-item__details {
            font-size: 0.92rem;
            color: rgba(255, 255, 255, 0.95);
            margin: 0.3rem 0;
            line-height: 1.6;
            font-weight: 400;
        }

        .agendamento-item__details strong {
            font-weight: 600;
            color: #ffffff;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge--agendado {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.25), rgba(34, 197, 94, 0.15));
            color: #22c55e;
            border: 2px solid rgba(34, 197, 94, 0.4);
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
        }

        .status-badge--concluido {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(59, 130, 246, 0.15));
            color: #3b82f6;
            border: 2px solid rgba(59, 130, 246, 0.4);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .status-badge--cancelado {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.25), rgba(239, 68, 68, 0.15));
            color: #ef4444;
            border: 2px solid rgba(239, 68, 68, 0.4);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #fff;
            opacity: 0.8;
        }

        .empty-state__icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state__icon i {
            width: 4rem;
            height: 4rem;
        }

        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .quick-action-card {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .quick-action-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .quick-action-card:hover {
            background: rgba(255, 255, 255, 0.16);
            transform: translateX(12px) scale(1.02);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 24px rgba(18, 22, 55, 0.5);
        }

        .quick-action-card:hover::before {
            opacity: 1;
        }

        .quick-action-card__icon {
            font-size: 2rem;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(18, 22, 55, 0.3);
        }

        .quick-action-card__icon i {
            width: 1.5rem;
            height: 1.5rem;
        }

        .quick-action-card:hover .quick-action-card__icon {
            transform: scale(1.1) rotate(5deg);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 6px 16px rgba(18, 22, 55, 0.4);
        }

        .quick-action-card__content h3 {
            margin: 0 0 0.25rem 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Efeito de partículas sutis no fundo */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .dashboard-container {
            position: relative;
            z-index: 1;
        }

        /* Melhorar animação de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-header {
            animation: fadeInUp 0.6s ease backwards;
        }

        .stats-grid {
            animation: fadeInUp 0.6s ease 0.2s backwards;
        }

        /* Adicionar pulse sutil nos ícones */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .stat-card:hover .stat-card__icon {
            animation: pulse 2s infinite;
        }

        .empty-state__icon {
            animation: pulse 3s infinite;
        }

        @media (max-width: 768px) {
            .dashboard-header__greeting h1 {
                font-size: 2rem;
            }
            .dashboard-header__greeting p {
                font-size: 1rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .stat-card__value {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-header__greeting">
                <h1>Olá, <?php echo htmlspecialchars($usuario_nome); ?>! <i data-lucide="sparkles" style="width: 2rem; height: 2rem; display: inline-block; vertical-align: middle;"></i></h1>
                <p>Painel Profissional MyBeauty</p>
            </div>
            <div class="dashboard-header__actions">
                <a href="Index.php?acao=agenda_profissional_mostrar" class="btn-header btn-header--primary">
                    <i data-lucide="calendar"></i>
                    <span>Minha Agenda</span>
                </a>
                <a href="Index.php?acao=logout" class="btn-header btn-header--secondary">
                    <i data-lucide="log-out"></i>
                    <span>Sair</span>
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="clipboard-list"></i></div>
                <div class="stat-card__value"><?php echo $total_agendamentos; ?></div>
                <div class="stat-card__label">Total de Agendamentos</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="clock"></i></div>
                <div class="stat-card__value"><?php echo count($agendamentos_hoje); ?></div>
                <div class="stat-card__label">Agendamentos Hoje</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="check-circle"></i></div>
                <div class="stat-card__value"><?php echo count(array_filter($agendamentos, fn($a) => $a->status === 'CONCLUIDO')); ?></div>
                <div class="stat-card__label">Concluídos</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="calendar-clock"></i></div>
                <div class="stat-card__value"><?php echo count($agendamentos_proximos); ?></div>
                <div class="stat-card__label">Próximos</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">Próximos Agendamentos</h2>
                </div>
                <div class="agendamentos-list">
                    <?php if (empty($agendamentos_proximos)): ?>
                        <div class="empty-state">
                            <div class="empty-state__icon"><i data-lucide="calendar-x"></i></div>
                            <div class="empty-state__text">Nenhum agendamento próximo</div>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($agendamentos_proximos, 0, 5) as $agendamento): 
                            $data = formatarData($agendamento->data_hora);
                            $status = statusBadge($agendamento->status);
                        ?>
                            <div class="agendamento-item">
                                <div class="agendamento-item__header">
                                    <div class="agendamento-item__date">
                                        <div class="date-badge">
                                            <div class="date-badge__day"><?php echo $data['dia']; ?></div>
                                            <div class="date-badge__month"><?php echo $data['mes']; ?></div>
                                        </div>
                                        <div class="agendamento-item__info">
                                            <h3 class="agendamento-item__title"><?php echo htmlspecialchars($agendamento->servicos ?? 'Serviço'); ?></h3>
                                            <p class="agendamento-item__details">
                                                <strong>Cliente:</strong> <?php echo htmlspecialchars($agendamento->cliente_nome ?? 'N/A'); ?><br>
                                                <strong>Horário:</strong> <?php echo $data['hora']; ?> • <?php echo $data['data_completa']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <span class="status-badge <?php echo $status['class']; ?>">
                                        <?php echo $status['texto']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">Ações Rápidas</h2>
                </div>
                <div class="quick-actions">
                    <a href="Index.php?acao=agenda_profissional_mostrar" class="quick-action-card">
                        <div class="quick-action-card__icon"><i data-lucide="calendar"></i></div>
                        <div class="quick-action-card__content">
                            <h3>Minha Agenda</h3>
                            <p>Visualizar agenda completa</p>
                        </div>
                    </a>
                    <a href="Index.php?acao=inicio" class="quick-action-card">
                        <div class="quick-action-card__icon"><i data-lucide="home"></i></div>
                        <div class="quick-action-card__content">
                            <h3>Dashboard</h3>
                            <p>Voltar ao início</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Inicializar ícones Lucide após o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
