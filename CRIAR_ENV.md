# Como Criar o Arquivo .env

## Opção 1: Criar Manualmente (Recomendado)

1. Crie um novo arquivo na raiz do projeto chamado: `.env`
2. Cole o seguinte conteúdo:

```
DB_HOST=127.0.0.1
DB_NAME=tcc
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

3. Salve o arquivo

## Opção 2: Usar o Arquivo de Exemplo

1. Copie o arquivo `.env.example` 
2. Renomeie para `.env` (remova o `.example`)
3. Ajuste os valores se necessário

## Configuração para XAMPP (Padrão)

```env
DB_HOST=127.0.0.1
DB_NAME=tcc
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

## Se você alterou a senha do MySQL no XAMPP

Se você configurou uma senha para o MySQL, altere a linha `DB_PASS`:

```env
DB_PASS=sua_senha_aqui
```

## Verificar se está funcionando

Após criar o arquivo `.env`, acesse:
```
http://localhost:8000/Index.php
```

Se aparecer a tela de login, está tudo configurado corretamente!

