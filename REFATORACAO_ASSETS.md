# Atualização de Referências aos Assets

Os arquivos `style.css` e `script.js` foram movidos para a pasta `assets/`.

## Estrutura Antiga:

```
/style.css
/script.js
```

## Estrutura Nova:

```
/assets/css/style.css
/assets/js/script.js
```

## Arquivos que precisam ser atualizados:

Procure por estas referências nos arquivos `.php` dentro da pasta `Views/`:

### Referências ao CSS:

```php
<!-- ANTES -->
<link rel="stylesheet" href="../style.css">

<!-- DEPOIS -->
<link rel="stylesheet" href="../assets/css/style.css">
```

### Referências ao JavaScript:

```php
<!-- ANTES -->
<script src="../script.js"></script>

<!-- DEPOIS -->
<script src="../assets/js/script.js"></script>
```

## Comando para atualizar automaticamente (PowerShell):

```powershell
# Atualizar referências ao CSS
Get-ChildItem -Path "Views\*.php" -Recurse | ForEach-Object {
    (Get-Content $_.FullName) -replace 'href="\.\./style\.css"', 'href="../assets/css/style.css"' | Set-Content $_.FullName
}

# Atualizar referências ao JS
Get-ChildItem -Path "Views\*.php" -Recurse | ForEach-Object {
    (Get-Content $_.FullName) -replace 'src="\.\./script\.js"', 'src="../assets/js/script.js"' | Set-Content $_.FullName
}
```

## Arquivos que contêm estas referências:

- Views/login.php
- Views/cadastrar.php
- Views/inicio_cliente.php
- Views/inicio_profissional.php
- Views/inicio_admi.php
- Views/agenda_profissional.php
- Views/agendamento.php
- Views/gerenciar_agendamento.php
- Views/meu_perfil.php
- Views/recuperar_senha.php
- Views/resetar_senha.php
- Views/servico_formulario.php
- Views/servico_listar.php
- Views/funcionario_editar.php
- Views/funcionario_listar.php
- Views/cadastrarprofissional.php

**NOTA:** Execute os comandos acima no terminal PowerShell a partir da raiz do projeto.
