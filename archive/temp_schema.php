<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$db = new PDO('sqlite:database/linkhub.sqlite');
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_ASSOC);
echo "tables:\n";
var_dump($tables);
$stmt = $db->query('PRAGMA table_info(lh_payment_requests)');
if (!$stmt) {
    echo 'query failed';
    var_dump($db->errorInfo());
    exit(1);
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "schema:\n";
var_dump($rows);
foreach ($rows as $row) {
    echo $row['name'] . "\n";
}
