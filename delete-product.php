<?php

require_once "auth.php";
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

if (!isset($_POST["id"])) {
    die("Product ID is missing.");
}

$id = (int) $_POST["id"];

$stmt = $conn->prepare(
    "DELETE FROM products WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;