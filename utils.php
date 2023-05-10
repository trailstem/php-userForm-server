<?php

namespace utils;

use mysqli;

// MySQLに接続する
function connectDB()
{
  //mysql接続情報
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "userForm";
  // MySQLに接続する（接続できなかった場合の処理も記載）
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn) {
    return $conn;
  } else {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $conn;
}
