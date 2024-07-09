# Doctrine demo

Репа для тестирования функций "чистой" doctrine

Можно создавать различные конфигурации и случаи с смотреть как лучше сделать функционал

Сейчас в качестве БД используется MySQL, планирую подключить PostgreSQL
## Разворачивание с использованием DOCKER

* Запуск docker контейнеров: ```docker-compose up -d --build```

* Установка PHP зависимостей композером
```docker exec -it demo-doctrine-php composer install```

* Создание схемы
``` docker exec -it demo-doctrine-php php bin/doctrine orm:schema-tool:create```

* Наполнение базы фикстурами
``` docker exec -it demo-doctrine-php php bin/console app:fixture-load```

## Использование

* Войти в консоль
``` docker exec -it demo-doctrine-php bash```

* Список консольных команд (внутри контейнера)
``` php bin/console ```

## Остановка проекта
* Остановка docker контейнеров: ```docker-compose down --remove-orphans```
