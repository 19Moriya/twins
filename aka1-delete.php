<?php
define( 'DB_HOST', 'localhost');define( 'DB_USER', 'root');define( 'DB_PASS', '');define( 'DB_NAME', 'id1');

date_default_timezone_set('Asia/Tokyo');$view_name = null;$message = array();$message_data = null;$error_message = array();
$pdo = null;$stmt = null;$res = null;$option = null;session_start();if( empty($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true ) {

// ログインページへリダイレクト
header("Location: ./aka1-admin.php");exit;}

try {$option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_MULTI_STATEMENTS => false);
$pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);} catch(PDOException $e) {
$error_message[] = $e->getMessage();}if( !empty($_GET['message_id']) && empty($_POST['message_id']) ) {
$stmt = $pdo->prepare("SELECT * FROM message WHERE id = :id");$stmt->bindValue( ':id', $_GET['message_id'], PDO::PARAM_INT);
$stmt->execute();$message_data = $stmt->fetch();

// 投稿データが取得できないときは管理ページに戻る
if( empty($message_data) ) {header("Location: ./aka1-admin.php");exit;}

} elseif( !empty($_POST['message_id']) ) {$pdo->beginTransaction();try {$stmt = $pdo->prepare("DELETE FROM message WHERE id = :id");
$stmt->bindValue( ':id', $_POST['message_id'], PDO::PARAM_INT); $stmt->execute(); $res = $pdo->commit();} catch(Exception $e) {$pdo->rollBack();}

// 削除に成功したら一覧に戻る
if( $res ) {header("Location: ./aka1-admin.php");exit;}}

$stmt = null;$pdo = null;?>
<!DOCTYPE html><html lang="ja"><head><meta charset="utf-8"><title>DELETE / 削除</title>

</head><body><h1>DELETE / 削除</h1><?php if( !empty($error_message) ): ?><ul class="error_message"><?php foreach( $error_message as $value ): ?>
<li>・<?php echo $value; ?></li><?php endforeach; ?></ul><?php endif; ?><p class="text-confirm">以下の投稿を削除します。<br>よろしければ「削除」ボタンを押してください。</p>
<form method="post"><div><label for="view_name">表示名</label>
<input id="view_name" type="text" name="view_name" value="<?php if( !empty($message_data['view_name']) ){ echo $message_data['view_name']; } elseif( !empty($view_name) ){ echo htmlspecialchars( $view_name, ENT_QUOTES, 'UTF-8'); } ?>" disabled>
</div><div><label for="message">ひと言メッセージ</label><textarea id="message" name="message" disabled><?php if( !empty($message_data['message']) ){ echo $message_data['message']; } elseif( !empty($message) ){ echo htmlspecialchars( $message, ENT_QUOTES, 'UTF-8'); } ?></textarea></div>
	
<a class="btn_cancel" href="aka1-admin.php">キャンセル</a>

<input type="submit" name="btn_submit" value="削除"><input type="hidden" name="message_id" value="<?php if( !empty($message_data['id']) ){ echo $message_data['id']; } elseif( !empty($_POST['message_id']) ){ echo htmlspecialchars( $_POST['message_id'], ENT_QUOTES, 'UTF-8'); } ?>"></form></body>

</html>