<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// A variável $usuario é fornecida pelo UsuarioController::mostrarPerfil()
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Usuário';
$usuario_tipo = $_SESSION['usuario_tipo'] ?? 'CLIENTE';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Função para toggle do menu hambúrguer - precisa estar disponível globalmente
        function toggleBurgerMenu() {
            const burgerMenu = document.querySelector('.burger-menu');
            const burgerButton = document.querySelector('.navbar-burger');
            const overlay = document.querySelector('.burger-menu-overlay');
            const body = document.body;
            
            // Não fazer nada se o menu estiver fixado - proteger completamente
            if (body.classList.contains('menu-pinned')) {
                return false;
            }
            
            if (burgerMenu && burgerButton && overlay) {
                burgerMenu.classList.toggle('active');
                burgerButton.classList.toggle('active');
                overlay.classList.toggle('active');
            }
            return false;
        }

        // Função para fixar/desfixar o menu
        function togglePinMenu() {
            const burgerMenu = document.querySelector('.burger-menu');
            const pinButton = document.querySelector('.burger-menu__pin');
            const pinIcon = pinButton ? pinButton.querySelector('i') : null;
            const body = document.body;
            
            if (burgerMenu && pinButton) {
                const isPinned = body.classList.contains('menu-pinned');
                
                if (isPinned) {
                    // Desfixar o menu
                    body.classList.remove('menu-pinned');
                    burgerMenu.classList.remove('pinned');
                    pinButton.classList.remove('active');
                    burgerMenu.classList.remove('active');
                    
                    // Salvar estado no localStorage
                    localStorage.setItem('sidebarPinned', 'false');
                    
                    // Atualizar ícone para pin
                    if (pinIcon) {
                        pinIcon.setAttribute('data-lucide', 'pin');
                    }
                } else {
                    // Fixar o menu
                    body.classList.add('menu-pinned');
                    burgerMenu.classList.add('pinned', 'active');
                    pinButton.classList.add('active');
                    
                    // Esconder overlay
                    const overlay = document.querySelector('.burger-menu-overlay');
                    if (overlay) {
                        overlay.classList.remove('active');
                    }
                    
                    // Salvar estado no localStorage
                    localStorage.setItem('sidebarPinned', 'true');
                    
                    // Atualizar ícone para pin-off
                    if (pinIcon) {
                        pinIcon.setAttribute('data-lucide', 'pin-off');
                    }
                }
                
                // Reinicializar ícones Lucide
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }

        // Função para restaurar o estado da sidebar ao carregar a página
        function restoreSidebarState() {
            const isPinned = localStorage.getItem('sidebarPinned') === 'true';
            
            if (isPinned) {
                // Tentar aplicar imediatamente
                applyPinnedState();
                
                // Tentar novamente após um pequeno delay caso os elementos ainda não existam
                setTimeout(applyPinnedState, 50);
                setTimeout(applyPinnedState, 100);
                setTimeout(applyPinnedState, 200);
                
                // Proteção contínua: verificar e reforçar o estado periodicamente
                setInterval(function() {
                    const checkPinned = localStorage.getItem('sidebarPinned') === 'true';
                    if (checkPinned && document.body && document.body.classList.contains('menu-pinned')) {
                        applyPinnedState();
                    }
                }, 500);
            }
        }

        // Função auxiliar para aplicar o estado fixado
        function applyPinnedState() {
            const burgerMenu = document.querySelector('.burger-menu');
            const pinButton = document.querySelector('.burger-menu__pin');
            const pinIcon = pinButton ? pinButton.querySelector('i') : null;
            const body = document.body;
            const overlay = document.querySelector('.burger-menu-overlay');
            
            if (body && burgerMenu && pinButton) {
                // Forçar o estado fixado - garantir que nada interfira
                body.classList.add('menu-pinned');
                burgerMenu.classList.add('pinned', 'active');
                pinButton.classList.add('active');
                
                // Garantir que o overlay esteja desativado
                if (overlay) {
                    overlay.classList.remove('active');
                }
                
                // Atualizar ícone para pin-off
                if (pinIcon) {
                    pinIcon.setAttribute('data-lucide', 'pin-off');
                }
                
                // Reinicializar ícones Lucide se disponível
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                
                // Marcar item ativo
                markActiveMenuItem();
            }
        }

        // Função para marcar a aba ativa no menu
        function markActiveMenuItem() {
            const currentUrl = window.location.href;
            const menuItems = document.querySelectorAll('.burger-menu__item');
            
            menuItems.forEach(function(item) {
                item.classList.remove('burger-menu__item--active');
                
                const itemHref = item.getAttribute('href');
                if (itemHref && itemHref !== '#') {
                    // Verifica se a URL atual contém o href do item do menu
                    if (currentUrl.includes(itemHref) || 
                        (currentUrl.includes('acao=inicio') && itemHref.includes('acao=inicio')) ||
                        (currentUrl.includes('acao=perfil_mostrar') && itemHref.includes('acao=perfil_mostrar'))) {
                        item.classList.add('burger-menu__item--active');
                    }
                }
            });
        }

        // Função para calcular e definir a altura do navbar
        function updateNavbarHeight() {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                const navbarHeight = navbar.offsetHeight;
                document.documentElement.style.setProperty('--navbar-height', navbarHeight + 'px');
                
                // Aplicar margin-top ao burger-menu
                const burgerMenu = document.querySelector('.burger-menu');
                if (burgerMenu) {
                    burgerMenu.style.marginTop = navbarHeight + 'px';
                    burgerMenu.style.height = `calc(100vh - ${navbarHeight}px)`;
                }
            }
        }

        // Tentar restaurar o estado imediatamente quando o script carrega
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                updateNavbarHeight();
                restoreSidebarState();
                // Atualizar novamente após um pequeno delay para garantir que está correto
                setTimeout(updateNavbarHeight, 100);
            });
        } else {
            // DOM já carregou, executar imediatamente
            updateNavbarHeight();
            restoreSidebarState();
            setTimeout(updateNavbarHeight, 100);
        }

        // Atualizar altura ao redimensionar a janela
        window.addEventListener('resize', updateNavbarHeight);

        // Função para toggle do menu do usuário - precisa estar disponível globalmente
        function toggleUserMenu() {
            const userMenu = document.querySelector('.navbar-user');
            if (userMenu) {
                userMenu.classList.toggle('active');
            }
        }
        
        // Inicializar ícones quando o Lucide carregar
        window.addEventListener('load', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        
        // Função para mostrar/ocultar o campo de senha
        function toggleAlterarSenha() {
            const senhaFields = document.getElementById('senha-fields');
            const checkbox = document.getElementById('alterar-senha');
            
            if (checkbox.checked) {
                senhaFields.style.display = 'block';
            } else {
                senhaFields.style.display = 'none';
                // Limpar os campos de senha quando desmarcar
                document.getElementById('senha_atual').value = '';
                document.getElementById('senha_nova').value = '';
                document.getElementById('confirma_senha').value = '';
            }
        }
    </script>
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
                    <?php if ($usuario_tipo === 'CLIENTE'): ?>
                    <a href="Index.php?acao=agendamento_criar" class="burger-menu__item">
                        <i data-lucide="calendar-plus"></i>
                        <span>Novo Agendamento</span>
                    </a>
                    <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="burger-menu__item">
                        <i data-lucide="clipboard-list"></i>
                        <span>Gerenciar Agendamentos</span>
                    </a>
                    <?php endif; ?>
                    <a href="Index.php?acao=perfil_mostrar" class="burger-menu__item">
                        <i data-lucide="user"></i>
                        <span>Meu Perfil</span>
                    </a>
                    <a href="#" class="burger-menu__item">
                        <i data-lucide="settings"></i>
                        <span>Configurações</span>
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
                        <div class="navbar-user__role"><?php echo ucfirst(strtolower($usuario_tipo)); ?></div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="Index.php?acao=perfil_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="user"></i>
                            <span>Meu Perfil</span>
                        </a>
                        <a href="#" class="navbar-user__dropdown-item">
                            <i data-lucide="settings"></i>
                            <span>Configurações</span>
                        </a>
                        <?php if ($usuario_tipo === 'CLIENTE'): ?>
                        <a href="Index.php?acao=agendamento_criar" class="navbar-user__dropdown-item">
                            <i data-lucide="calendar-plus"></i>
                            <span>Novo Agendamento</span>
                        </a>
                        <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="clipboard-list"></i>
                            <span>Gerenciar Agendamentos</span>
                        </a>
                        <?php endif; ?>
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
        <!-- Header -->
        <div class="dashboard-header">
            <div class="dashboard-header__greeting">
                <h1>
                    <i data-lucide="user-circle"></i>
                    Meu Perfil
                </h1>
                <p>
                    <i data-lucide="edit"></i>
                    Atualize suas informações pessoais
                </p>
            </div>
        </div>

        <div class="content-grid">
            <!-- Formulário de Perfil -->
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">
                        <i data-lucide="user"></i>
                        Informações Pessoais
                    </h2>
                </div>

                <?php
                if (isset($_SESSION['erros_perfil'])):
                ?>
                    <div class="alert alert-error" role="alert">
                        <i data-lucide="alert-circle"></i>
                        <div>
                            <?php foreach ($_SESSION['erros_perfil'] as $erro): ?>
                                <p><?php echo htmlspecialchars($erro); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['erros_perfil']);
                endif;

                if (isset($_SESSION['sucesso_perfil'])):
                ?>
                    <div class="alert alert-success" role="alert">
                        <i data-lucide="check-circle"></i>
                        <p><?php echo htmlspecialchars($_SESSION['sucesso_perfil']); ?></p>
                    </div>
                <?php
                    unset($_SESSION['sucesso_perfil']);
                endif;
                
                if (isset($_SESSION['erro_perfil'])):
                ?>
                    <div class="alert alert-error" role="alert">
                        <i data-lucide="alert-circle"></i>
                        <p><?php echo htmlspecialchars($_SESSION['erro_perfil']); ?></p>
                    </div>
                <?php
                    unset($_SESSION['erro_perfil']);
                endif;
                ?>

                <form action="Index.php" method="POST">
                    <input type="hidden" name="acao" value="perfil_atualizar">

                    <div class="form-group">
                        <label for="nome">
                            <i data-lucide="user"></i>
                            Nome Completo
                        </label>
                        <input 
                            type="text" 
                            name="nome" 
                            id="nome" 
                            value="<?php echo htmlspecialchars($usuario->getNome()); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i data-lucide="mail"></i>
                            E-mail
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="<?php echo htmlspecialchars($usuario->getEmail()); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="telefone">
                            <i data-lucide="phone"></i>
                            Telefone
                        </label>
                        <input 
                            type="tel" 
                            name="telefone" 
                            id="telefone" 
                            value="<?php echo htmlspecialchars($usuario->getTelefone() ?? ''); ?>" 
                            placeholder="(00) 00000-0000"
                        >
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                id="alterar-senha" 
                                onchange="toggleAlterarSenha()"
                            >
                            <span>
                                <i data-lucide="lock"></i>
                                Desejo alterar minha senha
                            </span>
                        </label>
                    </div>

                    <div id="senha-fields" style="display: none;">
                        <div class="form-group">
                            <label for="senha_atual">
                                <i data-lucide="key"></i>
                                Senha Atual
                            </label>
                            <input 
                                type="password" 
                                name="senha_atual" 
                                id="senha_atual"
                                placeholder="Digite sua senha atual"
                            >
                        </div>

                        <div class="form-group">
                            <label for="senha_nova">
                                <i data-lucide="lock"></i>
                                Nova Senha
                            </label>
                            <input 
                                type="password" 
                                name="senha_nova" 
                                id="senha_nova"
                                placeholder="Digite a nova senha (mínimo 6 caracteres)"
                            >
                        </div>

                        <div class="form-group">
                            <label for="confirma_senha">
                                <i data-lucide="lock"></i>
                                Confirmar Nova Senha
                            </label>
                            <input 
                                type="password" 
                                name="confirma_senha" 
                                id="confirma_senha"
                                placeholder="Confirme a nova senha"
                            >
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="Index.php?acao=inicio" class="btn-secondary">
                            <i data-lucide="x"></i>
                            <span>Cancelar</span>
                        </a>
                        <button type="submit" class="btn-primary">
                            <i data-lucide="save"></i>
                            <span>Salvar Alterações</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informações da Conta -->
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">
                        <i data-lucide="info"></i>
                        Informações da Conta
                    </h2>
                </div>

                <div class="info-list">
                    <div class="info-item">
                        <div class="info-item__label">
                            <i data-lucide="shield"></i>
                            Tipo de Conta
                        </div>
                        <div class="info-item__value">
                            <?php echo ucfirst(strtolower($usuario_tipo)); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item__label">
                            <i data-lucide="hash"></i>
                            ID do Usuário
                        </div>
                        <div class="info-item__value">
                            #<?php echo htmlspecialchars($usuario->getId()); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item__label">
                            <i data-lucide="calendar"></i>
                            Membro desde
                        </div>
                        <div class="info-item__value">
                            <?php echo date('d/m/Y'); ?>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info" role="alert" style="margin-top: 1.5rem;">
                    <i data-lucide="info"></i>
                    <div>
                        <strong>Dica de Segurança:</strong> Recomendamos alterar sua senha regularmente e nunca compartilhá-la com outras pessoas.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicializar ícones Lucide após o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            try {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                } else {
                    // Se não carregou, tenta novamente após um delay
                    setTimeout(function() {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }, 500);
                }
                // Estado já está sendo restaurado pela função acima, mas garantir aqui também
                const isPinned = localStorage.getItem('sidebarPinned') === 'true';
                if (isPinned) {
                    applyPinnedState();
                }
                // Marcar item ativo
                markActiveMenuItem();
            } catch (e) {
                console.error('Erro ao inicializar Lucide:', e);
            }
        });

        // Fechar menu quando clicar fora (mas nunca fechar se estiver fixado)
        document.addEventListener('click', function(event) {
            const body = document.body;
            // Proteger: nunca fechar se o menu estiver fixado
            if (body.classList.contains('menu-pinned')) {
                return;
            }
            
            const userMenu = document.querySelector('.navbar-user');
            if (userMenu && !userMenu.contains(event.target)) {
                userMenu.classList.remove('active');
            }
        });
        
        // Auto-fechar alertas de sucesso após 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>
