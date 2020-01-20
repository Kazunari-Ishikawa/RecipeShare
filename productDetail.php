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
  <main id="productDetail" class="layout-1-column">
    <a href="ProductDetail.php?p_id=<?php echo sanitize($viewData['id']); ?>">
      <h1 class="page-title"><?php echo sanitize($viewData['main_name']); ?></h1>
    </a>

    <!-- メインコンテンツ -->
    <section id="detail">
      <div class="detail-container">
        <div class="detail-container-top">
          <a href="mypage.php">前ページへ戻る</a>
        </div>
        <div class="detail-container-left">
          <img src="<?php echo sanitize($viewData['pic']); ?>" alt="" />
        </div>
        <div class="detail-container-right">
          <div class="icon-container">
            <i class="fa fa-heart detail-icon js-click-favorite <?php if(isFavorite($_SESSION['user_id'], $viewData['id'])) echo 'active'; ?>" aria-hidden="true" data-productid="<?php echo $viewData['id']; ?>"></i>
            <a href="registProduct.php?p_id=<?php echo $product_id; ?>">
              <i class="fas fa-edit detail-icon"></i>
            </a>
            <i class="far fa-trash-alt detail-icon js-click-delete" data-productid=<?php echo $_GET['p_id']; ?>></i>
            <!-- <a href="productDelete.php?p_id=<?php echo $product_id; ?>">
              <i class="far fa-trash-alt detail-icon js-click-delete" data-productid=<?php echo $_GET['p_id']; ?>></i>
            </a> -->
          </div>

          <div class="google-search">
            <a href="https://www.google.com/search?hl=ja&q=<?php echo $viewData['main_name']; ?>+レシピ"><?php echo sanitize($viewData['main_name']); ?>のレシピを検索！</a>
            <a href="https://www.google.com/search?hl=ja&q=<?php echo $viewData['sub_name']; ?>+レシピ"><?php echo sanitize($viewData['sub_name']); ?>のレシピを検索！</a>
          </div>

          <table id="detail-table">
            <tr>
              <td>カテゴリ</td>
              <td>：<?php echo sanitize($viewData['category']); ?></td>
            </tr>
            <tr>
              <td>日付</td>
              <td>：<?php echo sanitize($viewData['date']); ?></td>
            </tr>
            <tr>
              <td>主菜</td>
              <td>：<?php echo sanitize($viewData['main_name']); ?></td>
            </tr>
            <tr>
              <td>副菜</td>
              <td>：<?php echo sanitize($viewData['sub_name']); ?></td>
            </tr>
            <tr>
              <td>コメント</td>
              <td>：<?php echo sanitize($viewData['comment']); ?></td>
            </tr>
          </table>

        </div>
      </div>
    </section>
  </main>

  <!-- フッター -->
  <?php require('footer.php'); ?>
</body>
