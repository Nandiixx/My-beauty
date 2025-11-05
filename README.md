# MyBeauty - Sistema de Gerenciamento de Salão

Sistema completo de gerenciamento para salões de beleza, desenvolvido como projeto de TCC. Permite o cadastro de clientes, profissionais e gestão de agendamentos.

## 📋 Sobre o Projeto

O MyBeauty é uma aplicação web desenvolvida em PHP que oferece uma solução completa para administração de salões de beleza, incluindo gestão de agendamentos, clientes, serviços e profissionais.

## ✨ Funcionalidades

### Autenticação
- Sistema de login seguro com hash de senhas
- Cadastro de clientes
- Diferenciação de perfis (Cliente e Funcionário)
- Recuperação de senha
- Toggle para visualização de senhas

### Clientes
- Cadastro completo com validação
- Dashboard personalizado
- Agendamento de serviços
- Visualização de agendamentos

### Profissionais
- Gestão de agenda
- Confirmação e cancelamento de agendamentos
- Visualização de clientes
- Dashboard específico

### Agendamentos
- Sistema de agendamento completo
- Status de agendamento (Agendado, Concluído, Cancelado)
- Relacionamento entre Cliente e Profissional
- Gestão de serviços e horários

## 🛠️ Tecnologias Utilizadas

- **Backend:** PHP 8.x
- **Frontend:** HTML5, CSS3, JavaScript
- **Banco de Dados:** MySQL
- **Design:** CSS com Glassmorphism e design moderno
- **Fontes:** Google Fonts (Poppins)

## 📁 Estrutura do Projeto

```
Eu-e-o-Shadow-main/
├── Controllers/          # Lógica de negócio
│   ├── UsuarioController.php
│   ├── AgendamentoController.php
│   └── Navegacao.php
├── Models/               # Modelos de dados
│   ├── Usuario.php
│   ├── Cliente.php
│   ├── Funcionario.php
│   ├── Agendamento.php
│   ├── Servico.php
│   └── ConexaoDB.php
├── Views/                # Interface do usuário
│   ├── login.php
│   ├── cadastrar.php
│   ├── inicio_cliente.php
│   ├── inicio_profissional.php
│   └── agendamento.php
├── sql/                  # Scripts SQL
│   ├── criar_tabela_usuarios.sql
│   ├── criar_tabela_recuperacao.sql
│   └── bdtcc.sql
├── Config/               # Configurações
│   └── Database.php
├── Index.php             # Página inicial
├── script.js             # JavaScript
├── style.css             # Estilos CSS
└── background.png        # Imagem de fundo
```

## 🗄️ Banco de Dados

O sistema utiliza as seguintes tabelas principais:

- **Usuario:** Tabela base para todos os usuários
- **Cliente:** Informações específicas dos clientes
- **Funcionario:** Dados dos funcionários (Recepcionista, Profissional, Proprietário, Gerente)
- **Servico:** Serviços oferecidos pelo salão
- **Agendamento:** Relaciona cliente e profissional em uma data/hora
- **RecuperacaoSenha:** Tokens para recuperação de senhas

## 🚀 Como Executar

### Pré-requisitos
- PHP 8.0 ou superior
- MySQL 5.7 ou superior
- Apache/Nginx
- Composer (opcional)

### Instalação

1. Clone o repositório:
```bash
git clone https://github.com/Nandiixx/Eu-e-o-Shadow.git
cd Eu-e-o-Shadow
```

2. Configure o banco de dados:
```bash
# Execute os scripts SQL na pasta sql/
mysql -u root -p < sql/criar_tabela_usuarios.sql
mysql -u root -p < sql/bdtcc.sql
```

3. Configure a conexão com o banco:
   - Edite o arquivo `Models/ConexaoDB.php` ou `Config/Database.php`
   - Configure host, usuário, senha e nome do banco

4. Inicie o servidor:
```bash
php -S localhost:8000
```

5. Acesse no navegador:
```
http://localhost:8000/index.php
```

## 🎨 Interface

O sistema possui uma interface moderna com:
- Design Glassmorphism
- Responsivo para desktop e mobile
- Animações suaves
- Paleta de cores rosa (#ea638c)
- Fundo fixo com imagem personalizada
- Formulários com validação visual

## 🔐 Segurança

- Senhas armazenadas com `password_hash()` (bcrypt)
- Validação de entrada nos formulários
- Proteção contra SQL Injection (PDO prepared statements)
- Sessões seguras
- CSRF protection (implementar no futuro)

## 📝 Rotas Principais

### Autenticação
- `index.php?acao=login_mostrar` - Página de login
- `index.php?acao=autenticar` - Processa login
- `index.php?acao=cadastro_mostrar` - Página de cadastro
- `index.php?acao=salvar_cliente` - Salva novo cliente
- `index.php?acao=logout` - Encerra sessão

### Agendamentos
- `index.php?acao=agendamento_mostrar` - Mostra formulário de agendamento
- `index.php?acao=agendamento_salvar` - Salva novo agendamento
- `index.php?acao=agenda_profissional_mostrar` - Agenda do profissional
- `index.php?acao=confirmar&id=X` - Confirma agendamento
- `index.php?acao=cancelar&id=X` - Cancela agendamento

### Dashboard
- `index.php?acao=inicio` - Redireciona para dashboard apropriado

## 👥 Tipos de Usuário

1. **Cliente**
   - Pode se cadastrar
   - Visualizar e criar agendamentos
   - Acessar seu dashboard

2. **Funcionário**
   - Recepcionista
   - Profissional de Beleza
   - Proprietário
   - Gerente Financeiro

## 🔄 Desenvolvimento Futuro

- [ ] Implementar CRUD completo de serviços
- [ ] Sistema financeiro completo
- [ ] Relatórios e estatísticas
- [ ] Sistema de notificações
- [ ] API REST
- [ ] Aplicativo mobile
- [ ] Integração com pagamentos
- [ ] Sistema de fidelidade

## 📄 Licença

Este projeto foi desenvolvido como Trabalho de Conclusão de Curso (TCC).

## 👨‍💻 Autor

- GitHub: [@Nandiixx](https://github.com/Nandiixx)

## 🤝 Contribuindo

Contribuições são sempre bem-vindas! Sinta-se à vontade para fazer um fork do projeto e enviar pull requests.

## 📞 Suporte

Para dúvidas ou suporte, abra uma issue no repositório.

---

**Desenvolvido com ❤️ para o TCC**

