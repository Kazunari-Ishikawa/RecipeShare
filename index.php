<?php
// 共通関数の読み込み
// require('function.php');

// debugLogStart();

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      href="https://fonts.googleapis.com/css?family=Kosugi+Maru&display=swap&subset=japanese"
      rel="stylesheet"
    />
    <link
      href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
      rel="stylesheet"
    />
    <title>わたしのご飯</title>
  </head>
  <body>
    <!-- ヘッダー -->
    <header>
      <div class="header-container">
        <div class="header-title">
          <img src="img/食事アイコン.png" alt="食事アイコン" />
        </div>
        <nav class="header-nav">
          <ul>
            <li><a href="index.php">トップページ</a></li>
            <li><a href="signup.html">ユーザー登録</a></li>
            <li><a href="login.html">ログイン</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- メイン -->
    <!-- トップ画面 -->
    <section id="ABOUT">
      <div class="top-img">
        <img src="img/top-pic3.jpg" alt="" />
        <div class="top-img-container">
          <h1>わたしのご飯</h1>
          <p>
            作ったご飯を登録しておくことで<br />今日のレシピに迷ったときにさっと検索できます
          </p>
          <div class="top-img-btn">
            <a href="" class="btn btn-sample1">登録する</a>
          </div>
        </div>
      </div>
    </section>

    <!-- コンテンツ -->
    <div class="container">
      <section id="DESCRIPTION">
        <h2>できること</h2>
        <div class="panel-container">
          <div class="panel description">
            <div class="panel-header">
              <h3>写真でかんたん登録</h3>
            </div>
            <div class="panel-main">
              <i class="fas fa-camera icon-middle"></i>
              <div class="panel-txt">
                <p>写真と料理のカテゴリだけでかんたんに登録できます</p>
              </div>
            </div>
          </div>
          <div class="panel description">
            <div class="panel-header">
              <h3>何を食べたか記録にも</h3>
            </div>
            <div class="panel-main">
              <i class="far fa-edit icon-middle"></i>
              <div class="panel-txt">
                <p>料理のカテゴリ、日付などで検索できます</p>
              </div>
            </div>
          </div>
          <div class="panel description">
            <div class="panel-header">
              <h3>レシピを検索できる</h3>
            </div>
            <div class="panel-main">
              <i class="fab fa-google icon-middle"></i>
              <div class="panel-txt">
                <p>レシピ検索ボタンを押すだけでGoogle検索できます</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- フッター -->
    <footer>
      <p>
        &copy Copyright 2020 <a href="">わたしのご飯</a> All rights reserved.
      </p>
    </footer>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>
  </body>
</html>
