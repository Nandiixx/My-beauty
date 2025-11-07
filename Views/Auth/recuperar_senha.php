<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>MyBeauty - Recuperar senha</title>
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
						<h1 class="brand-title">Recuperar senha</h1>
						<p class="brand-subtitle">Informe seu e-mail para redefinir</p>
					</div>
				</div>

				<?php if (isset($_GET['erro'])): ?>
				<div class="alert-error" role="alert">
					<?php 
					if ($_GET['erro'] == '1') {
						echo 'E-mail não encontrado no sistema.';
					} else {
						echo 'Erro ao processar solicitação. Tente novamente.';
					}
					?>
				</div>
				<?php endif; ?>

				<?php if (isset($_GET['sucesso'])): ?>
				<div class="alert-success" role="alert">
					Instruções para recuperação de senha foram enviadas para seu e-mail.
				</div>
				<?php endif; ?>

				<form class="auth-form" action="Index.php?acao=resetar_senha_mostrar" method="GET" novalidate>
					<input type="hidden" name="acao" value="resetar_senha_mostrar">
					<div class="input-field">
						<span class="input-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M20 4H4c-1.1 0-2 .9-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2Zm0 4-8 5-8-5V6l8 5 8-5v2Z" fill="currentColor"/>
							</svg>
						</span>
						<input type="email" id="email" name="email" placeholder="Seu e-mail" required aria-label="E-mail" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
					</div>

					<button type="submit" class="btn-primary" data-loading="false" aria-busy="false">
						<span class="btn-label">Continuar</span>
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
