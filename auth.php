<?php

//================================
// ログイン認証
//================================
// ログイン済みの場合
if (!empty($_SESSION['login_date'])) {
  debug('ログイン済みユーザーです');

  // ログイン有効期限を判定
  if (time() < ($_SESSION['login_date']+$_SESSION['login_limit'])) {
    debug('ログイン期限内です');

    // 現在日時を新たにログイン日時に設定
    $_SESSION['login_date'] = time();

    // login.phpでログイン認証をした場合マイページへ
    if (basename($_SERVER['SCRIPT_NAME']) === 'login.php') {
      debug('マイページへ遷移');
      header("Location:mypage.php");
    }

  } else {
    debug('ログイン期限切れです');

    // セッションを削除
    session_destroy();
    header("Location:login.php");
  }

} else {
  debug('未ログインです');

  // 実行スクリプトがlogin.phpでない場合
  if (basename($_SERVER['SCRIPT_NAME']) !== 'login.php') {
    debug('ログインページへ遷移');
    header("Location:login.php");
  }
}
