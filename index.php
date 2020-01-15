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
    <title>わたしのご飯</title>
  </head>
  <body>
    <!-- ヘッダー -->
    <header>
      <div class="header-container">
        <div class="header-title">
          <img src="" alt="" />
        </div>
        <nav class="header-nav">
          <ul>
            <li><a href="index.html">トップページ</a></li>
            <li><a href="#">ユーザー登録</a></li>
            <li><a href="#">ログイン</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- メイン -->
    <div class="container">
      <!-- トップ画面 -->
      <section id="ABOUT">
        <div class="about-container">
          <h1>わたしのご飯</h1>
          <p>
            作ったご飯を登録しておくことで<br />今日のレシピに迷ったときにさっと検索できます
          </p>
        </div>
      </section>

      <!-- コンテンツ -->
      <section id="DESCRIPTION">
        <div class="panel-container">
          <div class="panel description">
            <div class="panel-header">
              <h3>写真でかんたん登録</h3>
            </div>
            <div class="panel-main">
              <img src="" alt="" />
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
              <img src="" alt="" />
              <div class="panel-txt">
                <p>料理のカテゴリ、日付などで検索できます</p>
              </div>
            </div>
          </div>
          <div class="panel description">
            <div class="panel-header">
              <h3>忘れたレシピを検索できる</h3>
            </div>
            <div class="panel-main">
              <img src="" alt="" />
              <div class="panel-txt">
                <p>レシピ検索ボタンを押すだけでGoogle検索できます</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- フッター -->
    <footer></footer>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>
  </body>
</html>
