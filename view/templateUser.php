<?php $vAry = $_SESSION['viewAry']; ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php include "headUser.php"; ?><?php echo "\n"; ?>
</head>

<body id="<?php echo $vAry['body_id']; ?>">
    <?php include "headerUser.php"; ?>
    <?php echo "\n"; ?>
    <?php include $vAry['main'] . '.php'; ?>
    <?php echo "\n"; ?>
    <?php include "footerUser.php"; ?>
    <?php echo "\n"; ?>
    <button id="scrollTopBtn" onclick="scrollToTop()" title="トップに戻る">∧ TOP</button>
    <script src="js/user.js"></script>
</body>

</html>