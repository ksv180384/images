<?php
namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        version: "1.0.0",
        description: "API для работы с галереей",
        title: "Галерея",
//        termsOfService: "http://example.com/terms/",
//        contact: new OA\Contact(email: "support@example.com"),
//        license: new OA\License(name: "MIT", url: "https://opensource.org/licenses/MIT"),
    ),
    servers: [
        new OA\Server(url: "http://localhost:7031/api/v1", description: "Локальный сервер"),
        new OA\Server(url: "http://test.ru/api/v1", description: "Локальный сервер"),
    ],
    tags: [
        new OA\Tag(name: "Images", description: "Работа с изображениями"),
        new OA\Tag(name: "Categories", description: "Категории изображений"),
    ]
)]
class APIDocs {}
