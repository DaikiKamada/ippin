<?php $vAry = $_SESSION['viewAry']; ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php require_once "headAdmin.php"; ?><?php echo "\n"; ?>
</head>

<body id="<?php echo $vAry['bodyId']; ?>">
    <?php require_once "headerAdmin.php"; ?>
    <?php echo "\n"; ?>
    <?php require_once 'v_' . $vAry['main'] . '.php'; ?>
    <?php echo "\n"; ?>
    <button id="scrollTopBtn" onclick="scrollToTop()" title="トップに戻る">∧ TOP</button>
    <script src="js/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>