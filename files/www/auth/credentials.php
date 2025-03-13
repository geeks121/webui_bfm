<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    header('Location: /');
    exit;
}
return [
    'username' => 'admin',
    'hashed_password' => '$2y$10$Klcmonjcf0sWXeAWypcHdu5cCI63twKBCobakZMWqFs7drcuwD4VW',
];
