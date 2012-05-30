<?php

	/* <bible_check_list 테이블 생성 루틴> */
	// bible_list 테이블 필요함 
	// 나중을 위해 이를 파일형태로 저장하여 db생성시 만들면 어떨까?
	function create_check_list() {
		require_once('functions.php');
		$dbc = connectDB();
		$query = 'SELECT * FROM bible_list';
		$result = mysqli_query($dbc, $query);
		
		while ($row = mysqli_fetch_array($result)) {
			$title = $row['title'];
			$chapters = $row['total_verse'];
			for ($i = 1; $i <= $chapters; $i++) {
				$query = "INSERT INTO bible_check_list (title, chapter, written) ".
						 "VALUES ('$title', '$i', '0')";
				mysqli_query($dbc, $query) or die ('error');
			}
		}
		
		mysqli_close($dbc);	
	}
	
	function update_check_list() {
		require_once('functions.php');
		$dbc = connectDB();
		$query = 'SELECT * FROM bible_contents';
		$result = mysqli_query($dbc, $query);

		while ($row = mysqli_fetch_array($result)) {
			$title = $row['title'];
			$chapter = $row['chapter'];
			$query = "UPDATE bible_check_list SET written = '1' " .
					 "WHERE title = '$title' AND chapter = '$chapter'";
			mysqli_query($dbc, $query) or die ('error in update_check_list');
		}
			
	}
	
	
	create_check_list(); // check_list 테이블 생성
	update_check_list(); // contents 테이블을 보고 check_list 테이블 갱신
?>