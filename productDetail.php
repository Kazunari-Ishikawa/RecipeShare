<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('料理詳細');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

function getProductAndCategory($product_id) {
  debug('プロダクトとカテゴリ情報を取得します');
  debug('プロダクトID：'.print_r($product_id, true));

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT c.name AS category, r.main_name, r.sub_name, r.comment, r.pic, r.user_id FROM Recipe AS r LEFT JOIN category AS c ON r.category_id = c.id WHERE r.id = :p_id AND r.delete_flg = 0 AND c.delete_flg = 0';
    $data = array(':p_id' => $product_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // データ取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt) {
      return $result;
    } else {
      return false;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}

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
            <p><?php echo $viewData['category']; ?></p>
            <p>日付：</p>
            <p>主菜：<?php echo $viewData['main_name']; ?></p>
            <div class="btn-container">
              <a href="">レシピをGoogle検索！</a>
            </div>
            <p>副菜：<?php echo $viewData['sub_name']; ?></p>
            <div class="btn-container">
              <a href="">レシピをGoogle検索！</a>
            </div>
            <p><?php echo $viewData['comment']; ?></p>
          </div>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>