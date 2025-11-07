# Guia de Teste - Refatoração MVC

## 🧪 Checklist de Testes

Use este guia para validar se a refatoração foi bem-sucedida.

---

## 1. ✅ Testes de Autenticação

### Teste 1.1: Login de Cliente

1. Acesse: `http://localhost/Index.php?acao=login_mostrar`
2. Faça login com credenciais de cliente
3. ✅ **Esperado:** Redireciona para `/Index.php?acao=inicio` e exibe o dashboard do cliente
4. ✅ **Verificar:**
   - Nome do usuário aparece corretamente
   - Estatísticas são exibidas
   - CSS está carregado (visual correto)
   - JavaScript está funcionando (interatividade)

### Teste 1.2: Login de Profissional

1. Faça login com credenciais de profissional
2. ✅ **Esperado:** Redireciona para dashboard do profissional
3. ✅ **Verificar:**
   - Agendamentos do profissional são listados
   - Estatísticas corretas (agendamentos hoje, próximos, concluídos)

### Teste 1.3: Login de Admin

1. Faça login com credenciais de admin/proprietário
2. ✅ **Esperado:** Redireciona para dashboard administrativo
3. ✅ **Verificar:**
   - Totais de clientes, funcionários, serviços e agendamentos
   - Links para gerenciamento funcionam

### Teste 1.4: Logout

1. Clique em "Sair" em qualquer dashboard
2. ✅ **Esperado:** Retorna para tela de login
3. ✅ **Verificar:** Sessão foi destruída (não pode acessar dashboards diretamente)

---

## 2. ✅ Testes de Assets (CSS/JS)

### Teste 2.1: CSS está carregando

1. Abra qualquer página do sistema
2. ✅ **Verificar no navegador:**
   - Pressione F12 (DevTools)
   - Vá na aba "Network"
   - Recarregue a página (F5)
   - Procure por `style.css`
   - ✅ **Esperado:** Status 200 OK
   - ✅ **URL correta:** `.../assets/css/style.css`

### Teste 2.2: JavaScript está carregando

1. Ainda no DevTools (F12)
2. Procure por `script.js` na aba Network
3. ✅ **Esperado:** Status 200 OK
4. ✅ **URL correta:** `.../assets/js/script.js`

### Teste 2.3: Visual está correto

1. Compare o visual antes e depois
2. ✅ **Verificar:**
   - Cores estão corretas
   - Layout não quebrou
   - Fontes estão carregadas
   - Ícones aparecem
   - Animações funcionam

### Teste 2.4: Funcionalidades JavaScript

1. Teste interatividade:
   - Menu hambúrguer (se houver)
   - Dropdowns
   - Botões com animações
   - Validações de formulário
2. ✅ **Esperado:** Tudo funciona como antes

---

## 3. ✅ Testes de Funcionalidade

### Teste 3.1: Dashboard do Cliente

**Acesse:** Dashboard após login como cliente

#### Estatísticas

- [ ] Total de agendamentos está correto
- [ ] Próximos agendamentos está correto
- [ ] Concluídos está correto
- [ ] Em andamento está correto

#### Lista de Agendamentos

- [ ] Agendamentos são exibidos corretamente
- [ ] Data e hora estão formatadas
- [ ] Nome do profissional aparece
- [ ] Serviços são listados
- [ ] Status badge tem cor correta (Agendado/Concluído/Cancelado)

#### Ações Rápidas

- [ ] Botão "Novo Agendamento" funciona
- [ ] Botão "Gerenciar Agendamentos" funciona
- [ ] Links levam para páginas corretas

---

### Teste 3.2: Dashboard do Profissional

**Acesse:** Dashboard após login como profissional

#### Estatísticas

- [ ] Total de agendamentos do profissional
- [ ] Agendamentos hoje está correto
- [ ] Concluídos está correto
- [ ] Próximos está correto

#### Lista de Agendamentos

- [ ] Apenas agendamentos do profissional logado
- [ ] Nome do cliente aparece
- [ ] Data/hora estão corretas
- [ ] Status está correto

#### Ações

- [ ] Botão "Minha Agenda" funciona
- [ ] Pode confirmar agendamentos
- [ ] Pode cancelar agendamentos

---

### Teste 3.3: Dashboard do Admin

**Acesse:** Dashboard após login como admin

#### Estatísticas

- [ ] Total de clientes está correto
- [ ] Total de funcionários está correto
- [ ] Total de serviços está correto
- [ ] Total de agendamentos está correto

#### Links de Gerenciamento

- [ ] "Gerenciar Serviços" funciona
- [ ] "Gerenciar Profissionais" funciona
- [ ] "Gerenciar Clientes" funciona (se implementado)

---

## 4. ✅ Testes de Segurança

### Teste 4.1: Proteção de Rotas

1. **Faça logout**
2. Tente acessar diretamente (na URL):
   - `Index.php?acao=inicio`
   - ✅ **Esperado:** Redireciona para login

### Teste 4.2: Validação de Tipos de Usuário

1. **Faça login como CLIENTE**
2. Tente acessar:

   - `Index.php?acao=agenda_profissional_mostrar`
   - ✅ **Esperado:** Redireciona para login ou erro de permissão

3. **Faça login como PROFISSIONAL**
4. Tente acessar:
   - `Index.php?acao=servico_listar` (admin)
   - ✅ **Esperado:** Redireciona para login ou erro de permissão

---

## 5. ✅ Testes de Console (Erros)

### Teste 5.1: Console do Navegador

