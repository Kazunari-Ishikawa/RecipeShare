<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('料理詳細');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

//================================
// 画面表示用データ取得
//================================
debug('GET：'.print_r($_GET, true));

// GETパラメータから料理のIDを取得
$product_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$viewData = getProductAndCategory($product_id);
// パラメータに不正な値が入っているかチェック
if (empty($viewData)) {
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:mypage.php");
}
debug('料理情報：'.print_r($viewData,true));

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
            <img src="<?php echo $viewData['pic']; ?>" alt="" />
          </div>
          <div class="detail-container-right">
            <i" class="fa fa-heart icon-favorite js-click-favorite <?php if(isFavorite($_SESSION['user_id'], $viewData['id'])) echo 'active'; ?>" aria-hidden="true" data-productid="<?php echo $viewData['id']; ?>"></i">
            <p><?php echo $viewData['category']; ?></p>
            <p>日付：</p>
            <p>主菜：<?php echo $viewData['main_name']; ?></p>
            <div class="btn-container">
              <a href="https://www.google.com/search?hl=ja&q=<?php echo $viewData['main_name']; ?>+レシピ">レシピをGoogle検索！</a>
            </div>
            <p>副菜：<?php echo $viewData['sub_name']; ?></p>
            <div class="btn-container">
            <a href="https://www.google.com/search?hl=ja&q=<?php echo $viewData['sub_name']; ?>+レシピ">レシピをGoogle検索！</a>
            </div>
            <p><?php echo $viewData['comment']; ?></p>
          </div>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>