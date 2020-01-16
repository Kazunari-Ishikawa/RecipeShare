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
            <li><a href="mypage.php">マイページ</a></li>
            <li><a href="favorite.html">お気に入り</a></li>
            <li><a href="profEdit.html">個人設定</a></li>
            <li><a href="logout.php">ログアウト</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- メイン -->
    <main id="mypage" class="layout-2-column">
      <h1 class="page-title">マイページ</h1>
      <div class="regist-container">
        <p><a href="registProduct.html">新しく料理を登録する</a></p>
      </div>
      <!-- サイドバー -->
      <section id="side-bar" class="layout-2-column-left">
        <div class="search-bar">
          <form action="" method="post">
            <p>カテゴリ</p>
            <select name="category" id="">
              <option value="0">選択してください</option>
            </select>
            <p>日付</p>
            <div class="btn-container">
              <input type="submit" value="検索" class="btn btn-center" />
            </div>
          </form>
        </div>
      </section>

      <!-- メインコンテンツ -->
      <section id="contents" class="layout-2-column-right">
        <div class="contents-container">
          <div class="card-container">
            <div class="card">
              <a href="productDetail.html">
                <img src="sample/IMG_20200102_194326.jpg" alt="" />
                <p>日付</p>
                <p>タイトル</p>
              </a>
            </div>
            <div class="card">
              <a href="productDetail.html">
                <img src="sample/IMG_20200103_193716.jpg" alt="" />
                <p>日付</p>
                <p>タイトル</p>
              </a>
            </div>
            <div class="card">
              <a href="productDetail.html">
                <img src="sample/IMG_20200104_183435.jpg" alt="" />
                <p>日付</p>
                <p>タイトル</p>
              </a>
            </div>
            <div class="card">
              <a href="productDetail.html">
                <img src="sample/IMG_20200105_185529.jpg" alt="" />
                <p>日付</p>
                <p>タイトル</p>
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <footer>
      <p>
        &copy Copyright 2020 <a href="">わたしのご飯</a> All rights reserved.
      </p>
    </footer>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>
  </body>
</html>
