# Teste técnico Fácil consulta - Natanael Alves

## Documentação da API

Acesse a rota `127.0.0.1/docs/api`.

A documentação foi feita com ajuda do pacote scramble.

# Comandos

composer install

cp .env.example .env

./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

./vendor/bin/sail artisan jwt:secret

./vendor/bin/sail artisan migrate --seed

touch database/database.sqlite

./vendor/bin/sail artisan test
