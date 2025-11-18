<?php
// A variável $erro é definida pelo FuncionarioController caso a validação falhe.
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Recepcionista';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Profissional - MyBeauty</title>
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
                    <a href="Index.php?acao=funcionario_cadastro_mostrar" class="burger-menu__item burger-menu__item--active">
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
                        <div class="navbar-user__role">Recepcionista</div>
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
                <i data-lucide="user-plus"></i>
                Cadastrar Profissional
            </h1>
            <p>Adicione um novo membro à equipe</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-error" role="alert">
                <i data-lucide="alert-circle"></i>
                <p><?php echo htmlspecialchars($erro); ?></p>
            </div>
        <?php endif; ?>

        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="user-check"></i>
                    Dados do Profissional
                </h2>
            </div>

            <form class="form-modern" method="POST" action="../Index.php?acao=funcionario_salvar" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">
                            <i data-lucide="user"></i>
                            Nome Completo <span class="required">*</span>
                        </label>
                        <input type="text" id="nome" name="nome" placeholder="Nome completo" required 
                               value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i data-lucide="mail"></i>
                            E-mail <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" placeholder="E-mail" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="senha">
                            <i data-lucide="lock"></i>
                            Senha <span class="required">*</span>
                        </label>
                        <input type="password" id="senha" name="senha" placeholder="Senha" required minlength="6">
                        <small class="form-hint">Mínimo de 6 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="confirma_senha">
                            <i data-lucide="lock-keyhole"></i>
                            Confirmar Senha <span class="required">*</span>
                        </label>
                        <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Confirmar senha" required minlength="6">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="matricula">
                            <i data-lucide="badge-check"></i>
                            Matrícula <span class="required">*</span>
                        </label>
                        <input type="text" id="matricula" name="matricula" placeholder="Matrícula" required
                               value="<?php echo isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="cargo">
                            <i data-lucide="briefcase"></i>
                            Cargo <span class="required">*</span>
                        </label>
                        <select name="cargo" id="cargo" required>
                            <option value="">Selecione um cargo</option>
                            <option value="PROFISSIONAL_BELEZA" <?php echo (isset($_POST['cargo']) && $_POST['cargo'] === 'PROFISSIONAL_BELEZA') ? 'selected' : ''; ?>>
                                Profissional de Beleza
                            </option>
                            <option value="RECEPCIONISTA" <?php echo (isset($_POST['cargo']) && $_POST['cargo'] === 'RECEPCIONISTA') ? 'selected' : ''; ?>>
                                Recepcionista
                            </option>
                            <option value="PROPRIETARIO" <?php echo (isset($_POST['cargo']) && $_POST['cargo'] === 'PROPRIETARIO') ? 'selected' : ''; ?>>
                                Proprietário
                            </option>
                            <option value="GERENTE_FINANCEIRO" <?php echo (isset($_POST['cargo']) && $_POST['cargo'] === 'GERENTE_FINANCEIRO') ? 'selected' : ''; ?>>
                                Gerente Financeiro
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group form-group--full">
                        <label for="especialidade">
                            <i data-lucide="star"></i>
                            Especialidade
                        </label>
                        <input type="text" id="especialidade" name="especialidade" placeholder="Especialidade (opcional)"
                               value="<?php echo isset($_POST['especialidade']) ? htmlspecialchars($_POST['especialidade']) : ''; ?>">
                        <small class="form-hint">Campo opcional</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="Index.php?acao=funcionario_listar" class="btn-secondary">
                        <i data-lucide="x"></i>
                        <span>Cancelar</span>
                    </a>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="user-plus"></i>
                        <span>Cadastrar Profissional</span>
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
