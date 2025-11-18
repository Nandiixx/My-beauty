<?php
/**
 * View: Dashboard do Cliente
 * Recebe dados processados do AgendamentoController::mostrarDashboardCliente()
 * 
 * Variáveis esperadas:
 * - $dados['usuario_nome']
 * - $dados['total_agendamentos']
 * - $dados['agendamentos']
 * - $dados['agendamentos_proximos']
 * - $dados['proximos_count']
 * - $dados['total_concluidos']
 * - $dados['total_agendados']
 */

// Inclui as funções auxiliares
require_once __DIR__ . '/../../helpers.php';

// Extrai os dados fornecidos pelo Controller
$usuario_nome = $dados['usuario_nome'] ?? 'Cliente';
$total_agendamentos = $dados['total_agendamentos'] ?? 0;
$agendamentos = $dados['agendamentos'] ?? [];
$agendamentos_proximos = $dados['agendamentos_proximos'] ?? [];
$proximos_count = $dados['proximos_count'] ?? 0;
$total_concluidos = $dados['total_concluidos'] ?? 0;
$total_agendados = $dados['total_agendados'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MyBeauty</title>
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
                        <a href="Index.php?acao=cliente_historico_mostrar" class="burger-menu__item">
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
        <!-- Header -->
        <div class="dashboard-header">
            <div class="dashboard-header__greeting">
                <h1>Olá, <?php echo htmlspecialchars($usuario_nome); ?>! <i data-lucide="sparkles" class="inline-icon"></i></h1>
                <p>Bem-vindo ao MyBeauty</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="clock"></i></div>
                <div class="stat-card__value"><?php echo $proximos_count; ?></div>
                <div class="stat-card__label">Próximos Agendamentos</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="check-circle"></i></div>
                <div class="stat-card__value"><?php echo $total_concluidos; ?></div>
                <div class="stat-card__label">Concluídos</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <!-- Agendamentos List -->
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">Meus Agendamentos</h2>
                </div>
                <div class="agendamentos-list">
                    <?php if (empty($agendamentos)): ?>
                        <div class="empty-state">
                            <div class="empty-state__icon"><i data-lucide="calendar"></i></div>
                            <div class="empty-state__text">Você ainda não possui agendamentos</div>
                            <a href="Index.php?acao=agendamento_criar" class="btn-header btn-header--primary">
                                <i data-lucide="calendar-plus"></i>
                                <span>Agendar Agora</span>
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($agendamentos, 0, 5) as $agendamento): 
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
                                            <h3 class="agendamento-item__title">Agendamento #<?php echo htmlspecialchars($agendamento->id); ?></h3>
                                            <div class="agendamento-servicos">
                                                <?php 
                                                // Divide os serviços e cria badges individuais
                                                $servicos_array = explode(', ', $agendamento->servicos ?? 'Serviço');
                                                foreach($servicos_array as $servico_nome): 
                                                ?>
                                                    <span class="servico-badge">
                                                        <i data-lucide="scissors"></i>
                                                        <?php echo htmlspecialchars(trim($servico_nome)); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                            <p class="agendamento-item__details">
                                                <strong>Profissional:</strong> <?php echo htmlspecialchars($agendamento->profissional_nome ?? 'N/A'); ?><br>
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
                        <?php if (count($agendamentos) > 5): ?>
                            <div class="text-center mt-1">
                                <a href="Index.php?acao=agendamento_criar" class="btn-header btn-header--primary display-inline-flex">
                                    Ver Todos os Agendamentos
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">Ações Rápidas</h2>
                </div>
                <div class="quick-actions">
                    <a href="Index.php?acao=agendamento_criar" class="quick-action-card">
                        <div class="quick-action-card__icon"><i data-lucide="calendar-plus"></i></div>
                        <div class="quick-action-card__content">
                            <h3>Novo Agendamento</h3>
                            <p>Agende um novo serviço</p>
                        </div>
                    </a>
                    <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="quick-action-card">
                        <div class="quick-action-card__icon"><i data-lucide="clipboard-list"></i></div>
                        <div class="quick-action-card__content">
                            <h3>Gerenciar Agendamentos</h3>
                            <p>Gerencie os seus agendamentos</p>
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
                        (currentUrl.includes('acao=agendamento_criar') && itemHref.includes('acao=agendamento_criar'))) {
                        item.classList.add('burger-menu__item--active');
                    }
                }
            });
        }

        // Inicializar ícones Lucide após o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            // Estado já está sendo restaurado pela função acima, mas garantir aqui também
            const isPinned = localStorage.getItem('sidebarPinned') === 'true';
            if (isPinned) {
                applyPinnedState();
            }
            // Marcar item ativo
            markActiveMenuItem();
        });

        // Marcar item ativo quando o menu é aberto/fixado
        function updateActiveMenuItem() {
            setTimeout(markActiveMenuItem, 100);
        }

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
            
            // Proteger o burger menu também
            const burgerMenu = document.querySelector('.burger-menu');
            const burgerButton = document.querySelector('.navbar-burger');
            const overlay = document.querySelector('.burger-menu-overlay');
            
            // Se o menu não está fixado E está aberto E o clique foi fora dele
            if (!body.classList.contains('menu-pinned') && 
                burgerMenu && 
                burgerMenu.classList.contains('active') &&
                !burgerMenu.contains(event.target) &&
                burgerButton &&
                !burgerButton.contains(event.target)) {
                // Não fazer nada aqui - deixar o overlay gerenciar
            }
        });
    </script>
</body>
</html>
