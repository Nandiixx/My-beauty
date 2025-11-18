<?php
/**
 * View: Formulário de cadastro de cliente pela recepcionista
 * Usado por: ClienteController::mostrarFormularioCadastroRecepcionista()
 */

// Recupera erros e dados do formulário da sessão (se houver)
$erros = $_SESSION['erros_cadastro'] ?? [];
$dados_form = $_SESSION['dados_form'] ?? [];
unset($_SESSION['erros_cadastro'], $_SESSION['dados_form']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente - MyBeauty</title>
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
                    <a href="Index.php?acao=funcionario_cadastro_mostrar" class="burger-menu__item">
                        <i data-lucide="user-plus"></i>
                        <span>Cadastrar Profissional</span>
                    </a>
                    <a href="Index.php?acao=cliente_listar" class="burger-menu__item burger-menu__item--active">
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
                <a href="Index.php?acao=cliente_listar" class="btn-header btn-header--primary">
                    <i data-lucide="arrow-left"></i>
                    <span>Voltar à Lista</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($_SESSION['usuario_nome'] ?? 'R', 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $_SESSION['usuario_nome'] ?? 'Recepcionista')[0]); ?></div>
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
                Cadastrar Novo Cliente
            </h1>
            <p>Preencha os dados do cliente. Uma senha temporária será gerada automaticamente.</p>
        </div>

        <!-- Alertas de Erro -->
        <?php if (!empty($erros)): ?>
            <div class="alert alert-error" role="alert">
                <i data-lucide="alert-circle"></i>
                <div>
                    <p><strong>Erro ao cadastrar cliente:</strong></p>
                    <ul>
                        <?php foreach ($erros as $erro): ?>
                            <li><?php echo htmlspecialchars($erro); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulário de Cadastro -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="clipboard-list"></i>
                    Dados do Cliente
                </h2>
            </div>

            <form class="form-grid" method="POST" action="Index.php?acao=recepcionista_cliente_cadastro_salvar" novalidate>
                <div class="input-field">
                    <label for="nome">Nome Completo *</label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true">
                            <i data-lucide="user"></i>
                        </span>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            placeholder="Digite o nome completo do cliente" 
                            required
                            value="<?php echo htmlspecialchars($dados_form['nome'] ?? ''); ?>">
                    </div>
                </div>

                <div class="input-field">
                    <label for="email">E-mail *</label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true">
                            <i data-lucide="mail"></i>
                        </span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Digite o e-mail do cliente" 
                            required
                            value="<?php echo htmlspecialchars($dados_form['email'] ?? ''); ?>">
                    </div>
                </div>

                <div class="input-field">
                    <label for="telefone">Telefone *</label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true">
                            <i data-lucide="phone"></i>
                        </span>
                        <input 
                            type="tel" 
                            id="telefone" 
                            name="telefone" 
                            placeholder="Digite o telefone do cliente" 
                            required
                            value="<?php echo htmlspecialchars($dados_form['telefone'] ?? ''); ?>">
                    </div>
                </div>

                <div class="alert alert-info grid-col-full" role="alert">
                    <i data-lucide="info"></i>
                    <p><strong>Senha temporária:</strong> O cliente receberá a senha padrão <code>mudar123</code> que deve ser alterada no primeiro acesso.</p>
                </div>

                <div class="form-actions grid-col-full">
                    <button type="submit" class="btn-primary">
                        <i data-lucide="check"></i>
                        <span>Cadastrar Cliente</span>
                    </button>
                    <a href="Index.php?acao=cliente_listar" class="btn-secondary">
                        <i data-lucide="x"></i>
                        <span>Cancelar</span>
                    </a>
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
