<?php $vAry = $_SESSION['viewAry']; ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php include "headAdmin.php"; ?><?php echo "\n"; ?>
</head>

<body id="<?php echo $vAry['body_id']; ?>">
    <?php include "headerAdmin.php"; ?>
    <?php echo "\n"; ?>
    <?php include $vAry['main'] . '.php'; ?>
    <?php echo "\n"; ?>
    <button onclick="scrollToTop()" id="scrollTopBtn" title="トップに戻る">∧ TOP</button>
    <script src="js/admin.js"></script>
</body>

</html>