# ✈️ TripUAI - Gestão Inteligente de Viagens

O **TripUAI** é uma plataforma de gestão de viagens corporativas que une a hospitalidade mineira à alta tecnologia. Desenvolvido com uma arquitetura sênior, o sistema foca em escalabilidade, processamento assíncrono e uma experiência de usuário fluida em qualquer dispositivo.

## 🏗️ Diferenciais da Arquitetura

O projeto foi desenhado sob o conceito de **Separação de Preocupações (SoC)** e utiliza dois containers distintos para o ecossistema Laravel:

1. **`tripuai-api` (Contexto de Requisição)**: Container otimizado para servir a API REST com baixa latência.
2. **`tripuai-worker` (Contexto de Fila)**: Container dedicado exclusivamente ao processamento de jobs assíncronos via **RabbitMQ**.

- _Por que dois containers?_ Isso garante que tarefas pesadas, como o envio de e-mails em massa (Broadcast), não degradem a performance da API principal enquanto o usuário navega.

---

## 🛠️ Stack Tecnológica

- **Backend:** Laravel 12 (PHP 8.3) + JWT Authentication.
- **Frontend:** Vue 3 (Composition API) + Vite + Tailwind CSS.
- **Mensageria:** RabbitMQ para filas assíncronas.
- **Banco de Dados:** MySQL 8.0.
- **Servidor de E-mail:** MailDev para visualização de e-mails enviados.
- **Infraestrutura:** Docker e Docker Compose.

---

## 🚀 Guia de Instalação (Passo a Passo)

### 1. Clonar o Repositório

```bash
git clone https://github.com/dalcinbh/tripuai.git
cd tripuai

```

### 2. Configurar Variáveis de Ambiente

Copie os arquivos `.env.example` para os oficiais (o Docker, o Laravel e o Vue utilizarão estes arquivos para suas configurações):

```bash
cp .env.example .env
cp api/.env.example api/.env
cp web/.env.example web/.env

```

### 3. Subir a Infraestrutura

O comando abaixo irá buildar as imagens e iniciar todos os serviços (API, DB, RabbitMQ, Web, etc.):

```bash
docker-compose up -d

```

### 4. Preparar o Backend (Primeira execução)

**Importante:**
Dependendo do sistema operacional, você pode precisar executar os comandos abaixo com `sudo`, ou incluir seu usuário no grupo `docker`. Existem muitas soluções para isso, como o `sudo` ou o `docker run --user $(id -u):$(id -g)`, mas o mais simples é incluir seu usuário no grupo `docker`, mas fique a vontade para usar a solução que melhor se adequar ao seu sistema.

Execute os comandos abaixo para instalar as dependências e preparar o banco de dados dentro do container da API:

```bash
# Instalar dependências e gerar chaves
docker exec -it tripuai-api composer install
docker exec -it tripuai-api php artisan key:generate
docker exec -it tripuai-api php artisan jwt:secret

# Executar Migrations e Seeders (Criação de tabelas e usuários padrão)
docker exec -it tripuai-api php artisan migrate --seed

```

Se o PHP (8.3+), o composer, o Sqllite estiverem instalados em sua máquina local, você pode executar os comandos abaixo para instalar as dependências e preparar o banco de dados:

```bash
# Instalar dependências e gerar chaves
php artisan key:generate
php artisan jwt:secret

# Executar Migrations e Seeders (Criação de tabelas e usuários padrão)
php artisan migrate --seed

```

Apos esse passo, reinicie os containers para que as alterações sejam aplicadas:

```bash
docker-compose down
docker-compose up -d
```

**Importante:**
O container do worker por conta de race condition, ele pode não iniciar imediatamente, para resolver isso, reinicie o container manualmente:

```bash
docker-compose restart tripuai-worker
```

Dessa forma, o worker irá iniciar normalmente.

---

## 🔗 Endereços das Ferramentas de Apoio

| Serviço        | Endereço                                                                                                           | Descrição                                   |
| -------------- | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------- |
| **Frontend**   | [http://localhost:5173](https://www.google.com/search?q=http://localhost:5173)                                     | Interface do usuário (Vue 3).               |
| **Swagger UI** | [http://localhost:8000/api/documentation](https://www.google.com/search?q=http://localhost:8000/api/documentation) | Documentação interativa da API.             |
| **MailDev**    | [http://localhost:1080](https://www.google.com/search?q=http://localhost:1080)                                     | Interface para visualizar e-mails enviados. |
| **phpMyAdmin** | [http://localhost:8081](https://www.google.com/search?q=http://localhost:8081)                                     | Gestão visual do banco de dados MySQL.      |
| **RabbitMQ**   | [http://localhost:15672](https://www.google.com/search?q=http://localhost:15672)                                   | Dashboard de filas (guest / guest).         |

---

## 👤 Credenciais de Acesso (Seeders)

- **Administrador:** `admin@tripuai.com.br` | Senha: `password`
- **Colaborador:** `user@tripuai.com.br` | Senha: `password`

---

## 🗺️ Roadmap de Desenvolvimento

O projeto foi construído seguindo um planejamento rigoroso de engenharia:

- **Fase 1: Infraestrutura**: Configuração Docker e isolamento de ambientes.
- **Fase 2: Segurança**: Autenticação JWT e Policies de autorização.
- **Fase 3: Negócio**: Implementação de Service Layer, Repository Pattern e API Resources para garantir SOLID.
- **Fase 4: Frontend**: Desenvolvimento Mobile-First com Composables no Vue 3.
- **Fase 5: Mensageria**: Micro-serviço de Broadcast Email utilizando RabbitMQ.

---
