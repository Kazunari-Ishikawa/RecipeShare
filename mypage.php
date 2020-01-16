<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('マイページ');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

?>

<?php
$siteTitle = 'マイページ';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <main id="mypage" class="layout-2-column">
      <h1 class="page-title"><?php echo $siteTitle; ?></h1>
      <div class="regist-container">
        <p><a href="registProduct.php">新しく料理を登録する</a></p>
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
              <a href="productDetail.php">
                <img src="sample/IMG_20200102_194326.jpg" alt="" />
                <p>日付</p>
                <p>タイトル</p>
              </a>
            </div>
            <div class="card">
              <a href="productDetail.php">
                <img src="sample/IMG_20200103_193716.jpg" alt="" />
                <p>日付</p>
                <p>タイトル</p>
              </a>
            </div>
            <div class="card">
              <a href="productDetail.php">
                <img src="sample/IMG_20200104_183435.jpg" alt="" />
                <p>日付</p>
                <p>タイトル</p>
              </a>
            </div>
            <div class="card">
              <a href="productDetail.php">
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
    <?php require('footer.php'); ?>