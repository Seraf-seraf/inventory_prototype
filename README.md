# Прототип системы инвентаризации

### Настройте env file и выполните миграцию:
```php
php artisan migrate
```

### Заполите базу данных продуктами для теста
```php
php artisan db:seed
```

## Создание документа
endpoint: POST /api/documents/create

пример запроса: 
```json
{
    "type": "income", // [income, expense, inventory]
    "products": [
        {
            "product_id": 4, // [product must exists]
            "quantity": 1000, // min: 0
            "price": 824.1 // min: 0
        },
        {
            "product_id": 5,
            "quantity": 1000,
            "price": 100
        }
    ]
}
```

## Получение всех документов 
endpoint: GET /api/documents/all

пример запроса: http://inventorization-prototype.test/api/documents/all

пример результата:

```json
{
    "data": [
        {
            "id": 19,
            "type": "income",
            "document_items": [
                {
                    "product_id": 4,
                    "price": 5,
                    "quantity": 1000,
                    "remainder": 1000,
                    "remainder_in_money": 5000
                },
                {
                    "product_id": 5,
                    "price": 100,
                    "quantity": 1000,
                    "remainder": 1000,
                    "remainder_in_money": 100000
                }
            ],
            "created_at": "09.09.2024 18:22:50"
        }
    ],
    "links": {
        "first": "http://inventorization-prototype.test/api/documents/all?page=1",
        "last": "http://inventorization-prototype.test/api/documents/all?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://inventorization-prototype.test/api/documents/all?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://inventorization-prototype.test/api/documents/all",
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

## Получение инвентаризаций

endpoint: GET /api/documents/inventories

пример запроса: http://inventorization-prototype.test/api/documents/inventories?page=1&date=09.09.2024

пример результата:

```json
{
    "data": [
        {
            "product_id": 4,
            "quantity": 1001,
            "remainder": 1001,
            "price": 5,
            "error": -1,
            "error_in_money": -5,
            "created_at": "09.09.2024 18:23:42"
        },
        {
            "product_id": 5,
            "quantity": 1001,
            "remainder": 1001,
            "price": 100,
            "error": -1,
            "error_in_money": -100,
            "created_at": "09.09.2024 18:23:42"
        }
    ],
    "links": {
        "first": "http://inventorization-prototype.test/api/documents/inventories?page=1",
        "last": "http://inventorization-prototype.test/api/documents/inventories?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://inventorization-prototype.test/api/documents/inventories?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://inventorization-prototype.test/api/documents/inventories",
        "per_page": 10,
        "to": 2,
        "total": 2
    }
}
```

## Пример ошибок валидации

```json
{
    "message": "Переданные данные некорректны",
    "errors": {
        "products.1.product_id": [
            "Создать документ можно к существующему товару"
        ],
        "products.0.quantity": [
            "Минимальное значение 0"
        ],
        "products.1.quantity": [
            "Минимальное значение 0"
        ]
    }
}
```
