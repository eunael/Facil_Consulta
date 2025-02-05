# Backlog

- [x] Criar projeto
- [x] Instalar sail
- [x] Configurar Mysql
- [x] Configurar Scramble (gerar docs api)
- [x] Create a Admin user
- [x] Configurar JWT com o tymon/jwt-auth
    - [x] Configurar guard no AppServiceProvider
    - [x] Criar rota /login
    - [x] Criar rota /user
    - [x] Criar middleware para validar o token
- [ ] Criar Models, Migrations, Seeders e Factories
- [ ] Criar rotas
- [ ] Criar controler (--invokable)

# Comandos

composer install

artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

artisan jwt:secret

artisan db:seed

artisan test
