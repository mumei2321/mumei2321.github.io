<?php
// データベースの設定
$db_host = "localhost"; // データベースのホスト名
$db_user = "root"; // データベースのユーザー名
$db_pass = "password"; // データベースのパスワード
$db_name = "bbs"; // データベースの名前

// データベースに接続
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("データベースに接続できませんでした: " . mysqli_connect_error());
}

// 文字コードを設定
mysqli_set_charset($conn, "utf8");

// テーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS posts (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)";
if (!mysqli_query($conn, $sql)) {
    die("テーブルの作成に失敗しました: " . mysqli_error($conn));
}

// フォームからデータを受け取った場合
if (isset($_POST["submit"])) {
    // 入力値を取得
    $name = $_POST["name"];
    $message = $_POST["message"];

    // 入力値のバリデーション
    if ($name == "" || $message == "") {
        echo "<script>alert('名前とメッセージは必須です。');</script>";
    } else {
        // データベースにデータを挿入
        $sql = "INSERT INTO posts (name, message) VALUES ('$name', '$message')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('投稿が完了しました。');</script>";
        } else {
            echo "<script>alert('投稿に失敗しました。');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
    <style>
        /* スタイルを自由に設定 */
        body {
            font-family: "Meiryo", sans-serif;
            background-color: #f0f0f0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            width: 600px;
            margin: 0 auto;
        }
        input, textarea {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
        .posts {
            width: 600px;
            margin: 0 auto;
        }
        .post {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            background-color: #fff;
        }
        .post-name {
            font-weight: bold;
            color: #333;
        }
        .post-date {
            font-size: 12px;
            color: #999;
        }
        .post-message {
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>掲示板</h1>
    <form method="post" action="">
        <input type="text" name="name" placeholder="名前">
        <textarea name="message" rows="5" placeholder="メッセージ"></textarea>
        <input type="submit" name="submit" value="投稿する">
    </form>
    <div class="posts">
        <?php
        // データベースからデータを取得
        $sql = "SELECT * FROM posts ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // データを表示
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='post'>";
                echo "<p class='post-name'>" . $row["name"] . "</p>";
                echo "<p class='post-date'>" . $row["date"] . "</p>";
                echo "<p class='post-message'>" . $row["message"] . "</p>";
                echo "</div>";
            }
        } else {
            // データがない場合
            echo "<p>まだ投稿がありません。</p>";
        }
        ?>
    </div>
</body>
</html>
