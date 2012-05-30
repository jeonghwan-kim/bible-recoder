<?php	
	// =========================================================================
	// index.php
	// 인덱스 파일
	// =========================================================================
	function getRecentTitle(&$title, &$chapter)
	{	
		$dbc = connectDB();
		$result = mysqli_query($dbc, 'select * from bible_recent');
		$row = mysqli_fetch_array($result);
		$title = $row['title'];
		$chapter = $row['chapter'];
	}
	
	require_once('htmlAuth.php');
	require_once('functions.php');
	setHeadHtml('Bible', 'style.css');	

	/* 상단 - 타이틀 출력 */
	showTitle();
		
	/* 좌측 - 리스트 출력 */
	showList($title);
	
	echo '</div id="right">';
	getRecentTitle($title, $chapter);
	
	// 1. 모든 성경이 비어있을 경우 - 메세지 출력 - javascript로 만들자!
	if (empty($title)) {
		echo "어머니, 파일로 성경입력 하시니 관리가 잘 되지 않는 것 같아 만들어 봤습니다.<br />".
		"앞으로는 이곳에 꾸준히 입력하셔서 성경 66권을 입력해 보세요.<br /><br />" .
		"입력방법은 매우 간단합니다.<br />" .
		"1. <좌측>에서 입력할 성경을 선택하여 <br />" .
		"2. <우측>에 있는 <입력 버튼>을 클릭하여 입력하시면 됩니다. <br />";
		
	}
	// 2. 입력한 성경이 있을 경우 - 해당 성경의 처음장을 출력
	else {
		moveurl("view.php?title=$title&chapter=$chapter");
	}
	echo '</div>';

	/* 하단 - 공백 */
	showFooter();
?>	
