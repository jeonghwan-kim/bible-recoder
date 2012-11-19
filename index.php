<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Bible Recoder</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style2.css">
    <script type="text/javascript">
        // more 버튼 클릭시 추가 성경목록을 보여줌
        function more(hidden, btn) {
            var hidden_elem = document.getElementsByName(hidden);
            for (var i = 0; i < hidden_elem.length; i++) 
                hidden_elem[i].style.display = "block"; // 성경 목록 보이기
            btn.style.display = "none"; // 버튼 숨기기
        }
    </script>
</head>
<body>
<div id="wrapper">
    <div id="top">
        <h1><a href="index2.php">Bible Recoder</a></h1>
    </div>
    
    <div id="middle">
        <div id="old_bible">
            <h3>구약성경</h3>
            <ul>
            <?php $dbc = connectDB();
            $query = "select p.id, p.title, p.total_verse, q.sum 
                      from bible_list as p, (
                          SELECT title, SUM( written ) as sum
                          FROM bible_check_list
                          GROUP BY title) as q
                      where q.title = p.title and id < 40
                      order by p.id";
            $result = mysqli_query($dbc, $query) or die("Error querying");

            for ($i = 0; $i < 10; $i++) { 
                 $row = mysqli_fetch_array($result); ?>
                <li><a href="#"><?php echo $row['title'] . $row['sum'] . '/' 
                . $row['total_verse'];?></a></li>
            <?php } 
            while ($row = mysqli_fetch_array($result)) { ?>
                <li name="hidden1" style="display:none;"><a href="#"><?php echo $row['title'] . $row['sum'] . '/' 
                . $row['total_verse'];?></a></li>
            <?php } ?>
            </ul>
            <input id="morebtn1" type="button" value="더보기" onclick="more('hidden1',this);" />
        </div>
        
        <div id="new_bible">
            <h3>신약성경</h3>
            <ul>
            <?php 
            $query = "select p.id, p.title, p.total_verse, q.sum 
                      from bible_list as p, (
                          SELECT title, SUM( written ) as sum
                          FROM bible_check_list
                          GROUP BY title) as q
                      where q.title = p.title and id > 39
                      order by p.id";
            $result = mysqli_query($dbc, $query) or die("Error querying");
            for ($i = 0; $i < 10; $i++) { 
                 $row = mysqli_fetch_array($result); ?>
                <li><a href="#"><?php echo $row['title'] . $row['sum'] . '/' 
                . $row['total_verse'];?></a></li>
            <?php } 
            while ($row = mysqli_fetch_array($result)) { ?>
                <li name="hidden2" style="display:none;"><a href="#"><?php echo $row['title'] . $row['sum'] . '/' 
                . $row['total_verse'];?></a></li>
            <?php } ?>
            </ul>
            <input id="morebtn2" type="button" value="더보기" onclick="more('hidden2',this);" />
        </div>
        
        <div id="recent">
            <h3>최근글</h3>
            <ul>
            <?php
            $query = "SELECT * FROM bible_check_list where written=1 
                      order by written_date desc, id DESC limit 0, 10";
            $result = mysqli_query($dbc, $query) or die("Error querying");
            mysqli_close($dbc);
            while ($row = mysqli_fetch_array($result)) { ?>
                <li><a href="#"><?php echo $row['written_date'] . ' ' 
                . $row['title'] . ' ' . $row['chapter'] . '장'; ?></a></li>
            <?php } ?>
            </ul>
        </div>
    </div> <!-- end of middle -->
    
    <div id="footer">
    </div>
</div> <!-- end of wrapper -->
</body>
</html>

<!-- php functions -->
<?php
function connectDB() {
    $db_host = 'localhost';
    $user =  'root';
    $password = 'root';
    $database = 'jeonghwankim';
    
    $dbc = mysqli_connect($db_host, $user, $password, $database)
           or die('Error connecting to MySQL server.');
           
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    mysqli_query($dbc, "set names utf8"); // DB 한글 설정
    return $dbc;
}
?>