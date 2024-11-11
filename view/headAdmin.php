<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- タイトルの呼び出し -->
<title><?php echo $vAry['title'];?></title>

<!-- jQuaryのCSS読み込み -->
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>

<!-- multipleの読み込み -->
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>

<!-- ブートストラップのCSS読み込み -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

<!--  jQueryのCSS読み込み -->
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">

<!-- オリジナルCSSの読み込み（呼び出し） -->
<link rel="stylesheet" href="<?php echo $vAry['cssPath'];?>">