<?php
require_once('conn.php');

$id = 0;
$description = '';
$situation = 1;

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    if (!$id) {
        header('Location: index.php');
        exit;
    }

    $stm = $conn->prepare("SELECT * FROM goals WHERE id=:id");

    $stm->bindValue('id', $id);
    $stm->execute();
    $result = $stm->fetch();

    if (!$result) {
        header('Location: index.php');
        exit;
    }

    $description = $result['description'];
    $situation = $result['situation'];
}

if (isset($_POST['id'])) {
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $situation = filter_input(INPUT_POST, "situation", FILTER_SANITIZE_NUMBER_INT);

    if (!$id) {
        $smt = $conn->prepare("INSERT INTO goals (description, situation) VALUES (:description, :situation)");
    } else {
        $smt = $conn->prepare("UPDATE goals SET description=:description, situation=:situation WHERE id=:id");
        $smt->bindValue(':id', $id);
    }
    $smt->bindValue(':description', $description);
    $smt->bindValue(':situation', $situation);
    $smt->execute();

    header('Location: index.php');
    exit;
}

include_once('layout/header.php');
?>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1><?= $id ? 'UPDATE GOAL ' . $id : 'ADD GOAL' ?></h1>
    </div>

    <!-- Não há action. Ele retorna para a mesma página. -->
    <form method="post" autocomplete="off">
        <div class="card-body">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="form-group">
                <label for="description">
                    Description
                </label>
                <input type="text" class="form-control" id="description" name="description" value="<?= $description; ?>" required>
            </div>

            <div class="form-group">
                <label for="situation">
                    Situation
                </label>

                <select class="form-select" id="situation" name="situation">
                    <option value="1" <?= $situation == 1 ? 'selected' : '' ?>>Open</option>
                    <option value="2" <?= $situation == 2 ? 'selected' : '' ?>>Progress</option>
                    <option value="3" <?= $situation == 3 ? 'selected' : '' ?>>Done</option>
                </select>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="index.php" class="btn btn-primary">Return</a>
        </div>
    </form>
</div>


<?php
include_once('layout/footer.php')
?>