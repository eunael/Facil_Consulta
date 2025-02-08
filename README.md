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
- [x] Criar Models, Migrations, Seeders e Factories
    - [x] Cities
    - [x] Doctors
    - [x] Patients
    - [x] Consultations
- [x] Criar rotas
    - [x] GET /cities?name=
    - [x] GET /medicos?name=
    - [x] POST AUTH /medicos
    - [x] GET /cidades/{id_cidade}/medicos?name=
    - [x] POST AUTH /pacientes
    - [x] POST AUTH /pacientes/{id_paciente}
    - [x] POST AUTH /medicos/consulta
    - [x] GET AUTH /medicos/{id_medico}/pacientes?apenas-agendadas=&name=

# Comandos

composer install

artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

artisan jwt:secret

artisan migrate --seed

artisan test
