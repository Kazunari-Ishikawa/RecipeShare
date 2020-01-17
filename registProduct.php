<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('ご飯登録・編集ページ');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

// DBからカテゴリデータを取得
// カテゴリデータ取得関数
function getCategory() {
  debug('カテゴリデータを取得します');

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM category WHERE delete_flg = 0';
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // データ取得
    $result = $stmt->fetchAll();
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
$dbCategoryData = getCategory();

// 画像アップロード関数
function uploadImg($file, $key) {
  debug('画像アップロード開始');

  if (isset($file['error']) && is_int($file['error'])) {

    try {
      // ファイルのエラーコード確認
      switch ($file['error']) {
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_INI_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        case UPLOAD_ERR_FORM_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('ファイルが選択されていません');
        default :
          throw new RuntimeException('予期せぬエラーが発生しました');
      }

      // ファイルのMIMEタイプ確認
      $type = @exif_imagetype($file['tmp_name']);
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
        throw new RuntimeException('画像形式が未対応です');
      }

      // ハッシュをつけてファイル名を保存する
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if (!move_uploaded_file($file['tmp_name'], $path)) {
        throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }

      // 保存したファイルの権限を変更する
      chmod($path, 0644);

      debug('アップロード完了');
      debug('ファイルパス：'.print_r($path,true));
      return $path;

    } catch (RuntimeException $e) {
      debug('エラー：'.$e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();

    }
  }
}

// プロダクト情報取得関数
function getProduct($product_id) {
  debug('プロダクト情報を取得します');
  debug('プロダクトID：'.print_r($product_id, true));

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM Recipe WHERE id = :p_id AND delete_flg = 0';
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
//==============================================================================================

debug('GET：'.print_r($_GET, true));

// 新規登録または編集を判別するフラグ（編集=>1 新規登録=>0）
$edit_flg = (!empty($_GET['p_id'])) ? true : false;

// 編集の場合GETパラメータから料理のIDを取得
$product_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$dbProductData = getProduct($product_id);
debug('料理情報：'.print_r($dbProductData,true));

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));
  debug('FILE：'.print_r($_FILES, true));

  // 変数定義
  $category = $_POST['category'];
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
    if (!preg_match("/^[0-9]+$/", $category)) {
      global $err_msg;
      $err_msg['category'] = '不正な値です';
    }
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
      if (!preg_match("/^[0-9]+$/", $category)) {
        global $err_msg;
        $err_msg['category'] = '不正な値です';
      }
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
        $sql = 'INSERT INTO Recipe(category_id,main_name,sub_name,comment,pic,user_id,created_at) VALUES (:c_id,:main_name,:sub_name,:comment,:pic,:u_id,:created_at)';
        $data = array(
          ':c_id' => $category,
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
        $_SESSION['suc_msg'] = '登録しました';
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
      <h1 class="page-title">ご飯を登録</h1>
      <a href="registProduct.php">更新</a>
      <!-- メインコンテンツ -->
      <section>
        <div class="form-container large-form">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="msg-area"></div>
            <label>
              <p>カテゴリ</p>
              <select name='category'>
                <option value="0">選択してください</option>
                <?php
                  if (!empty($dbCategoryData)) {
                    foreach ($dbCategoryData as $key => $val) {
                ?>
                      <option value="<?php echo $val['id']; ?>" <?php if (!empty($_POST['category']) && $_POST['category'] == $val['id']) echo 'selected'; ?>><?php echo $val['name']; ?></option>
                <?php
                    }
                  }
                ?>
              </select>
            </label>
            <div class="msg-area"><?php echo getErrMsg('category'); ?></div>
            <label>
              <p>主菜</p>
              <input type="text" name="main-name" value="<?php echo getFormData('main-name'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('main-name'); ?></div>
            <label>
              <p>副菜</p>
              <input type="text" name="sub-name" value="<?php echo getFormData('sub-name'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('sub-name'); ?></div>
            <label>
              <p>コメント</p>
              <input type="text" name="comment" value="<?php echo getFormData('comment'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('comment'); ?></div>
            <label>
              <p>写真</p>
              <input type="file" name="pic" accept="image/*" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pic'); ?></div>

            <div class="btn-container">
              <input type="submit" value="登録" class="btn btn-mid" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>