1. Pressione F12 para abrir DevTools
2. Vá na aba "Console"
3. Navegue por todas as páginas
4. ✅ **Verificar:** Não há erros JavaScript em vermelho

### Teste 5.2: Erros PHP

1. Durante os testes, observe o topo das páginas
2. ✅ **Verificar:** Não há avisos PHP visíveis como:
   - `Warning: Undefined variable...`
   - `Notice: ...`
   - `Fatal error: ...`

---

## 6. ✅ Testes de Helpers

### Teste 6.1: Função formatarData()

1. Crie um arquivo de teste temporário: `teste_helpers.php`

```php
<?php
require_once 'helpers.php';

$data = '2025-11-05 14:30:00';
$resultado = formatarData($data);

echo "Dia: " . $resultado['dia'] . "<br>";
echo "Mês: " . $resultado['mes'] . "<br>";
echo "Hora: " . $resultado['hora'] . "<br>";
echo "Data completa: " . $resultado['data_completa'] . "<br>";
```

2. Acesse: `http://localhost/teste_helpers.php`
3. ✅ **Esperado:**
   - Dia: 05
   - Mês: Nov
   - Hora: 14:30
   - Data completa: 05/11/2025

### Teste 6.2: Função statusBadge()

```php
<?php
require_once 'helpers.php';

$status1 = statusBadge('AGENDADO');
$status2 = statusBadge('CONCLUIDO');
$status3 = statusBadge('CANCELADO');

echo "AGENDADO: " . $status1['class'] . " - " . $status1['texto'] . "<br>";
echo "CONCLUIDO: " . $status2['class'] . " - " . $status2['texto'] . "<br>";
echo "CANCELADO: " . $status3['class'] . " - " . $status3['texto'] . "<br>";
```

3. ✅ **Esperado:**
   - AGENDADO: status-badge--agendado - Agendado
   - CONCLUIDO: status-badge--concluido - Concluído
   - CANCELADO: status-badge--cancelado - Cancelado

---

## 7. ✅ Verificação de Arquivos

### Teste 7.1: Estrutura de Pastas

Verifique se a estrutura está correta:

```
/
├── Index.php (✅ com require_once helpers.php)
├── helpers.php (✅ novo arquivo)
├── assets/ (✅ nova pasta)
│   ├── css/
│   │   └── style.css (✅ movido)
│   └── js/
│       └── script.js (✅ movido)
├── Controllers/
│   ├── AgendamentoController.php (✅ modificado)
│   ├── UsuarioController.php (✅ modificado)
│   └── ...
├── Views/
│   ├── inicio_profissional.php (✅ refatorado)
│   ├── inicio_cliente.php (✅ refatorado)
│   ├── inicio_admi.php (✅ refatorado)
│   └── ...
└── Models/
```

### Teste 7.2: Arquivos Antigos Removidos

- [ ] `style.css` **NÃO** existe na raiz (foi movido)
- [ ] `script.js` **NÃO** existe na raiz (foi movido)

---

## 8. ✅ Teste de Integração Completo

### Fluxo Completo: Cliente

1. **Acesse a home**

   - [ ] Visual está correto

2. **Registre uma nova conta** (cliente)

   - [ ] Cadastro funciona
   - [ ] Redireciona para login com mensagem de sucesso

3. **Faça login**

   - [ ] Redireciona para dashboard do cliente

4. **Navegue pelo dashboard**

   - [ ] Estatísticas corretas
   - [ ] Agendamentos listados (se houver)

5. **Crie um agendamento**

   - [ ] Formulário funciona
   - [ ] Validações funcionam
   - [ ] Salva no banco
   - [ ] Aparece no dashboard

6. **Gerencie agendamentos**

   - [ ] Lista todos os agendamentos
   - [ ] Pode cancelar

7. **Acesse perfil**

   - [ ] Dados aparecem
   - [ ] Pode editar

8. **Faça logout**
   - [ ] Desconecta corretamente

---

## 9. 📊 Relatório de Problemas

Se encontrar problemas, anote aqui:

### Problema 1:

**Descrição:**  
**Página afetada:**  
**Erro (se houver):**  
**Comportamento esperado:**  
**Comportamento observado:**

### Problema 2:

**Descrição:**  
**Página afetada:**  
**Erro (se houver):**  
**Comportamento esperado:**  
**Comportamento observado:**

---

## 10. ✅ Validação Final

Após todos os testes, preencha:

- [ ] Todos os logins funcionam (Cliente, Profissional, Admin)
- [ ] Todos os dashboards exibem dados corretos
- [ ] CSS está carregando em todas as páginas
- [ ] JavaScript está funcionando
- [ ] Não há erros no console do navegador
- [ ] Não há erros PHP visíveis
- [ ] Helpers estão funcionando
- [ ] Estrutura de pastas está correta
- [ ] Arquivos antigos foram removidos
- [ ] Segurança está funcionando (rotas protegidas)

---

## 🎉 Conclusão

✅ **SE TODOS OS TESTES PASSARAM:** A refatoração foi bem-sucedida!  
❌ **SE ALGUM TESTE FALHOU:** Consulte o relatório de problemas e corrija antes de continuar.

---

## 📞 Dúvidas ou Problemas?

Consulte:

1. `RELATORIO_REFATORACAO_MVC.md` - Documentação completa das alterações
2. `REFATORACAO_ASSETS.md` - Instruções sobre movimentação de assets
3. Logs do servidor (se houver)
4. Console do navegador (F12)

---

**Bons testes! 🚀**
