<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'ADMIN') {
    header('Location: Index.php?acao=login_mostrar');
    exit;
}

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
    <link rel="preload" as="image" href="../../../assets/images/background.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="../../../assets/js/script.js"></script>
</head>
<body>
    <main class="auth-bg">
        <section class="auth-wrapper register-page">
            <div class="auth-card">
                <div class="auth-brand">
                    <div class="brand-logo" aria-hidden="true">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 3c2.8 0 5 2.2 5 5 0 1.6-.8 3.1-2 4 2.8.4 5 2.8 5 5.7V19c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2v-1.3c0-2.9 2.2-5.3 5-5.7-1.2-.9-2-2.4-2-4 0-2.8 2.2-5 5-5Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="brand-title">Editar Funcionário</h1>
                        <p class="brand-subtitle">Atualize os dados do funcionário</p>
                    </div>
                </div>

                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert-error" role="alert"><?php echo htmlspecialchars($_SESSION['erro']); ?></div>
                    <?php unset($_SESSION['erro']); ?>
                <?php endif; ?>

                <form class="auth-form form-grid-2" method="POST" action="../Index.php?acao=funcionario_atualizar&id=<?php echo $funcionario->getId(); ?>" novalidate>
                    <div class="input-field">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input type="text" id="nome" name="nome" placeholder="Nome completo" required 
                               value="<?php echo htmlspecialchars($funcionario->getNome()); ?>">
                    </div>

                    <div class="input-field">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" placeholder="E-mail" required
                               value="<?php echo htmlspecialchars($funcionario->getEmail()); ?>">
                    </div>

                    <div class="input-field">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.4-5.1-3.8-6.5-6.5l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM19 12h2a9 9 0 0 0-9-9v2c3.9 0 7 3.1 7 7zm-4 0h2c0-2.8-2.2-5-5-5v2c1.7 0 3 1.3 3 3z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input type="tel" id="telefone" name="telefone" placeholder="Telefone"
                               value="<?php echo htmlspecialchars($funcionario->getTelefone() ?? ''); ?>">
                    </div>

                    <div class="input-field">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input type="text" id="matricula" name="matricula" placeholder="Matrícula" required
                               value="<?php echo htmlspecialchars($funcionario->getMatricula() ?? ''); ?>">
                    </div>

                    <div class="input-field">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="currentColor"/>
                            </svg>
                        </span>
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

                    <div class="input-field" style="grid-column: 1 / -1;">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input type="text" id="especialidade" name="especialidade" placeholder="Especialidade (opcional)"
                               value="<?php echo htmlspecialchars($funcionario->getEspecialidade() ?? ''); ?>">
                    </div>

                    <div style="grid-column: 1 / -1; padding: 1rem 0; border-top: 1px solid rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;">
                        <label style="color: rgba(255,255,255,0.9); font-weight: 600; display: block; margin-bottom: 0.5rem;">
                            Alterar Senha (deixe em branco para manter a atual)
                        </label>
                    </div>

                    <div class="input-field">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 8h-1V6a4 4 0 10-8 0v2H7a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2v-8a2 2 0 00-2-2Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input type="password" id="senha" name="senha" placeholder="Nova senha (opcional)" minlength="6">
                        <button type="button" class="toggle-password" onclick="togglePassword('senha')" aria-label="Mostrar senha">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5ZM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5Zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3Z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>

                    <div class="input-field">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 8h-1V6a4 4 0 10-8 0v2H7a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2v-8a2 2 0 00-2-2Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Confirmar nova senha" minlength="6">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirma_senha')" aria-label="Mostrar senha">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5ZM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5Zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3Z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>

                    <button type="submit" class="btn-primary" data-loading="false" aria-busy="false" style="grid-column: 1 / -1;">
                        <span class="btn-label">Atualizar Funcionário</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>
                </form>

                <div class="auth-divider" role="separator"></div>

                <div class="auth-cta">
                    <p class="auth-cta-text"><a class="auth-cta-link" href="../Index.php?acao=funcionario_listar"><strong>← Voltar para lista</strong></a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
