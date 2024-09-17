<?php
require_once('conn.php');

if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT);

    if ($id) {
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM goals WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    header('Location: index.php');
    exit;
}

$result = $conn->query('SELECT * FROM goals')->fetchAll();

$arraySituation = [1 => 'Open', 2 => 'Progress', 3 => "Done"];

require_once('layout/header.php');

?>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-around align-items-center">
        <h1>MY GOALS</h1>
        <a href="register.php" class="btn btn-success">Add</a>
    </div>

    <div class="card-body">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Situation</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($result as $item): ?>
                    <tr>
                        <td><?= $item['description'] ?></td>
                        <td><?= $arraySituation[$item['situation']] ?></td>
                        <td>
                            <a href="register.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Update</a>
                            <button class="btn btn-sm btn-danger" onclick="deleting(<?= $item['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function deleting(id) {
        if (confirm('Delete goal?')) {
            window.location.href = 'index.php?delete=' + id;
        }
    }
</script>

<?php
require_once('layout/footer.php');
?>