<?php
/**
 * View: Meu Histórico de Serviços (Visão do Cliente)
 * Recebe dados processados do AgendamentoController::mostrarHistoricoClienteLogado()
 * 
 * Variáveis esperadas:
 * - $dados['historico'] (array de agendamentos concluídos)
 * - $dados['usuario_nome'] (nome do usuário logado)
 */

// Inclui as funções auxiliares
require_once __DIR__ . '/../../helpers.php';

// Extrai os dados fornecidos pelo Controller
$historico = $dados['historico'] ?? [];
$usuario_nome = $dados['usuario_nome'] ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Histórico de Serviços - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
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
                    if (currentUrl.includes(itemHref)) {
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
                    <a href="Index.php?acao=agendamento_criar" class="burger-menu__item">
                        <i data-lucide="calendar"></i>
                        <span>Agendamentos</span>
                    </a>
                    <a href="Index.php?acao=cliente_historico_mostrar" class="burger-menu__item burger-menu__item--active">
                        <i data-lucide="history"></i>
                        <span>Meu Histórico</span>
                    </a>
                    <a href="Index.php?acao=perfil_mostrar" class="burger-menu__item">
                        <i data-lucide="user"></i>
                        <span>Meu Perfil</span>
                    </a>
                    <div class="burger-menu__divider"></div>
                    <a href="Index.php?acao=logout" class="burger-menu__item">
                        <i data-lucide="log-out"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
            
            <div class="navbar-actions">
                <a href="Index.php?acao=agendamento_criar" class="btn-header btn-header--primary">
                    <i data-lucide="calendar-plus"></i>
                    <span>Novo Agendamento</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($usuario_nome, 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $usuario_nome)[0]); ?></div>
                        <div class="navbar-user__role">Cliente</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="Index.php?acao=perfil_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="user"></i>
                            <span>Meu Perfil</span>
                        </a>
                        <a href="Index.php?acao=agendamento_criar" class="navbar-user__dropdown-item">
                            <i data-lucide="calendar"></i>
                            <span>Meus Agendamentos</span>
                        </a>
                        <a href="Index.php?acao=cliente_historico_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="history"></i>
                            <span>Meu Histórico</span>
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
                <h1>Meu Histórico de Serviços <i data-lucide="history" class="inline-icon"></i></h1>
                <p>Consulte o histórico de serviços que você realizou</p>
            </div>
        </div>

        <!-- Histórico de Serviços -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="clipboard-check"></i>
                    Serviços Concluídos
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
                        Você ainda não tem serviços concluídos
                    </div>
                    <a href="Index.php?acao=agendamento_criar" class="btn-primary mt-1">
                        <i data-lucide="calendar-plus"></i>
                        <span>Agendar Novo Serviço</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
