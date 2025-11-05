# Como Criar o Banco de Dados - XAMPP

## ⚠️ IMPORTANTE: Inicie o MySQL primeiro!

### Passo 1: Iniciar o MySQL no XAMPP

1. Abra o **Painel de Controle do XAMPP**
2. Clique em **Start** no módulo **MySQL**
3. Aguarde até aparecer a mensagem verde **"Running"** ✅

---

## Opção A: Criar via Terminal (Recomendado)

### Método 1: Usar o script .bat

1. **Inicie o MySQL** no XAMPP (passo 1 acima)
2. **Duplo clique** no arquivo: `criar_banco.bat`
3. O script vai criar o banco e todas as tabelas automaticamente!

### Método 2: Executar manualmente

1. **Inicie o MySQL** no XAMPP
2. Abra o **PowerShell** ou **CMD** na pasta do projeto
3. Execute o comando:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -e "source sql/criar_banco.sql"
```

Ou execute linha por linha:

```powershell
# Criar o banco
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS tcc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Executar o script SQL
C:\xampp\mysql\bin\mysql.exe -u root tcc < sql/criar_banco.sql
```

---

## Opção B: Criar via phpMyAdmin (Interface Gráfica)

### Passo 1: Acessar o phpMyAdmin

1. No painel do XAMPP, clique em **Admin** ao lado do MySQL
   - Ou acesse: `http://localhost/phpmyadmin`

### Passo 2: Criar o banco de dados

1. No phpMyAdmin, clique em **"Novo"** no menu lateral esquerdo
2. No campo **"Nome do banco de dados"**, digite: `tcc`
3. Selecione o **Collation**: `utf8mb4_unicode_ci`
4. Clique em **"Criar"**

### Passo 3: Importar as tabelas

1. Selecione o banco `tcc` no menu lateral
2. Clique na aba **"Importar"** (Import)
3. Clique em **"Escolher arquivo"**
4. Navegue até a pasta `sql` e selecione: `criar_banco.sql`
5. Clique em **"Executar"** (Go)

### Ou executar o SQL diretamente:

1. Selecione o banco `tcc`
2. Clique na aba **"SQL"**
3. Abra o arquivo `sql/criar_banco.sql` em um editor de texto
4. Copie todo o conteúdo
5. Cole no campo SQL do phpMyAdmin
6. Clique em **"Executar"**

---

## ✅ Verificar se foi criado corretamente

Após executar o script, você deve ver no phpMyAdmin as seguintes tabelas:

- ✅ Usuario
- ✅ Cliente
- ✅ Funcionario
- ✅ Servico
- ✅ Agendamento
- ✅ Agendamento_Servicos
- ✅ RecuperacaoSenha

E também verá alguns serviços de exemplo já cadastrados na tabela `Servico`.

---

## 🔧 Testar a conexão

Após criar o banco:

1. Certifique-se de que o arquivo `.env` está configurado:
   ```
   DB_HOST=127.0.0.1
   DB_NAME=tcc
   DB_USER=root
   DB_PASS=
   DB_CHARSET=utf8mb4
   ```

2. Acesse: `http://localhost:8000/Index.php`

3. Se aparecer a tela de login, está tudo funcionando! ✅

---

## ❌ Problemas Comuns

### Erro: "Can't connect to MySQL server"
**Solução:** Inicie o MySQL no painel do XAMPP primeiro!

### Erro: "Access denied for user 'root'"
**Solução:** Se você configurou uma senha para o MySQL, adicione `-p` no comando:
```powershell
C:\xampp\mysql\bin\mysql.exe -u root -p
```

Ou atualize o arquivo `.env` com a senha:
```
DB_PASS=sua_senha_aqui
```

### Erro: "Unknown database 'tcc'"
**Solução:** O banco não foi criado. Execute novamente o passo de criar o banco primeiro.

---

## 📝 Arquivos criados

- `sql/criar_banco.sql` - Script SQL completo para criar banco e tabelas
- `criar_banco.bat` - Script Windows para executar automaticamente
- `INSTRUCOES_CRIAR_BANCO.md` - Este arquivo com as instruções

