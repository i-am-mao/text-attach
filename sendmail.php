<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>sendmail.php</title>
</head>
<body>
<?php
//変数に格納
$email=$_POST['email'];


// 宛て先アドレス
$mailTo = $email;
 
// メールのタイトル
$mailSubject = 'テキストファイル添付メール';
 
// メール本文
$mailMessage = <<< __EOT
メール本文
	
ケータイにも送れます
__EOT;


//ファイル処理
$dir = '.';

if (!$dp= opendir($dir)) {
    die("開けません");
}
while (($file=readdir($dp)) !== false) {	// falseが返らない間ループ
    if ($file === '.' || $file === '..') continue;	// スキップ
   }
closedir($dp);

//画像ファイル名取得
$txtName    = $_FILES['txt']['name'];

//テンポラリファイルの名取得
$fileName    = $_FILES["txt"]["tmp_name"];


 
// 差出人のメールアドレス
$mailFrom    = '差出人のメールアドレス';
 
// Return-Pathに指定するメールアドレス
$returnMail  = '差出人のメールアドレスと同じでＯＫ';
 
// メールで日本語使用するための設定をします。
mb_language("Ja") ;
mb_internal_encoding("UTF-8");
 
$header  = "X-Mailer: PHP5\n";
$header  = "From: $mailFrom\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"__PHPRECIPE__\"\r\n";
$header .= "\r\n";
 
$body  = "--__PHPRECIPE__\r\n";
$body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\r\n";
$body .= "\r\n";
$body .= $mailMessage . "\r\n";
$body .= "--__PHPRECIPE__\r\n";
 
// 添付ファイルへの処理をします。
$handle = fopen($fileName, 'r');
$attachFile = fread($handle, filesize($fileName));
fclose($handle);
$attachEncode = base64_encode($attachFile);
 
$body .= "Content-Type: name=\"$file\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "Content-Disposition: attachment; filename=\"$txtName\"\r\n";
$body .= "\r\n";
$body .= chunk_split($attachEncode) . "\r\n";
$body .= "--__PHPRECIPE__--\r\n";
 
// メールの送信と結果の判定をします。セーフモードがOnの場合は第5引数が使えません。
if (ini_get('safe_mode')) {
 $result = mb_send_mail($mailTo, $mailSubject, $body, $header);
} else {
 $result = mb_send_mail($mailTo, $mailSubject, $body, $header,'-f' . $returnMail);
}
 
if($result){
       echo '<p>送信成功</p>';
}else{
       echo '<p>送信失敗</p>';
}
?>
</body>
</html>