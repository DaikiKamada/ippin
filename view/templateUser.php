<?php $vAry = $_SESSION['viewAry']; ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php require_once "headUser.php"; ?><?php echo "\n"; ?>
</head>

<body id="<?php echo $vAry['bodyId']; ?>">
    <?php require_once "headerUser.php"; ?>
    <?php echo "\n"; ?>
    <?php require_once 'v_' . $vAry['main'] . '.php'; ?>
    <?php echo "\n"; ?>
    <?php require_once "footerUser.php"; ?>
    <?php echo "\n"; ?>
    <button id="scrollTopBtn" onclick="scrollToTop()" title="トップに戻る">∧ TOP</button>
    <script src="js/user.js"></script>
</body>

</html>