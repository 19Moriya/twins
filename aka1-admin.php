<?php
define( 'PASSWORD', 'admin');define( 'DB_HOST', 'localhost');define( 'DB_USER', 'root');define( 'DB_PASS', '');define( 'DB_NAME', 'id1');

date_default_timezone_set('Asia/Tokyo');$current_date = null;$message = array();$message_array = array();$success_message = null;
$error_message = array();$pdo = null;$stmt = null;$res = null;$option = null;session_start();if( !empty($_GET['btn_logout']) ) {unset($_SESSION['admin_login']);}
try {$option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_MULTI_STATEMENTS => false);$pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);
} catch(PDOException $e) {$error_message[] = $e->getMessage();}if( !empty($_POST['btn_submit']) ) {if( !empty($_POST['admin_password']) && $_POST['admin_password'] === PASSWORD ) {
$_SESSION['admin_login'] = true;} else {$error_message[] = 'ログインに失敗しました。';}}if( !empty($pdo) ) {

// メッセージのデータを取得する
$sql = "SELECT * FROM message ORDER BY post_date DESC";$message_array = $pdo->query($sql);}
// データベースの接続を閉じる
$pdo = null;?>

<!DOCTYPE html><html lang="ja"><head><meta charset="utf-8"><title>Twins Map</title>
</head>
<style>
a {outline: none;text-decoration: none;} a:link { color: #615D5C} 
a:visited { color: #615D5C;} a:active { color: #615D5C}
.fix-01{background:#E6E6E7;left:0;line-height:1;position:fixed;top:0;
	width:100%;z-index:100;text-align:center;padding:1rem; }

ul {display:flex;}
.menu {position:fixed;left:0;bottom:0;background:#E6E6E7;width:100%;}
.menu li {display:table;table-layout:fixed;width: 100%;padding: 5px;list-style: none;text-align: center;}
.menu a span {display: block;font-size: 4px;}.restaurant{color: #d939cd;}
.baby{color: #F55555}.coffee{color: #F8D800}.shop{color: #32CCBC}
.trip{color: #7367F0}.milk{color: #0396FF}.play{color: #000DFF}
.home {position:fixed; left:100px; color: #615D5C; padding-top: 15px;}
.Kanri {position:fixed; right:100px; color: #615D5C; padding-top: 15px;}
h1 {padding-top:20px;color:#615D5C;font-size:120%;text-align:center;} iframe {height: 370px;}

/*Common Style*/
body {padding: 50px;font-size: 100%; color:#615D5C;background: #F6F6F7;}
/*入力エリア*/
label {display: inline-block;font-size:86%;vertical-align:top;}input[type="text"],
textarea {margin-bottom:5px; padding:10px;font-size:86%;border:1px solid #ddd;
    border-radius: 3px;background: #fff;color:#615D5C;}
input[type="text"] {width: 200px; height: 3px;}
textarea {width: 200px; height: 70px;color:#615D5C;}
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
	background: #fff;color:#615D5C;}
.info h2 {display: inline-block;margin-right: 10px;line-height: 0.1em;font-size: 86%;color:#615D5C;}
.info time {color: #999;line-height: 0.1em;font-size: 72%;margin-left:55px;}
article p {color:#615D5C;font-size: 86%;line-height: 1.6em;}


</style>
</head><header class="fix-01"><a href="index.html"><i class="fa-solid fa-house fa-lg home"></i></a>
<a href="index.html"><img src="img/Twins.png" alt="Twins" width="60" height="33">
<img src="img/Map.png" alt="Map" width="60" height="33"></a>
<a href="index.html"><i class="fa-solid fa-user fa-lg Kanri"></i></a></header>

<body>
<div id="header"><h1><i class="fa-solid fa-location-dot fa-lg"></i>　グランツリー武蔵小杉</h1></div>


<?php if( !empty($error_message) ): ?><ul class="error_message"><?php foreach( $error_message as $value ): ?>
<li>・<?php echo $value; ?></li><?php endforeach; ?></ul><?php endif; ?>

<section><?php if( !empty($_SESSION['admin_login']) && $_SESSION['admin_login'] === true ): ?>
<?php if( !empty($message_array) ){ ?><?php foreach( $message_array as $value ){ ?><article><div class="info">

<h2><?php echo htmlspecialchars( $value['view_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
<time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>

<p><a href="aka1-edit.php?message_id=<?php echo $value['id']; ?>">編集</a>  
<a href="aka1-delete.php?message_id=<?php echo $value['id']; ?>">削除</a></p>

</div><p><?php echo nl2br( htmlspecialchars( $value['message'], ENT_QUOTES, 'UTF-8') ); ?></p>
</article><?php } ?><?php } ?><form method="get" action=""><input type="submit" name="btn_logout" value="ログアウト"></form><?php else: ?>
<form method="post"><div><label for="admin_password">ログインパスワード</label><input id="admin_password" type="password" name="admin_password" value="">
</div><input type="submit" name="btn_submit" value="ログイン"></form><?php endif; ?></section>


<script src="https://kit.fontawesome.com/b2cf6ccdb7.js" crossorigin="anonymous"></script>
</body>
<ul class="menu">
<li><a href="#"><i class="fa-solid fa-baby-carriage fa-lg baby"></i><br><span class="baby">ベビー用品</span></a></li>
<li><a href="#"><i class="fa-solid fa-utensils fa-lg restaurant"></i><br><span class="restaurant">レストラン</span></a></li>		
<li><a href="#"><i class="fa-solid fa-mug-saucer fa-lg coffee"></i><br><span class="coffee">カフェ</span></a></li>
<li><a href="#"><i class="fa-solid fa-cart-shopping fa-lg shop"></i><br><span class="shop">買い物</span></a></li>			
<li><a href="#"><i class="fa-solid fa-baby fa-lg milk"></i><br><span class="milk">授乳室</span></a></li>
<li><a href="#"><i class="fa-solid fa-otter fa-lg play"></i><br><span class="play">お出掛け</span></a></li>
<li><a href="#"><i class="fa-solid fa-suitcase fa-lg trip"></i><br><span class="trip">旅行</span></a></li>		
<li><a href="#"><i class="fa-solid fa-pen-to-square fa-lg post"></i><br><span class="post">新規投稿</span></a></li>		
<li><a href="#"><i class=""></i><br><span class="">　　　　</span></a></li>	
</html>