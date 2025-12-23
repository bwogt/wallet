# Wallet
Este repositório contém uma implementação para simular uma carteira digital, 
focada em escalabilidade e boas práticas de arquitetura.

## Principais funcionalidades

+ Cadastro e autenticação de usuários;
+ Transferência de fundos entre usuários;
+ Depósitos.

## Tecnologias

+ PHP 8.5 & Laravel 12
+ MySQL (Banco de dados relacional)
+ Docker / Laravel Sail (Ambiente isolado)

## Instalação Local

### Requisitos
- Docker Engine
- Docker Compose

### Passos

1. Clone o Repositório
~~~git
git clone git@github.com:bwogt/wallet.git
~~~

2. Acesse a Pasta do Projeto
~~~bash
cd wallet
~~~

3. Instale as Dependências:

~~~bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs
~~~

4. Copie o arquivo `example.env` e renomeie para `.env`

5. Modifique o `.env` com suas variáveis
~~~bash
DB_USERNAME=sail
DB_PASSWORD=password

TRANSFER_AUTHORIZATION_URL=https://util.devi.tools/api/v2/authorize
SERVICE_NOTIFICATION_URL=https://util.devi.tools/api/v1/notify
~~~

6. Iniciar Ambiente
~~~bash
./vendor/bin/sail up -d
~~~

7. Gere uma APP_KEY
~~~bash
./vendor/bin/sail artisan key:generate
~~~

8. Executar Migrations e Seeders
~~~bash
./vendor/bin/sail artisan migrate
~~~

## Testes
Para executar os testes utilize o seguinte comando
~~~bash
./vendor/bin/sail artisan test
~~~

## Documentação
Para visualizar a documentação acesse
~~~bash
http://localhost/docs/api#/
~~~


