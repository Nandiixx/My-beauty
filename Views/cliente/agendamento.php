<?php
/**
 * View: Página de Agendamento
 * Recebe dados processados do AgendamentoController::index()
 * 
 * Variáveis esperadas:
 * - $lista_profissionais (array de profissionais)
 * - $lista_servicos (array de serviços)
 * - $lista_agendamentos (array de agendamentos do cliente)
 */

// Inclui as funções auxiliares
require_once __DIR__ . '/../../helpers.php';

// Obtém o nome do usuário da sessão (já validada pelo Controller)
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - MyBeauty</title>
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
                        (currentUrl.includes('acao=agendamento_criar') && itemHref.includes('acao=agendamento_criar')) ||
                        (currentUrl.includes('acao=gerenciar_agendamento_mostrar') && itemHref.includes('acao=gerenciar_agendamento_mostrar'))) {
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
                        <i data-lucide="calendar-plus"></i>
                        <span>Novo Agendamento</span>
                    </a>
                    <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="burger-menu__item">
                        <i data-lucide="clipboard-list"></i>
                        <span>Gerenciar Agendamentos</span>
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
                        <div class="navbar-user__role">Cliente</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="Index.php?acao=perfil_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="user"></i>
                            <span>Meu Perfil</span>
                        </a>
                        <a href="Index.php?acao=agendamento_criar" class="navbar-user__dropdown-item">
                            <i data-lucide="calendar-plus"></i>
                            <span>Novo Agendamento</span>
                        </a>
                        <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="clipboard-list"></i>
                            <span>Gerenciar Agendamentos</span>
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
                <h1>
                    <i data-lucide="calendar-plus"></i>
                    Novo Agendamento
                </h1>
                <p>
                    <i data-lucide="sparkles"></i>
                    Agende um novo serviço com nossos profissionais
                </p>
            </div>
        </div>

        <div class="content-grid">
            <!-- Formulário -->
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">
                        <i data-lucide="file-edit"></i>
                        Preencha os Dados
                    </h2>
                </div>

                <?php
                if (isset($_SESSION['erros_agendamento'])):
                ?>
                    <div class="alert alert-error" role="alert">
                        <i data-lucide="alert-circle"></i>
                        <div>
                            <?php foreach ($_SESSION['erros_agendamento'] as $erro): ?>
                                <p><?php echo htmlspecialchars($erro); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['erros_agendamento']);
                endif;

                if (isset($_SESSION['sucesso_agendamento'])):
                ?>
                    <div class="alert alert-success" role="alert">
                        <i data-lucide="check-circle"></i>
                        <p><?php echo htmlspecialchars($_SESSION['sucesso_agendamento']); ?></p>
                    </div>
                <?php
                    unset($_SESSION['sucesso_agendamento']);
                endif;
                ?>

                <form action="Index.php?acao=agendamento_salvar" method="POST">

                    <div class="form-group">
                        <label for="profissional_id">
                            <i data-lucide="user"></i>
                            Profissional
                        </label>
                        <select name="profissional_id" id="profissional_id" required>
                            <option value="">Selecione um profissional</option>
                            <?php 
                            if (isset($lista_profissionais) && !empty($lista_profissionais)):
                                foreach($lista_profissionais as $prof):
                            ?>
                                <option value="<?php echo htmlspecialchars($prof->id); ?>">
                                    <?php echo htmlspecialchars($prof->nome); ?>
                                </option>
                            <?php 
                                endforeach;
                            else:
                            ?>
                                <option value="">Nenhum profissional disponível</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="servico_id">
                            <i data-lucide="scissors"></i>
                            Serviço
                        </label>
                        <select name="servico_id" id="servico_id" required>
                            <option value="">Selecione um serviço</option>
                            <?php 
                            if (isset($lista_servicos) && !empty($lista_servicos)):
                                foreach($lista_servicos as $s):
                            ?>
                                <option value="<?php echo htmlspecialchars($s->getId()); ?>" 
                                        data-duracao="<?php echo htmlspecialchars($s->getDuracaoMin()); ?>">
                                    <?php echo htmlspecialchars($s->getNome()); ?> - R$ <?php echo number_format($s->getPreco(), 2, ',', '.'); ?> (<?php echo $s->getDuracaoMin(); ?> min)
                                </option>
                            <?php 
                                endforeach;
                            else:
                            ?>
                                <option value="">Nenhum serviço disponível</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dataHora">
                            <i data-lucide="clock"></i>
                            Data e Hora
                        </label>
                        <input type="datetime-local" name="dataHora" id="dataHora" required>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i data-lucide="check-circle"></i>
                        <span>Confirmar Agendamento</span>
                    </button>
                </form>
            </div>

            <!-- Lista de Agendamentos -->
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">
                        <i data-lucide="calendar-check"></i>
                        Meus Agendamentos
                    </h2>
                </div>

                <div class="agendamentos-list">
                    <?php 
                    if (isset($lista_agendamentos) && !empty($lista_agendamentos)):
                        foreach($lista_agendamentos as $a): 
                            $data = formatarData($a->data_hora);
                            $status = statusBadge($a->status);
                    ?>
                        <div class="agendamento-item">
                            <div class="agendamento-item__header">
                                <div class="agendamento-item__date">
                                    <div class="date-badge">
                                        <div class="date-badge__day"><?php echo $data['dia']; ?></div>
                                        <div class="date-badge__month"><?php echo $data['mes']; ?></div>
                                    </div>
                                    <div class="agendamento-item__info">
                                        <h3 class="agendamento-item__title">Agendamento #<?php echo htmlspecialchars($a->id); ?></h3>
                                        <div class="agendamento-servicos">
                                            <?php 
                                            // Divide os serviços e cria badges individuais
                                            $servicos_array = explode(', ', $a->servicos ?? 'Serviço');
                                            foreach($servicos_array as $servico_nome): 
                                            ?>
                                                <span class="servico-badge">
                                                    <i data-lucide="scissors"></i>
                                                    <?php echo htmlspecialchars(trim($servico_nome)); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                        <p class="agendamento-item__details">
                                            <strong>Profissional:</strong> <?php echo htmlspecialchars($a->profissional_nome ?? 'N/A'); ?><br>
                                            <strong>Horário:</strong> <?php echo $data['hora']; ?> • <?php echo $data['data_completa']; ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="status-badge <?php echo $status['class']; ?>">
                                    <?php echo $status['texto']; ?>
                                </span>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                        <div class="empty-state">
                            <div class="empty-state__icon">
                                <i data-lucide="calendar-x"></i>
                            </div>
                            <div class="empty-state__text">Você ainda não possui agendamentos</div>
                        </div>
                    <?php endif; ?>
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

        // Configurar restrições de horário no datetime picker
        document.addEventListener('DOMContentLoaded', function() {
            const dataHoraInput = document.getElementById('dataHora');
            const servicoSelect = document.getElementById('servico_id');
            
            if (dataHoraInput) {
                // Definir data/hora mínima como agora
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                
                dataHoraInput.min = `${year}-${month}-${day}T${hours}:${minutes}`;
                
                // Validar horário ao mudar
                dataHoraInput.addEventListener('change', function() {
                    validateTimeRange();
                });
                
                // Validar quando serviço é selecionado
                if (servicoSelect) {
                    servicoSelect.addEventListener('change', function() {
                        validateTimeRange();
                    });
                }
            }
            
            function validateTimeRange() {
                if (!dataHoraInput.value) return;
                
                const selectedDateTime = new Date(dataHoraInput.value);
                const selectedHour = selectedDateTime.getHours();
                const selectedMinute = selectedDateTime.getMinutes();
                
                // Converter hora para minutos desde meia-noite para facilitar cálculos
                const selectedTimeInMinutes = selectedHour * 60 + selectedMinute;
                const workStartMinutes = 8 * 60; // 8:00 AM
                const workEndMinutes = 16 * 60; // 4:00 PM
                
                // Obter duração do serviço selecionado
                let servicoDuracao = 0;
                if (servicoSelect && servicoSelect.value) {
                    const selectedOption = servicoSelect.options[servicoSelect.selectedIndex];
                    servicoDuracao = parseInt(selectedOption.getAttribute('data-duracao') || 0);
                }
                
                // Verificar se o horário inicial está dentro do expediente
                if (selectedTimeInMinutes < workStartMinutes) {
                    alert('O horário de início deve ser às 8:00 ou posterior.');
                    dataHoraInput.value = '';
                    return;
                }
                
                // Verificar se o serviço termina antes do fim do expediente
                const serviceEndMinutes = selectedTimeInMinutes + servicoDuracao;
                if (serviceEndMinutes > workEndMinutes) {
                    alert(`Este serviço tem duração de ${servicoDuracao} minutos e terminaria após as 16:00. Por favor, escolha um horário mais cedo.`);
                    dataHoraInput.value = '';
                    return;
                }
                
                // Arredondar para múltiplos de 30 minutos (opcional, para organização)
                const remainder = selectedMinute % 30;
                if (remainder !== 0) {
                    const roundedMinute = selectedMinute - remainder;
                    selectedDateTime.setMinutes(roundedMinute);
                    
                    const newYear = selectedDateTime.getFullYear();
                    const newMonth = String(selectedDateTime.getMonth() + 1).padStart(2, '0');
                    const newDay = String(selectedDateTime.getDate()).padStart(2, '0');
                    const newHours = String(selectedDateTime.getHours()).padStart(2, '0');
                    const newMinutes = String(selectedDateTime.getMinutes()).padStart(2, '0');
                    
                    dataHoraInput.value = `${newYear}-${newMonth}-${newDay}T${newHours}:${newMinutes}`;
                }
            }
        });
    </script>
</body>
</html>
