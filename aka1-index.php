<?php
define( 'DB_HOST', 'localhost');define( 'DB_USER', 'root');define( 'DB_PASS', '');define( 'DB_NAME', 'id1');
date_default_timezone_set('Asia/Tokyo');$current_date = null;$message = array();$message_array = array();

$error_message = array();$pdo = null;$stmt = null;$res = null;$option = null;session_start();
try {$option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_MULTI_STATEMENTS => false);
    $pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);} 
	catch(PDOException $e) {$error_message[] = $e->getMessage();}if( !empty($_POST['btn_submit']) ) 
	{$view_name = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['view_name']);
	$message = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['message']);if( empty($view_name) ) {
	$error_message[] = 'お名前を入力下さい。';} else {$_SESSION['view_name'] = $view_name;}if( empty($message) ) {
	$error_message[] = 'コメント入力下さい。';} else {if( 100 < mb_strlen($message, 'UTF-8') ) {
	$error_message[] = '100字以内で願います。';}}if( empty($error_message) ) {$current_date = date("Y-m-d H:i:s");
	$pdo->beginTransaction();try {$stmt = $pdo->prepare("INSERT INTO message (view_name, message, post_date) 
	VALUES ( :view_name, :message, :current_date)");$stmt->bindParam( ':view_name', $view_name, PDO::PARAM_STR);
	$stmt->bindParam( ':message', $message, PDO::PARAM_STR);$stmt->bindParam( ':current_date', $current_date, 
	PDO::PARAM_STR);$res = $stmt->execute();$res = $pdo->commit();} catch(Exception $e) {$pdo->rollBack();}if( 
	$res ) {$_SESSION['success_message'] = '投稿頂き、有難うございます。';} else {$error_message[] = '投稿に失敗しました。';}

	$stmt = null;header('Location: ./aka1-index.php');exit;}}if( !empty($pdo) ) {$sql = "SELECT view_name,message,
	post_date FROM message ORDER BY post_date DESC";$message_array = $pdo->query($sql);}$pdo = null;?>

<!DOCTYPE html><html><head><meta charset="utf-8"><title>TWINS LOG</title><meta charset="utf-8">

<style>
.fix-01{background:#d939cd;left:0;line-height:1;position:fixed;top:0;width:100%;
    z-index:100;text-align:center;padding:1rem;}
a {outline: none;text-decoration: none;} a:link { color: #000; }
a:visited { color: #000; } a:active { color: #000; }


ul {display:flex;}
.menu {position:fixed;left:0;bottom:0;background:rgb(247, 247, 247);width:100%;}
.menu li {display:table;table-layout:fixed;width: 100%;padding: 5px;list-style: none;text-align: center;}
.menu a span {display: block;font-size: 4px;}.baby{color: #d939cd;}
.restaurant{color: #F55555}.coffee{color: #F8D800}.shop{color: #32CCBC}
.milk{color: #7367F0}.play{color: #0396FF}.trip{color: #000DFF}

/*Common Style*/
body {padding: 50px;font-size: 100%; color: #222;background: #f7f7f7;}
h1 {margin-bottom: 30px;font-size: 100%;color: #222;text-align: center;}
/*入力エリア*/
label {display: inline-block;font-size:86%;vertical-align:top;}input[type="text"],
textarea {margin-bottom:5px; padding:10px;font-size:86%;border:1px solid #ddd;
    border-radius: 3px;background: #fff;}
input[type="text"] {width: 200px; height: 3px;}
textarea {width: 200px; height: 70px;}
input[type="submit"] {appearance:none;-webkit-appearance:none;padding:10px 20px;
	color: #fff;font-size: 86%;line-height: 1.0em;cursor: pointer;
	border: none;border-radius: 5px;background-color: #37a1e5;width:313px;}
input[type=submit]:hover, button:hover {background-color: #2392d8;}
.success_message {margin-bottom: 20px;padding: 10px;color: #48b400;
    border-radius: 10px;border: 1px solid #4dc100;}
.error_message {margin-bottom: 20px;padding: 10px;color: #ef072d;
    list-style-type: none;border-radius: 10px;border: 1px solid #ff5f79;}
.success_message,.error_message li {font-size: 86%;line-height: 0.6em;width:287px;}
/*掲示板エリア*/
article {margin-top: 20px;padding:10px;border-radius:10px;width:295px;
	background: #fff;}
.info h2 {display: inline-block;margin-right: 10px;color: #222;line-height: 0.1em;font-size: 86%;}
.info time {color: #999;line-height: 0.1em;font-size: 72%;margin-left:55px;}
article p {color: #555;font-size: 86%;line-height: 1.6em;}


#side{float:left;width: 320px;}#main{float:right;width: 600px;border:3px solid #37a1e5;}


</style></head><header class="fix-01"><a href="index.html"><i class="fa-solid fa-home post"></i>
<br><span class="post">HOME</span></a></header>
<body>


<div id="header"><h1>グランツリー武蔵小杉</h1></div>

<div id="side">
<?php if( empty($_POST['btn_submit']) && !empty($_SESSION['success_message']) ): ?>
<p class="success_message"><?php echo htmlspecialchars( $_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?><?php if( !empty($error_message) ): ?><ul class="error_message"><?php foreach( $error_message
	as $value ): ?><li>・<?php echo $value; ?></li><?php endforeach; ?></ul><?php endif; ?>

<form method="post">
<div><label for="view_name"><i class="fa-solid fa-circle-user fa-lg"></i>   お名前　　</label><input id="view_name" type="text" name="view_name"value="<?php 
	if( !empty($_SESSION['view_name']) ){ echo htmlspecialchars( $_SESSION['view_name'], ENT_QUOTES, 'UTF-8'); } ?>"></div>

<div><label for="message"><i class="fa-solid fa-comment fa-lg"></i> コメント　</label><textarea id="message" name="message"><?php if( !empty($message) ){ echo 
	htmlspecialchars( $message, ENT_QUOTES, 'UTF-8'); } ?></textarea></div>
<input type="submit" name="btn_submit" value="投稿する"></form>
<hr><section><?php if( !empty($message_array) ){ ?><?php foreach( $message_array as $value ){ ?><article>
	<div class="info"><h2><i class="fa-solid fa-circle-user fa-lg"></i>　<?php echo htmlspecialchars( $value['view_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
	<time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time></div>
	<p><?php echo nl2br( htmlspecialchars( $value['message'], ENT_QUOTES, 'UTF-8') ); ?></p></article><?php } ?><?php } ?></section>
</div>

<div id="main">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3245.1764270768863!2d139.65897531472558!3d35.5740369802194!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6018f57cb6c76467%3A0x3b74b37a6eea2e8f!2z44Ki44Kr44OB44Oj44Oz44Ob44Oz44OdIOOCsOODqeODs-ODhOODquODvOatpuiUteWwj-adieW6lw!5e0!3m2!1sja!2sjp!4v1660915933724!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

<script src="https://kit.fontawesome.com/b2cf6ccdb7.js" crossorigin="anonymous"></script>
</body>
<ul class="menu">
<li><a href="#"><i class="fa-solid fa-baby-carriage fa-lg baby"></i>
<br><span class="baby">ベビー用品</span></a></li>
<li><a href="#"><i class="fa-solid fa-utensils fa-lg restaurant"></i>
	<br><span class="restaurant">レストラン</span></a></li>		
<li><a href="#"><i class="fa-solid fa-mug-saucer fa-lg coffee"></i>
	<br><span class="coffee">カフェ</span></a></li>
<li><a href="#"><i class="fa-solid fa-cart-shopping fa-lg shop"></i>
	<br><span class="shop">買い物</span></a></li>			
<li><a href="#"><i class="fa-solid fa-baby fa-lg milk"></i>
	<br><span class="milk">授乳室</span></a></li>
<li><a href="#"><i class="fa-solid fa-otter fa-lg play"></i>
	<br><span class="play">お出掛け</span></a></li>
<li><a href="#"><i class="fa-solid fa-suitcase fa-lg trip"></i>
		<br><span class="trip">旅行</span></a></li>		
<li><a href="#"><i class="fa-solid fa-pen-to-square fa-lg post"></i>
		<br><span class="post">新規投稿</span></a></li>		
<li><a href="#"><i class=""></i><br><span class="">　　　　</span></a></li>	
</html>
