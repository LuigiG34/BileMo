# Bilemo API REST - JWT

[![SymfonyInsight](https://insight.symfony.com/projects/eaee9d84-8d95-4e95-b20c-576fa694d143/big.svg)](https://insight.symfony.com/projects/eaee9d84-8d95-4e95-b20c-576fa694d143)

### 1. Requirements
1. Composer
2. WAMPServer
3. Insomnia or Postman

### 2. Installation
1. Create a Virtual Host
2. Configurate the ".env" file so that it corresponds to your environnement (I used WAMPServer)
3. Install dependencies : ```composer install```


4. Create the database : ```php bin/console doctrine:database:create```
5. Create a migration : ```php bin/console make:migration```
6. Update the database structure : ```php bin/console doctrine:make:migration```
7. Upload the data fixtures to the database : ```php bin/console doctrine:fixtures:load```


8. Create a folder ```/config/jwt```
9. Generate a private key for the JWT* : ```openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096```
10. Generate a public key for the JWT* : ```openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout```
11. Replace ```JWT_PASSPHRASE```, ```JWT_SECRET_KEY```, ```JWT_PUBLIC_KEY``` value in the ".env" file by the password you just entered for the generated keys, and by the paths to the secret and public key

**You can execute these commands in git bash terminal if you don't have openssl installed*

### 3. Usage/Access
- You can find 3 users with there credentials in the ```credentials.txt``` provided
- Link to documentation : ```{your-virtual-host}/api/doc```
