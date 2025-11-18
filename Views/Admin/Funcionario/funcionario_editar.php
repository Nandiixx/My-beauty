<?php
// A variável $funcionario é fornecida pelo FuncionarioController::mostrarEditar()
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário - MyBeauty</title>
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
                    <a href="Index.php?acao=funcionario_listar" class="burger-menu__item burger-menu__item--active">
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
                <a href="Index.php?acao=funcionario_listar" class="btn-header btn-header--primary">
                    <i data-lucide="arrow-left"></i>
                    <span>Voltar para Lista</span>
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
                <i data-lucide="user-pen"></i>
                Editar Funcionário
            </h1>
            <p>Atualize os dados do funcionário</p>
        </div>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-error" role="alert">
                <i data-lucide="alert-circle"></i>
                <p><?php echo htmlspecialchars($_SESSION['erro']); ?></p>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="user-check"></i>
                    Dados do Funcionário
                </h2>
            </div>

            <form class="form-modern" method="POST" action="../Index.php?acao=funcionario_atualizar&id=<?php echo $funcionario->getId(); ?>" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">
                            <i data-lucide="user"></i>
                            Nome Completo <span class="required">*</span>
                        </label>
                        <input type="text" id="nome" name="nome" placeholder="Nome completo" required 
                               value="<?php echo htmlspecialchars($funcionario->getNome()); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i data-lucide="mail"></i>
                            E-mail <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" placeholder="E-mail" required
                               value="<?php echo htmlspecialchars($funcionario->getEmail()); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefone">
                            <i data-lucide="phone"></i>
                            Telefone
                        </label>
                        <input type="tel" id="telefone" name="telefone" placeholder="Telefone"
                               value="<?php echo htmlspecialchars($funcionario->getTelefone() ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="matricula">
                            <i data-lucide="badge-check"></i>
                            Matrícula <span class="required">*</span>
                        </label>
                        <input type="text" id="matricula" name="matricula" placeholder="Matrícula" required
                               value="<?php echo htmlspecialchars($funcionario->getMatricula() ?? ''); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cargo">
                            <i data-lucide="briefcase"></i>
                            Cargo <span class="required">*</span>
                        </label>
                        <select name="cargo" id="cargo" required>
                            <option value="">Selecione um cargo</option>
                            <option value="PROFISSIONAL_BELEZA" <?php echo ($funcionario->getCargo() === 'PROFISSIONAL_BELEZA') ? 'selected' : ''; ?>>
                                Profissional de Beleza
                            </option>
                            <option value="RECEPCIONISTA" <?php echo ($funcionario->getCargo() === 'RECEPCIONISTA') ? 'selected' : ''; ?>>
                                Recepcionista
                            </option>
                            <option value="PROPRIETARIO" <?php echo ($funcionario->getCargo() === 'PROPRIETARIO') ? 'selected' : ''; ?>>
                                Proprietário
                            </option>
                            <option value="GERENTE_FINANCEIRO" <?php echo ($funcionario->getCargo() === 'GERENTE_FINANCEIRO') ? 'selected' : ''; ?>>
                                Gerente Financeiro
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="especialidade">
                            <i data-lucide="star"></i>
                            Especialidade
                        </label>
                        <input type="text" id="especialidade" name="especialidade" placeholder="Especialidade (opcional)"
                               value="<?php echo htmlspecialchars($funcionario->getEspecialidade() ?? ''); ?>">
                        <small class="form-hint">Campo opcional</small>
                    </div>
                </div>

                <div class="form-section-divider">
                    <label class="form-section-label">
                        <i data-lucide="key"></i>
                        Alterar Senha (deixe em branco para manter a atual)
                    </label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="senha">
                            <i data-lucide="lock"></i>
                            Nova Senha
                        </label>
                        <input type="password" id="senha" name="senha" placeholder="Nova senha (opcional)" minlength="6">
                        <small class="form-hint">Mínimo de 6 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="confirma_senha">
                            <i data-lucide="lock-keyhole"></i>
                            Confirmar Nova Senha
                        </label>
                        <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Confirmar nova senha" minlength="6">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="Index.php?acao=funcionario_listar" class="btn-secondary">
                        <i data-lucide="x"></i>
                        <span>Cancelar</span>
                    </a>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save"></i>
                        <span>Atualizar Funcionário</span>
                    </button>
                </div>
            </form>
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
