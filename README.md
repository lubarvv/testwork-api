testwork-api
============
Тестовое задание. АПИ сделано именно под ТЗ, описанное ниже, поэтому много чего не учтено.

Развертывание проекта
============
 1. Клонировать репозиторий
 2. Настроить виртуалхост с DocumentRoot в директории api
 3. Прописать в Comfig/db.php данные для соединения с mysql-сервером
 4. Выполнить sql-запросы из файла db.sql

ТЗ
============
Задача — написать простейшее АПИ для каталога товаров. Будут оцениваться архитектура и грамотное применение ООП. Приложение должно быть полностью самописным, использование фреймворков и библиотек не разрешается.
Приложение должно содержать:

 1. Категории товаров 
 2. Конкретные товары, которые принадлежат к какой то категории
 3. Возможность авторизации пользователя

Категория представляет из себя одноуровневый список. Товары привязываются к категории по ID категории.

Возможные действия:

 1. Получение списка всех категорий
 2. Получение списка товаров в конкретной категории
 3. Добавление категории
 4. Добавление товара

Результаты запросов должны быть представлены в формате JSON.
Результат должен быть выложен на github, при этом кроме самого когда приложения содержать еще и данные mysql запросов для создания базы данных. 
Желательно, что бы код был документирован (как минимум phpDocBlock)

Документация по апи
==========

Общая информация
----------
На все запросы сервер отправляет ответ в виде json, с Content-Type application/json. В каждом ответе есть поле result, содержащее одно из вдух значений:
 1. ok - запрос выполнен успешно
 2. error - при выполнении запроса произошла ошибка. В данном случае будет поле message с описанием ошибки.

Запросы можно отправлять как POST, так и GET методом (сделано, чтобы удобно было использовать прямо в браузере, подставляя данные в адресную строку).

Для выполнения запросов к методам апи нужна авторизация. При успешной авторизации будет выдн токен, который необходимо будет использовать для запросов к апи.

Методы апи
----------
**Авторизация**

    Url:
        auth.php
    
    In:
        email
        password
    
    Out:
        token
    
    Response example:
        {
            "result":"ok",
            "token":"u5v4UL2dhFvp267h8KHejd0ODHvJse1WuvYVhIpHxJ9vVyC48Ayn8huZtDPmMAwu"
        }

**Добавление пользователя**

    Url: 
        adduser.php
    
    In:
        email
        password
    
    Out:
        id
    
    Response example:
        {
            "result":"ok",
            "id":3
        }
    
**Получение списка категорий**

    Url:
        getcategories.php
        
    In:
        token
        
    Out:
        categories - массив с категориями
        
    Response example:
        {
            "result":"ok",
            "categories":[
                {
                    "id":"1",
                    "name":"Category 1"
                }, 
                {
                    "id":"2",
                    "name":"Category 2"
                }, 
                {
                    "id":"3",
                    "name":"Category 3"
                }
            ]
        }

**Получение категории**

    Url:
        getcategory.php

    In:
        token
        id

    Out:
        id
        name

    Response example
        {
            "result":"ok",
            "id":"2",
            "name":"Category 2"
        }

**Добавление категории**

    Url:
        addcategory.php

    In:
        token
        name

    Out:
        id

    Response example
        {
            "result":"ok",
            "id":5
        }

**Получение товара**

    Url:
        getproduct.php

    In:
        token
        id

    Out:
        id
        name
        description
        cost

    Response example:
        {
            "result":"ok",
            "id":"1",
            "category_id":"1",
            "name":"test product 1",
            "description":"product description",
            "cost":"100500"
        }

**Получение товаров в категории**

    Url:
        getproductsbycategory.php

    In:
        token
        category_id

    Out:
        products - массив (товары в категории)

    Response example:
        {
            "result":"ok",
            "products":[
                {
                    "id":"1",
                    "category_id":"1",
                    "name":"test product 1",
                    "description":"product description",
                    "cost":"100500"
                },
                {
                    "id":"2",
                    "category_id":"1",
                    "name":"test product 2",
                    "description":"product description",
                    "cost":"200600"
                },
                {
                    "id":"5",
                    "category_id":"1",
                    "name":"test",
                    "description":"description",
                    "cost":"12345"
                },
            ]
        }

**Добавление товара**

    Url:
        addproduct.php

    In:
        token
        category_id
        name
        description
        cost

    Out:
        id

    Response example:
        {
            "result":"ok",
            "id":3
        }