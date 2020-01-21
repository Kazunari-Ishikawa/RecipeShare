<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('ご飯登録・編集ページ');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

//================================
// 画面表示用データ取得
//================================
debug('GET：'.print_r($_GET, true));

// 新規登録または編集を判別するフラグ（編集=>1 新規登録=>0）
$edit_flg = (!empty($_GET['p_id'])) ? true : false;

// 編集の場合GETパラメータから料理のIDを取得
$product_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$dbProductData = getProduct($product_id);
debug('料理情報：'.print_r($dbProductData,true));

// DBからカテゴリデータを取得
$dbCategoryData = getCategory();

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));
  debug('FILE：'.print_r($_FILES, true));

  // 変数定義
  $category = $_POST['category_id'];
  $date = $_POST['date'];
  $mainName = $_POST['main-name'];
  $subName = $_POST['sub-name'];
  $comment = $_POST['comment'];
  // 画像をアップロードしパスを保存
  $pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'], 'pic') : '';
  // 画像がPOSTされていないが、DBにすでに登録されている場合DBのデータを代入
  $pic = (empty($pic) && !empty($dbProductData['pic'])) ? $dbProductData['pic'] : $pic;

  // 各項目のバリデーション
  // 新規登録の場合
  if ($edit_flg == 0) {
    // カテゴリ
    validSelect($category, 'category_id');
    // 日付
    validMaxLen($date, 'date');
    validRequired($date, 'date');
    // 主菜
    validMaxLen($mainName, 'main-name');
    validRequired($mainName, 'main-name');
    // 副菜
    validMaxLen($subName, 'sub-name');
    // コメント（コメントは最大500文字まで）
    validMaxLen($comment, 'comment', 500);

  } else {
    // 編集の場合DBと不一致の場合にバリデーションを行う
    // カテゴリ
    if ($category != $dbProductData['category_id']) {
      validSelect($category, 'category_id');
    }
    if ($date !== $dbProductData['date']) {
      validMaxLen($date, 'mate');
      validRequired($date, 'date');
    }
    // 主菜
    if ($mainName !== $dbProductData['main_name']) {
      validMaxLen($mainName, 'main-name');
      validRequired($mainName, 'main-name');
    }
    // 副菜
    if ($mainName !== $dbProductData['sub_name']) {
      validMaxLen($mainName, 'sub-name');
    }
    // コメント
    if ($comment !== $dbProductData['comment']) {
      validMaxLen($comment, 'comment', 500);
    }
  }

  // バリデーションを通った場合
  if (empty($err_msg)) {
    debug('バリデーションOK');

    try {
      // DB接続
      $dbh = dbConnect();

      // 新規登録の場合
      if ($edit_flg == 0) {
        debug('DBへ登録します');
        // SQL作成
        $sql = 'INSERT INTO Recipe(category_id,date,main_name,sub_name,comment,pic,user_id,created_at) VALUES (:c_id,:date,:main_name,:sub_name,:comment,:pic,:u_id,:created_at)';
        $data = array(
          ':c_id' => $category,
          ':date' => $date,
          ':main_name' => $mainName,
          ':sub_name' => $subName,
          ':comment' => $comment,
          ':pic' => $pic,
          ':u_id' => $_SESSION['user_id'],
          ':created_at' => date('Y-m-d H:i:s'),
        );
        // 編集の場合
      } else {
        debug('DBを更新します');
        // SQL作成
        $sql = 'UPDATE Recipe SET category_id = :c_id, main_name = :main_name, sub_name = :sub_name, comment = :comment, pic = :pic WHERE id = :p_id AND user_id = :u_id';
        $data = array(
          ':c_id' => $category,
          ':date' => $date,
          ':main_name' => $mainName,
          ':sub_name' => $subName,
          ':comment' => $comment,
          ':pic' => $pic,
          ':p_id' => $product_id,
          ':u_id' => $_SESSION['user_id'],
        );
      }

      debug('$data：'.print_r($data,true));

      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if ($stmt) {
        debug('登録成功');
        $_SESSION['suc_msg'] = SUC03;
        header("Location:mypage.php");
      } else {
        debug('登録失敗');
        $err_msg['common'] = MSG08;
      }

    } catch (Exception $e) {
      debug('エラー：'.$e->getMessage());
      $err_msg['common'] = MSG08;
    }

  } else {
    debug('バリデーションNG');
  }

}

if(!empty($err_msg)) debug('エラー：'.print_r($err_msg,true));
debug('<<<<<画面表示処理終了<<<<<');

?>

<?php
$siteTitle = 'ご飯登録';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <main id="REGISTER" class="layout-1-column">
      <h1 class="page-title">ご飯を<?php echo (!$edit_flg) ? '登録する' : '編集する'; ?></h1>
      <!-- メインコンテンツ -->
      <section>
        <div class="form-container large-form">
          <?php if($edit_flg == 1): ?>
            <a href="productDetail.php?p_id=<?php echo $product_id; ?>" class="reverse">前ページに戻る</a>
          <?php endif; ?>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="msg-area"></div>
            <label>
              <p>カテゴリ</p>
              <select name='category_id'>
                <option value="0">選択してください</option>
                <?php
                  if (!empty($dbCategoryData)) {
                    foreach ($dbCategoryData as $key => $val) {
                ?>
                      <option value="<?php echo $val['id']; ?>" <?php if (getFormData('category_id') == $val['id']) echo 'selected'; ?>><?php echo $val['name']; ?></option>
                <?php
                    }
                  }
                ?>
              </select>
            </label>
            <div class="msg-area"><?php echo getErrMsg('category_id'); ?></div>
            <label>
              <p>作った日</p>
              <input type="date" name="date" value="<?php echo getFormData('date'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('date'); ?></div>
            <label>
              <p>主菜</p>
              <input type="text" name="main-name" value="<?php echo getFormData('main_name'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('main-name'); ?></div>
            <label>
              <p>副菜</p>
              <input type="text" name="sub-name" value="<?php echo getFormData('sub_name'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('sub-name'); ?></div>
            <label>
              <p>コメント</p>
              <textarea name="comment" cols="30" rows="10" style="height:150px;"><?php echo getFormData('comment'); ?></textarea>
            </label>
            <div class="msg-area"><?php echo getErrMsg('comment'); ?></div>
            <div class="imgDrop-container">
              <p>写真</p>
              <label class="area-drop">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic" accept="image/*" class="input-file" />
                <img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;'; ?>">
                ドラッグ＆ドロップ
              </label>
            </div>
            <div class="msg-area"><?php echo getErrMsg('pic'); ?></div>

            <div class="btn-container">
              <input type="submit" value="<?php if($edit_flg == 0){echo '登録';}else{echo '編集';} ?>" class="btn btn-mid" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>