<main>
    <section class="main_visual">
        <img class="mv_img" src="images/keyvisual_white.png" alt="メインビジュアル">
    </section>

    <section class="foods_select">
        <form action="ippinResult.php" method="post" id="selectionForm" class="selection_form">
            <div class="container text-center">
                <h2 class="foods_select_title">食材を選択</h2>
                <p class="foods_select_text">3つまで選択できます</p>
            </div>

            <div class="container-fluid">
                <div class="row g-4 d-flex justify-content-center">
                    <?php
                        // $vAry[]にfoodsListがあれば$foodsListに配列を渡す、なければ空の配列を生成
                        if (isset($vAry['foodsList'])) {
                            $foodsList = $vAry['foodsList'];
                        } else {
                            $foodsList = [];
                        }
                    ?>
                    <?php foreach ($foodsList as $f) { ?>
                        <div class="col-6 col-md-4 col-lg-3 btn btn-flat">
                            <input type="checkbox" id="foods<?= $f['foodId'] ?>" name="foodsSelect[]" value="<?= $f['foodId'] ?>:<?= $f['foodName'] ?>">
                            <span class="foods_button_container">
                                <label for="foods<?= $f['foodId'] ?>" class="foods_button w-100 text-center"><?= $f['foodName'] ?></label>
                            </span>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="submit_btn_box text-center">
                <div class="btn btn-3d-flip btn-3d-flip2">
                    <span class="btn-3d-flip-box2">
                        <span class="btn-3d-flip-box-face btn-3d-flip-box-face--front2">この食材をつかう！<i class="fas fa-angle-right fa-position-right"></i></span>
                        <span class="btn-3d-flip-box-face  btn-3d-flip-box-face--back2">
                        <input type="submit" value="作れるippinを探す！" class="ippin_search_btn"><i class="fas fa-angle-right fa-position-right"></i></span>
                    </span>
                </div>
            </div>
        </form>
    </section>
</main>