<?php

$pdo = new PDO(
    'mysql:host=mysql;dbname=' . getenv('MYSQL_DATABASE'),
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD')
);

echo 'PHP + Nginx + MySQL работает';