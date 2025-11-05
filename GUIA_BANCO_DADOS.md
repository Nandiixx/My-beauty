# Guia para Criar o Banco de Dados no XAMPP

## Passo 1: Iniciar o MySQL no XAMPP

1. Abra o **Painel de Controle do XAMPP**
2. Clique em **Start** no módulo **MySQL**
3. Aguarde até aparecer a mensagem verde "Running"

## Passo 2: Acessar o phpMyAdmin

1. No painel do XAMPP, clique em **Admin** ao lado do MySQL
   - Ou acesse diretamente: `http://localhost/phpmyadmin`

## Passo 3: Criar o Banco de Dados

1. No phpMyAdmin, clique em **Novo** no menu lateral esquerdo
2. No campo **Nome do banco de dados**, digite: `tcc`
3. Selecione o **Collation**: `utf8mb4_general_ci` ou `utf8mb4_unicode_ci`
4. Clique em **Criar**

## Passo 4: Importar as Tabelas

### Opção A: Importar pelo phpMyAdmin (Recomendado)

1. No phpMyAdmin, selecione o banco `tcc` no menu lateral
2. Clique na aba **Importar** (Import)
3. Clique em **Escolher arquivo** (Choose File)
4. Navegue até a pasta do seu projeto e selecione: `sql/database.sql`
5. Clique em **Executar** (Go)

### Opção B: Copiar e colar o SQL

1. No phpMyAdmin, selecione o banco `tcc`
2. Clique na aba **SQL**
3. Abra o arquivo `sql/database.sql` em um editor de texto
4. Copie todo o conteúdo
5. Cole no campo SQL do phpMyAdmin
6. Clique em **Executar**

## Passo 5: Criar a Tabela de Recuperação de Senha (Opcional)

Se você precisar da funcionalidade completa de recuperação de senha, execute este SQL adicional:

```sql
CREATE TABLE RecuperacaoSenha (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    data_expiracao DATETIME NOT NULL,
    utilizado TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE
);
```

1. No phpMyAdmin, selecione o banco `tcc`
2. Clique na aba **SQL**
3. Cole o SQL acima
4. Clique em **Executar**

## Passo 6: Verificar se o Banco foi Criado Corretamente

Você deve ver as seguintes tabelas no banco `tcc`:
- ✅ Usuario
- ✅ Cliente
- ✅ Funcionario
- ✅ Servico
- ✅ Agendamento
- ✅ Agendamento_Servicos
- ✅ RecuperacaoSenha (se criou)

## Configuração da Conexão

O sistema está configurado para usar:
- **Host:** `127.0.0.1` ou `localhost`
- **Banco:** `tcc`
- **Usuário:** `root`
- **Senha:** (vazia - padrão do XAMPP)

Se você alterar a senha do MySQL no XAMPP, você precisará criar um arquivo `.env` na raiz do projeto com:

```
DB_HOST=127.0.0.1
DB_NAME=tcc
DB_USER=root
DB_PASS=sua_senha_aqui
DB_CHARSET=utf8mb4
```

## Testar a Conexão

Após criar o banco, acesse:
```
http://localhost:8000/Index.php
```

Se tudo estiver funcionando, você verá a tela de login!

## Dicas

- Se encontrar erro de conexão, verifique se o MySQL está rodando no XAMPP
- Se o XAMPP pedir senha para o MySQL, configure o arquivo `.env` com a senha correta
- O nome do banco padrão é `tcc`, mas você pode alterar no arquivo `.env` se preferir

