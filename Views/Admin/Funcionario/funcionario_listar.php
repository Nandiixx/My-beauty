<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'ADMIN') {
    header('Location: Index.php?acao=login_mostrar');
    exit;
}

// A variável $lista_funcionarios é fornecida pelo FuncionarioController::listar()
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Admin';

function cargoTexto($cargo) {
    $cargos = [
        'PROFISSIONAL_BELEZA' => 'Profissional de Beleza',
        'RECEPCIONISTA' => 'Recepcionista',
        'PROPRIETARIO' => 'Proprietário',
        'GERENTE_FINANCEIRO' => 'Gerente Financeiro'
    ];
    return $cargos[$cargo] ?? $cargo;
}

function cargoBadge($cargo) {
    $badges = [
        'PROFISSIONAL_BELEZA' => 'badge-primary',
        'RECEPCIONISTA' => 'badge-info',
        'PROPRIETARIO' => 'badge-warning',
        'GERENTE_FINANCEIRO' => 'badge-success'
    ];
    return $badges[$cargo] ?? 'badge-secondary';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Funcionários - MyBeauty</title>
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
                <a href="Index.php?acao=funcionario_cadastro_mostrar" class="btn-header btn-header--success">
                    <i data-lucide="user-plus"></i>
                    <span>Novo Funcionário</span>
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
                <i data-lucide="users"></i>
                Gerenciar Funcionários
            </h1>
            <p>Visualize e gerencie a equipe</p>
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

        <!-- Lista de Funcionários -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="clipboard-list"></i>
                    Lista de Funcionários
                </h2>
            </div>

            <?php if (isset($lista_funcionarios) && !empty($lista_funcionarios)): ?>
                <table class="funcionarios-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Cargo</th>
                            <th>Especialidade</th>
                            <th>Matrícula</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_funcionarios as $f): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($f->id) ?></strong></td>
                                <td><?= htmlspecialchars($f->nome) ?></td>
                                <td><?= htmlspecialchars($f->email) ?></td>
                                <td><?= htmlspecialchars($f->telefone ?? '-') ?></td>
                                <td>
                                    <span class="badge <?= cargoBadge($f->cargo) ?>">
                                        <i data-lucide="briefcase"></i>
                                        <?= cargoTexto($f->cargo) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($f->especialidade ?? '-') ?></td>
                                <td><?= htmlspecialchars($f->matricula ?? '-') ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="Index.php?acao=funcionario_editar_mostrar&id=<?= $f->id ?>" class="btn btn-edit">
                                            <i data-lucide="edit"></i>
                                            <span>Editar</span>
                                        </a>
                                        <a href="Index.php?acao=funcionario_excluir&id=<?= $f->id ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">
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
                        <i data-lucide="users-x"></i>
                    </div>
                    <div class="empty-state__text">Nenhum funcionário cadastrado</div>
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
