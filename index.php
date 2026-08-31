<?php
require_once "auth.php";
require_once "config.php";

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";

if ($search !== "") {
    $stmt = $conn->prepare(
        "SELECT * FROM products
         WHERE name LIKE ? OR category LIKE ?
         ORDER BY id DESC"
    );

    $searchTerm = "%" . $search . "%";

    $stmt->bind_param(
        "ss",
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query(
        "SELECT * FROM products ORDER BY id DESC"
    );
}

$statsResult = $conn->query("
    SELECT
        COUNT(*) AS total_products,
        COALESCE(SUM(quantity), 0) AS total_stock,
        COALESCE(SUM(quantity * price), 0) AS inventory_value,
        SUM(CASE WHEN quantity < 10 THEN 1 ELSE 0 END) AS low_stock
    FROM products
");

$stats = $statsResult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Inventory Management</h2>

        <a href="add-product.php" class="btn btn-primary">
            Add Product
        </a>
        <a href="logout.php" class="btn btn-outline-danger">
    Logout
</a>

    </div>

    <div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Products</small>
                <h3><?= $stats["total_products"] ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Stock</small>
                <h3><?= $stats["total_stock"] ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Inventory Value</small>
                <h3>
                    ₹<?= number_format($stats["inventory_value"], 2) ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Low Stock</small>
                <h3><?= $stats["low_stock"] ?></h3>
            </div>
        </div>
    </div>

</div>

    <form method="GET" class="mb-4">
    <div class="input-group">

        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search by product or category..."
            value="<?= htmlspecialchars($search) ?>">

        <button
            class="btn btn-dark"
            type="submit">
            Search
        </button>

        <a
            href="index.php"
            class="btn btn-secondary">
            Reset
        </a>

    </div>
</form>

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($product = $result->fetch_assoc()) { ?>

                 <tr class="<?= $product["quantity"] < 10 ? "table-danger" : "" ?>">

                        <td><?= $product["id"] ?></td>

                        <td><?= htmlspecialchars($product["name"]) ?></td>

                        <td><?= htmlspecialchars($product["category"]) ?></td>

                        <td><?= $product["quantity"] ?></td>

                        <td>₹<?= $product["price"] ?></td>

                        <td><?= $product["created_at"] ?></td>

                        <td>
                            <a
                                href="edit-product.php?id=<?= $product["id"] ?>"
                                class="btn btn-sm btn-warning">
                                Edit
                            </a>

                          <form
    action="delete-product.php"
    method="POST"
    class="d-inline"
    onsubmit="return confirm('Are you sure you want to delete this product?')"
>
    <input
        type="hidden"
        name="id"
        value="<?= $product["id"] ?>"
    >

    <button
        type="submit"
        class="btn btn-sm btn-danger"
    >
        Delete
    </button>
</form>
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>