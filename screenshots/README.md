# Screenshots - MyBeauty Application

Este diretório contém screenshots de todas as views do sistema MyBeauty capturadas em resolução **1280x720**.

## 📋 Visão Geral

Total de screenshots: **17 views capturadas**

### 🔐 Auth (Autenticação) - 4 screenshots

1. **auth_01_login.png** - Tela de Login
   - Formulário de autenticação com email e senha
   - Links para recuperação de senha e cadastro
   
2. **auth_02_cadastrar.png** - Cadastro de Cliente
   - Formulário de registro com nome, email, senha e telefone
   - Validação de senha com confirmação

3. **auth_03_recuperar_senha.png** - Recuperar Senha
   - Formulário para solicitar reset de senha por email
   
4. **auth_04_resetar_senha.png** - Redefinir Senha
   - Formulário para criar nova senha após receber token

### 👤 Cliente - 4 screenshots

1. **cliente_01_inicio.png** - Dashboard do Cliente
   - Visão geral com estatísticas de agendamentos
   - Próximos agendamentos
   - Ações rápidas

2. **cliente_02_agendamento.png** - Criar Agendamento
   - Formulário para agendar novos serviços
   - Seleção de profissional, serviço e data/hora

3. **cliente_03_perfil.png** - Meu Perfil
   - Informações pessoais do cliente
   - Edição de dados cadastrais

4. **cliente_04_editar_agendamento.png** - Editar Agendamento
   - Formulário de edição de agendamento existente
   - Alteração de profissional, serviço e horário

### 💼 Profissional - 4 screenshots

1. **profissional_01_inicio.png** - Dashboard do Profissional
   - Estatísticas de agendamentos (hoje, semana, concluídos)
   - Próximos agendamentos
   - Ações rápidas
   
2. **profissional_02_agenda.png** - Agenda do Profissional
   - Visualização da agenda completa
   - Agendamentos por status (Agendado, Concluído, Cancelado)

3. **profissional_03_gerenciar.png** - Gerenciar Agendamentos
   - Visualização e gestão de todos os agendamentos
   - Opções de confirmar ou cancelar agendamentos

4. **profissional_04_cadastrar.png** - Cadastrar Profissional
   - Formulário de cadastro de novo profissional
   - Campos: nome, email, senha, matrícula, cargo e especialidade

### ⚙️ Admin - 5 screenshots

1. **admin_01_inicio.png** - Dashboard Administrativo
   - Estatísticas gerais do salão
   - Total de clientes, funcionários, serviços e agendamentos
   - Links para gerenciamento

2. **admin_02_servicos.png** - Gerenciar Serviços
   - Listagem de serviços oferecidos
   - Opções para adicionar, editar e remover serviços

3. **admin_03_funcionarios.png** - Gerenciar Funcionários (Login redirect)
   - View protegida - redireciona para login sem autenticação adequada

4. **admin_04_gerenciar.png** - Gerenciar Agendamentos Admin
   - Interface administrativa para gestão de agendamentos
   - Visão completa de todos os agendamentos do salão

5. **admin_05_servico_form.png** - Formulário de Serviço
   - Cadastro/edição de serviços
   - Campos: nome, descrição, preço e duração

## 🎨 Especificações Técnicas

- **Resolução**: 1280x720 pixels (16:9)
- **Formato**: PNG
- **Profundidade de cor**: 8-bit RGB
- **Navegador**: Chromium (Playwright)

## 📂 Estrutura de Diretórios

```
screenshots/
├── Auth/              # Views de autenticação (4 screenshots)
├── Cliente/           # Views do cliente (4 screenshots)
├── Profissional/      # Views do profissional (4 screenshots)
└── Admin/             # Views administrativas (5 screenshots)
```

## 🔧 Como as Screenshots Foram Capturadas

As screenshots foram capturadas automaticamente usando:
1. **PHP Built-in Server** rodando na porta 8000
2. **Playwright** (navegador Chromium) com viewport 1280x720
3. **Mock wrappers** para simular sessões autenticadas e dados de teste

### Mock Files Utilizados

Para capturar views autenticadas, foram criados wrappers PHP temporários:
- `mock_cliente_inicio.php`
- `mock_cliente_agendamento.php`
- `mock_cliente_perfil.php`
- `mock_profissional_inicio.php`
- `mock_profissional_agenda.php`
- `mock_admin_inicio.php`
- `mock_admin_servicos.php`
- `mock_admin_funcionarios.php`

Esses arquivos simulam sessões autenticadas e fornecem dados mockados para renderização das views.

## 📝 Observações

- Todas as screenshots foram capturadas com dados de exemplo/mock
- Alguns elementos externos (Google Fonts, Lucide Icons) podem não carregar completamente devido a bloqueios de rede
- As screenshots refletem o estado visual das views após a refatoração MVC
- Views que requerem autenticação foram capturadas usando mock wrappers com sessões simuladas

## ✅ Verificação de Dimensões

Para verificar que todas as screenshots estão na resolução correta:

```bash
file screenshots/*/*.png | grep "1280 x 720"
```

Todas as imagens devem mostrar: `PNG image data, 1280 x 720, 8-bit/color RGB`

---

**Data de Captura**: Novembro 2024  
**Sistema**: MyBeauty - Gerenciamento de Salão de Beleza  
**Arquitetura**: MVC (Model-View-Controller)
