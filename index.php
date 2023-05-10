<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// my_namespaceをインポートしてconnectDB()関数を呼び出す
use function utils\connectDB;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // POSTリクエストから値を取得する（修正）
  //application/json形式の場合の取得方法
  $inputJSON = file_get_contents('php://input');
  $input = json_decode($inputJSON, true);
  $username = $input['username'] ?? null;
  $email = $input['email'] ?? null;
  $password = $input['password'] ?? null;

  // バリデーション処理
  if (!$username || !$email || !$password) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
  }

  // utils.phpファイルを読み込む
  require_once('utils.php');


  // DBに接続する
  $conn = connectDB();

  //パスワードハッシュ化
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  // プリペアードステートメントを使用してinsert
  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  //username,emailを文字列指定してバインド
  $stmt->bind_param("sss", $username, $email, $hashed_password);

  // プリペアードステートメントを実行
  if ($stmt->execute()) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $stmt->error;
  }
  // MySQLから切断する
  $stmt->close();
  $conn->close();



  // JSON形式でクライアントに返す
  // HTTPステータスコードを追加
  http_response_code(200);
  header('Content-Type: application/json');
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
