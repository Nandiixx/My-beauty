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
                (currentUrl.includes('acao=agenda_profissional_mostrar') && itemHref.includes('acao=agenda_profissional_mostrar')) ||
                (currentUrl.includes('acao=servico_listar') && itemHref.includes('acao=servico_listar')) ||
                (currentUrl.includes('acao=funcionario_listar') && itemHref.includes('acao=funcionario_listar')) ||
                (currentUrl.includes('acao=gerenciar_agendamento_mostrar') && itemHref.includes('acao=gerenciar_agendamento_mostrar'))) {
                item.classList.add('burger-menu__item--active');
            }
        }
    });
}

// Inicializar ícones Lucide após o DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    // Estado já está sendo restaurado pela função acima, mas garantir aqui também
    const isPinned = localStorage.getItem('sidebarPinned') === 'true';
    if (isPinned) {
        applyPinnedState();
    }
    // Marcar item ativo
    markActiveMenuItem();
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
