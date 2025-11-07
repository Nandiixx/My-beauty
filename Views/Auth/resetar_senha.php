<?php
// Verifica se o usuário tem permissão para acessar esta página
$email = isset($_GET['email']) ? trim((string)$_GET['email']) : '';
$token = isset($_GET['token']) ? (string)$_GET['token'] : '';

// Se o email não foi fornecido, redireciona para recuperar senha
if (empty($email)) {
	header('Location: Index.php?acao=recuperar_senha_mostrar');
	exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>MyBeauty - Redefinir senha</title>
	<link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg">
	<link rel="preload" as="image" href="../../assets/images/background.png">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
	<script src="script.js"></script>
</head>
<body>
	<main class="auth-bg">
		<section class="auth-wrapper">
			<div class="auth-card">
				<div class="auth-brand">
					<div class="brand-logo" aria-hidden="true">
						<svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12 3c2.8 0 5 2.2 5 5 0 1.6-.8 3.1-2 4 2.8.4 5 2.8 5 5.7V19c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2v-1.3c0-2.9 2.2-5.3 5-5.7-1.2-.9-2-2.4-2-4 0-2.8 2.2-5 5-5Z" fill="currentColor"/>
						</svg>
					</div>
					<div>
						<h1 class="brand-title">Redefinir senha</h1>
						<p class="brand-subtitle">Digite sua nova senha</p>
					</div>
				</div>

				<?php if (isset($_GET['erro'])): ?>
				<div class="alert-error" role="alert">
					<?php 
					if ($_GET['erro'] == '1') {
						echo 'Erro ao atualizar senha. Verifique o e-mail informado.';
					} else if ($_GET['erro'] == '2') {
						echo 'As senhas não coincidem ou estão vazias.';
					} else {
						echo 'Erro ao processar solicitação.';
					}
					?>
				</div>
				<?php endif; ?>

				<form class="auth-form" action="Index.php?acao=resetar_senha_processar" method="POST" novalidate>
					<input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
					<input type="hidden" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">

					<div class="input-field">
						<span class="input-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M17 8h-1V6a4 4 0 10-8 0v2H7a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2v-8a2 2 0 00-2-2Zm-7-2a2 2 0 114 0v2h-4V6Z" fill="currentColor"/>
							</svg>
						</span>
						<input type="password" id="nova_senha" name="nova_senha" placeholder="Nova senha" required aria-label="Nova senha" minlength="6">
						<button type="button" class="toggle-password" onclick="togglePassword('nova_senha')" aria-label="Mostrar senha">
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
						<input type="password" id="confirma_senha" name="confirma_senha" placeholder="Confirmar senha" required aria-label="Confirmar senha" minlength="6">
						<button type="button" class="toggle-password" onclick="togglePassword('confirma_senha')" aria-label="Mostrar senha">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5ZM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5Zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3Z" fill="currentColor"/>
							</svg>
						</button>
					</div>

					<button type="submit" class="btn-primary" data-loading="false" aria-busy="false">
						<span class="btn-label">Salvar nova senha</span>
						<span class="btn-spinner" aria-hidden="true"></span>
					</button>
				</form>

				<div class="auth-actions">
					<a class="link-muted" href="Index.php?acao=login_mostrar">Voltar ao login</a>
				</div>
			</div>
		</section>
	</main>
</body>
</html>
