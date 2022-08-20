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

} elseif( !empty($_POST['message_id']) ) {$view_name = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['view_name']);
$message = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['message']);if( empty($view_name) ) {
$error_message[] = '表示名を入力してください。';}if( empty($message) ) {$error_message[] = 'メッセージを入力してください。';} else {
if( 100 < mb_strlen($message, 'UTF-8') ) {$error_message[] = 'ひと言メッセージは100文字以内で入力してください。';}}if( empty($error_message) ) {
$pdo->beginTransaction();try {$stmt = $pdo->prepare("UPDATE message SET view_name = :view_name, message= :message WHERE id = :id");
$stmt->bindParam( ':view_name', $view_name, PDO::PARAM_STR);$stmt->bindParam( ':message', $message, PDO::PARAM_STR);
$stmt->bindValue( ':id', $_POST['message_id'], PDO::PARAM_INT);$stmt->execute();$res = $pdo->commit();} catch(Exception $e) {$pdo->rollBack();}

// 更新に成功したら一覧に戻る
if( $res ) {header("Location: ./aka1-admin.php");exit;}}}

$stmt = null;$pdo = null;?>
<!DOCTYPE html><html lang="ja">
<head><meta charset="utf-8"><title>EDIT / 編集</title>
<style>
</style>
</head>

<body><h1>EDIT / 編集</h1><?php if( !empty($error_message) ): ?><ul class="error_message"><?php foreach( $error_message as $value ): ?>
<li>・<?php echo $value; ?></li><?php endforeach; ?></ul><?php endif; ?><form method="post"><div>
<label for="view_name">表示名</label><input id="view_name" type="text" name="view_name" value="<?php if( !empty($message_data['view_name']) ){ echo $message_data['view_name']; } elseif( !empty($view_name) ){ echo htmlspecialchars( $view_name, ENT_QUOTES, 'UTF-8'); } ?>">
</div><div><label for="message">ひと言メッセージ</label><textarea id="message" name="message"><?php if( !empty($message_data['message']) ){ echo $message_data['message']; } elseif( !empty($message) ){ echo htmlspecialchars( $message, ENT_QUOTES, 'UTF-8'); } ?></textarea></div>

<a class="btn_cancel" href="aka1-admin.php">キャンセル</a>

<input type="submit" name="btn_submit" value="更新"><input type="hidden" name="message_id" value="<?php if( !empty($message_data['id']) ){ echo $message_data['id']; } elseif( !empty($_POST['message_id']) ){ echo htmlspecialchars( $_POST['message_id'], ENT_QUOTES, 'UTF-8'); } ?>"></form></body>
</html>