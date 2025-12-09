# Sistema Clínica Juqueri

![Status](https://img.shields.io/badge/Status-Concluído-success)
![PHP](https://img.shields.io/badge/Backend-PHP-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)

Projeto desenvolvido para a disciplina de **Programação Web II**. O objetivo é criar um sistema web simples para marcar e gerenciar consultas médicas.

---

## Sobre o Projeto

O sistema simula o funcionamento de uma clínica médica. Ele permite que pacientes marquem suas consultas pela internet e que os administradores gerenciem os médicos e a agenda da clínica.

### Funcionalidades

**Para o Paciente:**
* Criar conta e fazer login.
* Agendar consultas escolhendo a especialidade e o médico.
* Ver e cancelar suas próprias consultas.

**Para o Administrador:**
* Cadastrar novos médicos e especialidades.
* Ver todas as consultas agendadas na clínica.

---

## Tecnologias

* **Front-end:** HTML e CSS.
* **Back-end:** PHP.
* **Banco de Dados:** MySQL (XAMPP).

---

## Como Rodar o Projeto

Siga os passos abaixo para instalar no seu computador.

### 1. Instalação
* Instale o **XAMPP**.
* Coloque a pasta `consulta_medica` dentro de `C:\xampp\htdocs\`.

### 2. Iniciar Servidor
* Abra o painel do XAMPP.
* Inicie os serviços **Apache** e **MySQL**.

### 3. Banco de Dados
* Acesse [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
* Importe o arquivo `banco.sql` que está na pasta do projeto.

> **Atenção:** O projeto está configurado para a porta **3307** do MySQL. Se o seu XAMPP usa a porta 3306, altere o arquivo `conexao.php`.

### 4. Acessar
Abra o navegador e entre em:
[http://localhost/CONSULTA_MEDICA](http://localhost/consulta_medica/index.php)

---

## Dados para Teste

Use estes usuários para testar o sistema:

| Tipo | Usuário / Email | Senha |
| :--- | :--- | :--- |
| **Admin** | `admin` | `1234` |
| **Paciente** | `diasdeluta@gmail.com` | `abcd123456` |

---

## Autores

* **Luis Henrique Santana da Silva**
* **Marcos Gabriel Costa de Souza**
* **Ester da Silva Americo**

---

