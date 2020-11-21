* Lembre-se de instalar o composer na sua máquina.

* Ao instalar, entre na pasta do projeto, e rode o comando composer install, caso precise atualizar, composer update.

* Lembre-se de trocar a conexão com o banco de dados em Config/config.php

# BANCO DE DADOS
    CREATE TABLE `backend`.`users` 
    ( 
        `id` INT NOT NULL AUTO_INCREMENT, 
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        `updated_at` TIMESTAMP NULL, 
        `name` VARCHAR(255) NOT NULL, 
        `email` VARCHAR(255) NOT NULL, 
        `password` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;

    CREATE TABLE `backend`.`subjects` 
    ( 
        `id` INT NOT NULL AUTO_INCREMENT, 
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        `updated_at` TIMESTAMP NULL, 
        `name` VARCHAR(255) NOT NULL, 
        `teacher_name` VARCHAR(255) NOT NULL, 
        `teacher_email` VARCHAR(255) NOT NULL,
        `id_user` INT NOT NULL, 
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;

    CREATE TABLE `backend`.`memories_of_class` 
    ( 
        `id` INT NOT NULL AUTO_INCREMENT, 
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NULL, 
        `id_subject` INT NOT NULL, 
        `id_user` INT NOT NULL, 
        `taught_content` TEXT NOT NULL, 
        `observations` TEXT NULL, 
        `shared` TINYINT NOT NULL DEFAULT '0', 
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;

    CREATE TABLE `backend`.`interations_of_user` 
    ( 
        `id` INT NOT NULL AUTO_INCREMENT, 
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        `id_memory` INT NOT NULL, 
        `interation` TEXT NOT NULL, 
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;
