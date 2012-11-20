<?php
/**********************************************
 index.php에서 ajax로 호출되는 함수이고, 
 db 조회 후bible.xml를 생성함
**********************************************/
require_once('db.php');

// xml 헤더 제작
$bible_list = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><bible></bible>";

// xml element 생성
$xml = new SimpleXmlElement($bible_list);

// 데이터베이스 접속
$dbc = connectDB();
$query = "select p.id, p.title, p.total_verse, q.sum 
            from bible_list as p, (
            SELECT title, SUM( written ) as sum
            FROM bible_check_list
            GROUP BY title) as q	
            where q.title = p.title
            order by p.id";
$result = mysqli_query($dbc, $query) or die('error in bible-list.php');
mysqli_close($dbc);

// 자식노트로 새로운 블로그 항목 추가
while ($row = mysqli_fetch_array($result)) {
//     echo $row['id'] . '<br />';
    $entry = $xml->addChild("entry");
    $entry->addChild("num",     $row['id']);
    $entry->addChild("title",   $row['title']);
    $entry->addChild("total",   $row['total_verse']);
    $entry->addChild("written", $row['sum']);
}
 
// 파일에 저장
$file = fopen('bible.xml', 'w');
fwrite($file, $xml->asXML());
fclose($file);
?>
 
