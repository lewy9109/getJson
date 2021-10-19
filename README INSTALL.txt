1. configure .env to connect database
2. create database in /create.sql
3. run php bin/console make:migration
4. run php bin/console doctrine:migrations:migrate
5. run CLI php bin/console app:download-users