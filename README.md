
# Instruções para uso da aplicação apos fazer `git clone`

1. Copie o arquivo .env.example para .env usando o comando
   
```sh
cp .env.example .env
```

2. Levante os containers docker usando 

```sh
docker-compose up -d --build
```
3. Em seguida entre no container da aplicação 

```sh
docker exec -it app bash
```
4. Agora dentro dele vamos em busca de nossas dependências 

```sh
composer install
```
5. Podemos agora popular o banco usando 

```sh
php artisan migrate:fresh --seed
```
6. Para testar se nossa API está funcioando como esperado execute os testes

```sh
php artisan test
```   



