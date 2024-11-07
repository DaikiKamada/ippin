    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- タイトルの呼び出し -->
    <title><?php echo $vAry['title'];?></title>

    <!-- ブートストラップのCSS読み込み -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- オリジナルCSSの読み込み（呼び出し） -->
    <link rel="stylesheet" href="<?php echo $vAry['cssPath'];?>">
    
    <!-- ファビコンの読み込み -->
    <link rel="icon" href="images/favicon.ico">
    
    <!-- フォントの読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@300;400;500;700;900&display=swap" rel="stylesheet">