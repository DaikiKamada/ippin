<!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
<?php
    $h1Title = isset($this->assign['h1Title']) ? $this->assign['h1Title'] : '';
    $resultMsg = isset($this->assign['resultMsg']) ? $this->assign['resultMsg'] : '';
    $linkUrl = isset($this->assign['linkUrl']) ? $this->assign['linkUrl'] : '';
?>

<main>
    <h1 class="ertitle">エラー：<?= $h1Title ?></h1>
    <hr>
    <div class="error_container">
        <p class="erp"><?= $resultMsg ?></p>
        <button class="erb" type="button" onclick="location.href='<?= $linkUrl ?>'">戻る</button>
    </div>
</main>