<?php
include("includes/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get form inputs
$searchTerm = $_GET['search'] ?? '';
$authorId = $_GET['author'] ?? '';
$categoryId = $_GET['category'] ?? '';

// Fetch categories and authors
$categories = $dbh->query("SELECT id, CategoryName FROM tblcategory WHERE Status = 1 ORDER BY CategoryName")->fetchAll(PDO::FETCH_ASSOC);
$authors = $dbh->query("SELECT id, AuthorName FROM tblauthors ORDER BY AuthorName")->fetchAll(PDO::FETCH_ASSOC);

// Build search query
$sql = "
    SELECT 
        b.BookName,
        b.ISBNNumber,
        b.BookPrice,
        c.CategoryName,
        a.AuthorName,
        b.RegDate
    FROM tblbooks b
    JOIN tblcategory c ON b.CatId = c.id
    JOIN tblauthors a ON b.AuthorId = a.id
    WHERE 1=1
";

$params = [];

if (!empty($searchTerm)) {
    $sql .= " AND b.BookName LIKE :search";
    $params['search'] = "%$searchTerm%";
}
if (!empty($authorId)) {
    $sql .= " AND b.AuthorId = :author";
    $params['author'] = $authorId;
}
if (!empty($categoryId)) {
    $sql .= " AND b.CatId = :category";
    $params['category'] = $categoryId;
}

$stmt = $dbh->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Book Search</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        input, select, button {
            padding: 8px;
            font-size: 14px;
        }
        button {
            background: #007bff;
            color: #fff;
            border: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Search Books</h2>

    <form method="get" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="text" name="search" placeholder="Search by title" value="<?= htmlspecialchars($searchTerm) ?>">
        
        <select name="author">
            <option value="">All Authors</option>
            <?php foreach ($authors as $author): ?>
                <option value="<?= $author['id'] ?>" <?= $authorId == $author['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($author['AuthorName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= $categoryId == $category['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['CategoryName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Search</button>
    </form>

    <?php if (count($results) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>ISBN</th>
                    <th>Price</th>
                    <th>Registered On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['BookName']) ?></td>
                        <td><?= htmlspecialchars($book['AuthorName']) ?></td>
                        <td><?= htmlspecialchars($book['CategoryName']) ?></td>
                        <td><?= htmlspecialchars($book['ISBNNumber']) ?></td>
                        <td><?= htmlspecialchars($book['BookPrice']) ?></td>
                        <td><?= htmlspecialchars($book['RegDate']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
</div>

</body>
</html>
