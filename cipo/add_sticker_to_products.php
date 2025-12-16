<?php
// Подключение к базе данных OCStore
require_once('config.php');

// Настройки подключения к БД
$db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($db->connect_error) {
    die("Ошибка подключения к базе данных: " . $db->connect_error);
}

$db->set_charset("utf8");

// Префикс таблиц
$prefix = DB_PREFIX;

// ID магазина (обычно 1, если не мультимагазин)
$store_id = 1;

// Данные стикера для добавления
$sticker_key = "stickers_dostavka-v-moskvu-5-15-dnei";
$sticker_data = [
    "title" => "Доставка в Москву 5-15 дней",
    "description" => "Доставка в Москву 5-15 дней",
    "image" => false,
    "sort" => 1
];

// Получаем все product_id из таблицы товаров
$query = "SELECT product_id FROM {$prefix}product";
$result = $db->query($query);

if (!$result) {
    die("Ошибка запроса: " . $db->error);
}

$processed = 0;
$created = 0;
$updated = 0;

while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];

    // Проверяем, есть ли запись в таблице стикеров
    $check_query = "SELECT product_id, stickers_json FROM {$prefix}oct_product_stickers WHERE product_id = {$product_id}";
    $check_result = $db->query($check_query);

    if ($check_result->num_rows == 0) {
        // Записи нет - создаём новую
        $new_json = json_encode([
            (string)$store_id => [
                $sticker_key => $sticker_data
            ]
        ], JSON_UNESCAPED_UNICODE);

        $insert_query = "INSERT INTO {$prefix}oct_product_stickers (product_id, stickers_json) VALUES ({$product_id}, '" . $db->real_escape_string($new_json) . "')";

        if ($db->query($insert_query)) {
            $created++;
        }
    } else {
        // Запись существует - проверяем JSON
        $existing = $check_result->fetch_assoc();
        $json_data = json_decode($existing['stickers_json'], true);

        // Проверяем, есть ли наш стикер
        $sticker_exists = false;
        if (isset($json_data[$store_id]) && isset($json_data[$store_id][$sticker_key])) {
            $sticker_exists = true;
        }

        if (!$sticker_exists) {
            // Добавляем стикер в существующий JSON
            if (!isset($json_data[$store_id])) {
                $json_data[$store_id] = [];
            }

            $json_data[$store_id][$sticker_key] = $sticker_data;

            $updated_json = json_encode($json_data, JSON_UNESCAPED_UNICODE);

            $update_query = "UPDATE {$prefix}oct_product_stickers SET stickers_json = '" . $db->real_escape_string($updated_json) . "' WHERE product_id = {$product_id}";

            if ($db->query($update_query)) {
                $updated++;
            }
        }
    }

    $processed++;
}

$db->close();

echo "Обработка завершена!\n";
echo "Всего товаров обработано: {$processed}\n";
echo "Создано новых записей: {$created}\n";
echo "Обновлено существующих записей: {$updated}\n";
