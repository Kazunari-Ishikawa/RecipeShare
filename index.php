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
    <title>みんなのご飯</title>
  </head>
  <body>
    <!-- ヘッダー -->
    <header>
      <div class="header-container">
        <div class="header-tiitle">
          <h1>みんなのご飯</h1>
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
    <main>
      <!-- ABOUT -->
      <section id="ABOUT">
        <div class="about-container">
          <h2>ABOUT</h2>
          <p>
            ここはみんなが作ったご飯を共有するサイトです<br />今日のレシピに迷ったらヒントをもらいましょう
          </p>
        </div>
      </section>

      <!-- サイドバー -->
      <section id="side-bar">
        <div class="side-bar">
          <form action="" method="post">
            <p>投稿者</p>
            <input type="text" name="username" value="" />
            <p>カテゴリ</p>
            <select name="category" id="">
              <option value="0">選択してください</option>
            </select>
            <input type="submit" value="検索" />
          </form>
        </div>
      </section>

      <!-- メインコンテンツ -->
      <section id="contents">
        <div class="contents-container">
          <div class="panel-container">
            <div class="panel">
              <img src="" alt="" />
              <p>投稿者</p>
              <p>日付</p>
              <p>タイトル</p>
            </div>
            <div class="panel">
              <img src="" alt="" />
              <p>投稿者</p>
              <p>日付</p>
              <p>タイトル</p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <footer></footer>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>
  </body>
</html>
