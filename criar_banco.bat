@echo off
echo ========================================
echo Criando banco de dados MyBeauty
echo ========================================
echo.

REM Verificar se o MySQL está rodando
echo Verificando se o MySQL esta rodando...
netstat -ano | findstr :3306 >nul
if %errorlevel% neq 0 (
    echo ERRO: MySQL nao esta rodando!
    echo Por favor, inicie o MySQL no painel do XAMPP e tente novamente.
    pause
    exit /b 1
)

echo MySQL esta rodando!
echo.

REM Executar o script SQL
echo Criando banco de dados e tabelas...
"C:\xampp\mysql\bin\mysql.exe" -u root -e "source sql/criar_banco.sql"

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo Banco de dados criado com sucesso!
    echo ========================================
    echo.
    echo Banco: tcc
    echo Tabelas criadas:
    echo - Usuario
    echo - Cliente
    echo - Funcionario
    echo - Servico
    echo - Agendamento
    echo - Agendamento_Servicos
    echo - RecuperacaoSenha
    echo.
) else (
    echo.
    echo ERRO ao criar o banco de dados!
    echo Verifique se o MySQL esta rodando no XAMPP.
    echo.
)

pause

