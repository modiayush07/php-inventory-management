<?php
require_once "auth.php";
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $category = trim($_POST["category"]);
    $quantity = (int) $_POST["quantity"];
    $price = (float) $_POST["price"];

    if ($name === "" || $category === "") {
        $error = "Name and category are required.";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO products (name, category, quantity, price)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssid",
            $name,
            $category,
            $quantity,
            $price
        );

        $stmt->execute();

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <h3 class="mb-4">Add Product</h3>

                    <?php if ($error !== "") { ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Product Name</label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>

                            <input
                                type="text"
                                name="category"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>

                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                min="0"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                min="0"
                                step="0.01"
                                required>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary">
                            Save Product
                        </button>

                        <a
                            href="index.php"
                            class="btn btn-secondary">
                            Cancel
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>