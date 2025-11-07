<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_cargo'], ['PROPRIETARIO', 'GERENTE_FINANCEIRO'])) {
    header('Location: Index.php?acao=login_mostrar');
    exit;
}

// As variáveis $servico, $acao e $titulo são definidas pelo Controller
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Admin';

// Define a action do formulário (para qual rota enviar)
$action_url = ($acao == 'cadastrar') 
    ? 'Index.php?acao=servico_cadastrar' 
    : 'Index.php?acao=servico_editar&id=' . htmlspecialchars($servico->getId());

$btn_texto = ($acao == 'cadastrar') ? 'Cadastrar Serviço' : 'Atualizar Serviço';
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
                <a href="Index.php?acao=servico_listar" class="btn-header btn-header--secondary">
                    <i data-lucide="arrow-left"></i>
                    <span>Voltar para Lista</span>
                </a>
                <a href="Index.php?acao=inicio" class="btn-header btn-header--primary">
                    <i data-lucide="home"></i>
                    <span>Dashboard</span>
                </a>
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
            <p><?php echo $acao == 'cadastrar' ? 'Preencha os dados para cadastrar um novo serviço' : 'Edite as informações do serviço'; ?></p>
        </div>

        <!-- Formulário -->
        <div class="section-card">
            <div class="section-card__header">
                <h2 class="section-card__title">
                    <i data-lucide="file-edit"></i>
                    Dados do Serviço
                </h2>
            </div>

            <form action="<?php echo $action_url; ?>" method="POST" class="form-modern">
                <div class="form-row">
                    <div class="form-group form-group--full">
                        <label for="nome">
                            <i data-lucide="sparkles"></i>
                            Nome do Serviço <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            value="<?php echo htmlspecialchars($servico->getNome() ?? ''); ?>" 
                            placeholder="Ex: Corte de Cabelo Feminino"
                            required
                            maxlength="100"
                        >
                        <small class="form-hint">Nome do serviço que será exibido para os clientes</small>
                    </div>
                </div>

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
                            placeholder="Descreva os detalhes do serviço..."
                            required
                            maxlength="500"
                        ><?php echo htmlspecialchars($servico->getDescricao() ?? ''); ?></textarea>
                        <small class="form-hint">Forneça uma descrição detalhada do serviço (máx. 500 caracteres)</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="duracao_min">
                            <i data-lucide="clock"></i>
                            Duração (minutos) <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="duracao_min" 
                            name="duracao_min" 
                            value="<?php echo htmlspecialchars($servico->getDuracaoMin() ?? ''); ?>" 
                            placeholder="60"
                            min="1"
                            max="480"
                            required
                        >
                        <small class="form-hint">Tempo estimado em minutos (1 a 480)</small>
                    </div>

                    <div class="form-group">
                        <label for="preco">
                            <i data-lucide="dollar-sign"></i>
                            Preço (R$) <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="preco" 
                            name="preco" 
                            value="<?php echo htmlspecialchars($servico->getPreco() ?? ''); ?>" 
                            placeholder="50.00"
                            pattern="^\d+(\.\d{1,2})?$"
                            required
                        >
                        <small class="form-hint">Valor do serviço em reais (use ponto para decimais, ex: 50.00)</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i data-lucide="<?php echo $btn_icone; ?>"></i>
                        <span><?php echo $btn_texto; ?></span>
                    </button>
                    <a href="Index.php?acao=servico_listar" class="btn-secondary">
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
                <h3 class="info-card__title">Dicas para cadastrar serviços</h3>
                <ul class="info-card__list">
                    <li><i data-lucide="check"></i> Use nomes descritivos e claros para os serviços</li>
                    <li><i data-lucide="check"></i> Inclua todos os detalhes importantes na descrição</li>
                    <li><i data-lucide="check"></i> Defina durações realistas considerando todo o processo</li>
                    <li><i data-lucide="check"></i> Mantenha os preços atualizados conforme a tabela de valores</li>
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
            const preco = document.getElementById('preco');
            const duracao = document.getElementById('duracao_min');
            
            // Formatar e validar preço
            if (preco) {
                preco.addEventListener('input', function() {
                    // Remove caracteres não numéricos exceto ponto
                    this.value = this.value.replace(/[^\d.]/g, '');
                    
                    // Garante apenas um ponto decimal
                    const parts = this.value.split('.');
                    if (parts.length > 2) {
                        this.value = parts[0] + '.' + parts.slice(1).join('');
                    }
                });
                
                preco.addEventListener('blur', function() {
                    const value = parseFloat(this.value);
                    if (!isNaN(value) && value > 0) {
                        this.value = value.toFixed(2);
                    } else if (this.value !== '') {
                        alert('Por favor, insira um valor válido para o preço.');
                        this.value = '';
                    }
                });
            }
            
            // Validar duração
            if (duracao) {
                duracao.addEventListener('input', function() {
                    const value = parseInt(this.value);
                    if (value < 1) {
                        this.value = 1;
                    } else if (value > 480) {
                        this.value = 480;
                    }
                });
            }
            
            // Confirmação ao submeter
            if (form) {
                form.addEventListener('submit', function(e) {
                    const acao = '<?php echo $acao; ?>';
                    const nome = document.getElementById('nome').value;
                    
                    if (acao === 'editar') {
                        if (!confirm('Tem certeza que deseja atualizar o serviço "' + nome + '"?')) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
