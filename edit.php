<?php 
  // ここにDBに登録する処理を記述する
//1 DBへ接続
$dsn= 'mysql:dbname=myfriends;host=localhost';
$user= 'root';
$password='';
  $dbh= new PDO($dsn, $user, $password);
  $dbh->query('SET NAMES utf8');


/////////////////////////////////

  $editName= '';
  $editAge= '';

  //1 パラメータを受け取る
  $friend_id= $_GET['friend_id'];

  //2受け取ったパラメーターに紐づく友達のデータを取得するSQL文作成(DB内のfriendsというテーブルから引っ張ってくる)
$sql= 'SELECT * FROM `friends` WHERE`friend_id`=?';
$data[]=$friend_id;

// SQL実行
$stmt= $dbh->prepare($sql);
  $stmt->execute($data);


  //友達のデータを取得
  $friends= $stmt->fetch(PDO::FETCH_ASSOC);


//値を変数に格納
    $editName = $friends['friend_name'];
    $editAge = $friends['age'];


////////////////////////////////////////////////////
  //セレクトボックス用の都道府県を取得
  //SQL文（DB内のareasというテーブルから引っ張ってくる）
  $sql='SELECT * FROM `areas`';

  // SQL実行
$stmt= $dbh->prepare($sql);
  $stmt->execute();

//取得データ格納用変数
$areas= array();
  ////3 データを取得
while (1) {
    $rec= $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rec==false){
      break;
    }
    $areas[]= $rec;
  }
  // var_dump($areas);
////////////////////////////////////////////////

//データ更新（更新ボタンクリック時）
if(!empty($_POST)){
$sql = 'UPDATE `friends` SET `friend_name`=?,`area_id`=?,`gender`=?,`age`=? WHERE `friend_id`=?';
$edit_data[]= $_POST['name'];
$edit_data[]= $_POST['area_id'];
$edit_data[]= $_POST['gender'];
$edit_data[]= $_POST['age'];
$edit_data[]= $friend_id;

 // SQL実行
$stmt= $dbh->prepare($sql);
  $stmt->execute($edit_data);

////登録後index.phpに移行するようにする
  header('Location: index.php');
exit();

}

//DB切断
$dbh= null;

?>
  <!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>myFriends</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.php"><span class="strong-title"><i class="fa fa-facebook-square"></i> My friends</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
        <legend>友達の編集</legend>
        <form method="post" action="edit.php?friend_id=<?php echo $friends['friend_id']; ?>" class="form-horizontal" role="form">
            <!-- 名前 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">名前</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" placeholder="editName" required value="<?php echo $editName; ?>">
              </div>
            </div>
            <!-- 出身 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">出身</label>
              <div class="col-sm-10">
                <select class="form-control" name="area_id">
                  <option value="0">出身地を選択</option>
                  <?php foreach($areas as $area):?>
                    <?php if ($area['area_id']== $friends['area_id']): ?>
                  <option value="<?php echo $area['area_id'];?>" selected><?php echo $area['area_name'];?></option>
                     <?php else: ?>
                      <option value="<?php echo $area['area_id'];?>"><?php echo $area['area_name'];?></option>
                <?php endif; ?>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <!-- 性別 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">性別</label>
              <div class="col-sm-10">
                <select class="form-control" name="gender">
                  <option value="0">性別を選択</option>
                  <?php if ($friends['gender']==1):?>
                  <option value="1" selected>男性</option>
                  <option value="2">女性</option>
                  <?php elseif($friends['gender']==2):?>
                    <option value="1">男性</option>
                  <option value="2" selected>女性</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            <!-- 年齢 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">年齢</label>
              <div class="col-sm-10">
                <input type="text" name="age" class="form-control" placeholder="editAge" required value="<?php echo $editAge; ?>">
              </div>
            </div>

          <input type="submit" class="btn btn-default" value="更新">
        </form>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
