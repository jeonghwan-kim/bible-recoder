<?php 
/******************* DB 연결 함수 ****************************************/
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