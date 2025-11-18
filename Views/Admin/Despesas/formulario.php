<?php
// As variáveis $despesa, $acao e $titulo são definidas pelo Controller
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Admin';

// Define a action do formulário (para qual rota enviar)
$action_url = ($acao == 'cadastrar') 
    ? 'Index.php?acao=despesa_cadastrar' 
    : 'Index.php?acao=despesa_editar&id=' . htmlspecialchars($despesa->getId());

$btn_texto = ($acao == 'cadastrar') ? 'Cadastrar Despesa' : 'Atualizar Despesa';
$btn_icone = ($acao == 'cadastrar') ? 'plus-circle' : 'save';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?> - MyBeauty</title>
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
                    <a href="Index.php?acao=funcionario_listar" class="burger-menu__item">
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
                    <a href="Index.php?acao=despesa_listar" class="burger-menu__item burger-menu__item--active">
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
                <a href="Index.php?acao=despesa_listar" class="btn-header btn-header--primary">
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
                <i data-lucide="<?php echo $acao == 'cadastrar' ? 'file-plus' : 'edit'; ?>"></i>
                <?php echo htmlspecialchars($titulo); ?>
            </h1>
            <p><?php echo $acao == 'cadastrar' ? 'Preencha os dados para cadastrar uma nova despesa' : 'Edite as informações da despesa'; ?></p>
        </div>

        <!-- Formulário -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="file-edit"></i>
                    Dados da Despesa
                </h2>
            </div>

            <form action="<?php echo $action_url; ?>" method="POST" class="form-modern">
                <div class="form-row">
                    <div class="form-group form-group--full">
                        <label for="descricao">
                            <i data-lucide="file-text"></i>
                            Descrição <span class="required">*</span>
                        </label>
                        <textarea 
                            id="descricao" 
                            name="descricao" 
                            rows="4" 
                            placeholder="Descreva a despesa..."
                            required
                            maxlength="500"
                        ><?php echo htmlspecialchars($despesa->getDescricao() ?? ''); ?></textarea>
                        <small class="form-hint">Forneça uma descrição detalhada da despesa (máx. 500 caracteres)</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="valor">
                            <i data-lucide="dollar-sign"></i>
                            Valor (R$) <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="valor" 
                            name="valor" 
                            value="<?php echo htmlspecialchars($despesa->getValor() ?? ''); ?>" 
                            placeholder="150.00"
                            pattern="^\d+(\.\d{1,2})?$"
                            required
                        >
                        <small class="form-hint">Valor da despesa em reais (use ponto para decimais, ex: 150.00)</small>
                    </div>

                    <div class="form-group">
                        <label for="data">
                            <i data-lucide="calendar"></i>
                            Data <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="data" 
                            name="data" 
                            value="<?php echo htmlspecialchars($despesa->getData() ?? ''); ?>" 
                            required
                            max="<?php echo date('Y-m-d'); ?>"
                        >
                        <small class="form-hint">Data da despesa</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group form-group--full">
                        <label for="categoria">
                            <i data-lucide="tag"></i>
                            Categoria <span class="required">*</span>
                        </label>
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione uma categoria</option>
                            <option value="Aluguel" <?php echo ($despesa->getCategoria() ?? '') == 'Aluguel' ? 'selected' : ''; ?>>Aluguel</option>
                            <option value="Água" <?php echo ($despesa->getCategoria() ?? '') == 'Água' ? 'selected' : ''; ?>>Água</option>
                            <option value="Energia" <?php echo ($despesa->getCategoria() ?? '') == 'Energia' ? 'selected' : ''; ?>>Energia</option>
                            <option value="Internet" <?php echo ($despesa->getCategoria() ?? '') == 'Internet' ? 'selected' : ''; ?>>Internet</option>
                            <option value="Produtos" <?php echo ($despesa->getCategoria() ?? '') == 'Produtos' ? 'selected' : ''; ?>>Produtos</option>
                            <option value="Equipamentos" <?php echo ($despesa->getCategoria() ?? '') == 'Equipamentos' ? 'selected' : ''; ?>>Equipamentos</option>
                            <option value="Marketing" <?php echo ($despesa->getCategoria() ?? '') == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                            <option value="Manutenção" <?php echo ($despesa->getCategoria() ?? '') == 'Manutenção' ? 'selected' : ''; ?>>Manutenção</option>
                            <option value="Salários" <?php echo ($despesa->getCategoria() ?? '') == 'Salários' ? 'selected' : ''; ?>>Salários</option>
                            <option value="Impostos" <?php echo ($despesa->getCategoria() ?? '') == 'Impostos' ? 'selected' : ''; ?>>Impostos</option>
                            <option value="Outros" <?php echo ($despesa->getCategoria() ?? '') == 'Outros' ? 'selected' : ''; ?>>Outros</option>
                        </select>
                        <small class="form-hint">Categoria da despesa para organização</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i data-lucide="<?php echo $btn_icone; ?>"></i>
                        <span><?php echo $btn_texto; ?></span>
                    </button>
                    <a href="Index.php?acao=despesa_listar" class="btn-secondary">
                        <i data-lucide="x"></i>
                        <span>Cancelar</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Informações adicionais -->
        <div class="info-card">
            <div class="info-card__icon">
                <i data-lucide="info"></i>
            </div>
            <div class="info-card__content">
                <h3 class="info-card__title">Dicas para registrar despesas</h3>
                <ul class="info-card__list">
                    <li><i data-lucide="check"></i> Registre todas as despesas do salão para controle financeiro preciso</li>
                    <li><i data-lucide="check"></i> Use categorias apropriadas para facilitar relatórios e análises</li>
                    <li><i data-lucide="check"></i> Inclua detalhes suficientes na descrição para fácil identificação</li>
                    <li><i data-lucide="check"></i> Mantenha as datas corretas para um histórico confiável</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Inicializar ícones Lucide após o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Validação de formulário em tempo real
            const form = document.querySelector('.form-modern');
            const valor = document.getElementById('valor');
            
            // Formatar e validar valor
            if (valor) {
                valor.addEventListener('input', function() {
                    // Remove caracteres não numéricos exceto ponto
                    this.value = this.value.replace(/[^\d.]/g, '');
                    
                    // Garante apenas um ponto decimal
                    const parts = this.value.split('.');
                    if (parts.length > 2) {
                        this.value = parts[0] + '.' + parts.slice(1).join('');
                    }
                });
                
                valor.addEventListener('blur', function() {
                    const value = parseFloat(this.value);
                    if (!isNaN(value) && value > 0) {
                        this.value = value.toFixed(2);
                    } else if (this.value !== '') {
                        alert('Por favor, insira um valor válido para a despesa.');
                        this.value = '';
                    }
                });
            }
            
            // Confirmação ao submeter
            if (form) {
                form.addEventListener('submit', function(e) {
                    const acao = '<?php echo $acao; ?>';
                    const descricao = document.getElementById('descricao').value;
                    
                    if (acao === 'editar') {
                        if (!confirm('Tem certeza que deseja atualizar esta despesa?')) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
