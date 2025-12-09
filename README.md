# Sistema de Gestão Clínica - Clínica Juqueri

![Status](https://img.shields.io/badge/Status-Concluído-success)
![PHP](https://img.shields.io/badge/Backend-PHP-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)

> Projeto acadêmico desenvolvido para a disciplina de **Programação Web II**. O objetivo é simular o ecossistema de agendamento e gerenciamento de consultas médicas em ambiente web.

---

## Sobre o Projeto

O **Clínica Juqueri** é uma aplicação web full-stack que digitaliza o fluxo de atendimento de uma clínica médica. A solução foca na usabilidade para o paciente e no controle administrativo para a gestão da clínica, implementando conceitos fundamentais de desenvolvimento web como CRUD, controle de sessão e segurança de dados.

### Funcionalidades Principais

#### Módulo do Paciente
* **Autenticação Segura:** Cadastro e login de usuários.
* **Agendamento Intuitivo:** Seleção de especialidade e médico disponível.
* **Gestão de Agendamentos:** Visualização de histórico e cancelamento de consultas futuras.

#### Módulo Administrativo
* **Gestão de Corpo Clínico:** Cadastro e remoção de médicos.
* **Catálogo de Especialidades:** Adição de novas áreas médicas.
* **Painel de Controle:** Visão geral de todas as consultas agendadas na clínica.

---

## Tecnologias Utilizadas

* **Front-end:** HTML5, CSS3 (Design Responsivo).
* **Back-end:** PHP (Procedural/Orientado a Objetos).
* **Banco de Dados:** MySQL.
* **Ambiente de Desenvolvimento:** XAMPP.

---

## Instalação e Configuração

Siga os passos abaixo para executar o projeto em seu ambiente local.

### 1. Pré-requisitos
* XAMPP instalado (Apache + MySQL).

### 2. Configuração do Diretório
Clone ou extraia a pasta do projeto `CONSULTA_MEDICA` para o diretório raiz do servidor:
`C:\xampp\htdocs\`

### 3. Inicialização
Abra o **XAMPP Control Panel** e inicie os serviços:
* Apache
* MySQL

### 4. Configuração do Banco de Dados
1.  Acesse o **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2.  Crie um novo banco de dados (caso não exista) ou utilize a aba **Importar**.
3.  Selecione o arquivo `banco.sql` localizado na raiz do projeto.
4.  Execute a importação.

> **NOTA IMPORTANTE SOBRE CONEXÃO**
>
> Este projeto foi configurado nativamente para rodar com o MySQL na porta **3307** para evitar conflitos comuns no XAMPP.
>
> * **Se o seu MySQL roda na porta 3307:** Nenhuma ação necessária.
> * **Se o seu MySQL roda na porta padrão (3306):** Abra o arquivo `conexao.php` e altere a variável `$port` para `3306` ou remova o parâmetro de porta na conexão.

---

## Credenciais para Teste

Utilize os dados abaixo para validar as funcionalidades do sistema:

| Perfil | Email / Usuário | Senha |
| :--- | :--- | :--- |
| **Administrador** | `admin` | `1234` |
| **Paciente** | `diasdeluta@gmail.com` | `abcd123456` |

---

## Autores

* **Luis Henrique Santana da Silva**
* **Marcos Gabriel Costa de Souza**
* **Ester da Silva Americo**

---

## Licença e Observações

Este projeto foi desenvolvido exclusivamente para fins educacionais. O código prioriza a clareza didática para demonstração de conceitos de desenvolvimento web.

&copy; 2025 Clínica Juqueri - Todos os direitos reservados.