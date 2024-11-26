<!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
<?php
    $h1Title = isset($this->assign['h1Title']) ? $this->assign['h1Title'] : '';
    $resultNo = isset($this->assign['resultNo']) ? $this->assign['resultNo'] : '';
    $resultTxt = isset($this->assign['resultTxt']) ? $this->assign['resultTxt'] : '';
    $recipeName = isset($this->assign['recipeName']) ? $this->assign['recipeName'] : '';
    $resultMsg = isset($this->assign['resultMsg']) ? $this->assign['resultMsg'] : '';
    $linkUrl = isset($this->assign['linkUrl']) ? $this->assign['linkUrl'] : '';
?>

<main>
    <h1><?= $h1Title ?> : <?= $resultTxt ?></h1>
    <hr>

    <table>   
        <?php for ($i = 0; $i < count($recipeName); $i++) { ?>
            <tr>
                <td><?= $recipeName[$i] ?></td>
                <td><?= $resultMsg[$i] ?></td>
            </tr>
        <?php } ?>
    </table>

    <button type="button" onclick="location.href='<?= $linkUrl ?>'">戻る</button>
</main>