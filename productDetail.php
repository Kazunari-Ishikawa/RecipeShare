<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('料理詳細');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

?>

<?php
$siteTitle = '料理詳細';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <main id="mypage" class="layout-1-column">
      <h1 class="page-title">わたしのご飯</h1>

      <!-- メインコンテンツ -->
      <section id="detail">
        <div class="detail-container">
          <div class="detail-container-top">
            <a href="mypage.php">前ページへ戻る</a>
          </div>
          <div class="detail-container-left">
            <img src="sample/IMG_20200115_200337.jpg" alt="" />
          </div>
          <div class="detail-container-right">
            <p>日付</p>
            <p>タイトル</p>
            <p>カテゴリ</p>
            <div class="btn-container">
              <a href="">レシピをGoogle検索！</a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>