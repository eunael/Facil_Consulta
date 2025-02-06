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
    - [x] Cities
    - [x] Doctors
    - [ ] Patients
    - [ ] Consultations
- [ ] Criar rotas
    - [x] GET /cities?name=
    - [x] GET /medicos?name=
    - [x] GET /cidades/{id_cidade}/medicos?name=
    - [ ] POST AUTH /pacientes
    - [ ] POST AUTH /pacientes/{id_paciente}
    - [ ] POST AUTH /medicos/consulta
    - [ ] GET AUTH /medicos/{id_medico}/pacientes?apenas-agendadas=&name=
- [ ] Criar controler (--invokable)

# Comandos

composer install

artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

artisan jwt:secret

artisan migrate --seed

artisan test
