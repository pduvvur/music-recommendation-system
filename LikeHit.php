<?php 
    $songid = $_GET['song_id'];
    $userid = $_GET['user_id'];
    $pass = $_GET['password'];
    $clusterid = $_GET['cluster_id'];
    $sectiontoberefreshed = $_GET['to_be_refreshed'];

    $link = oci_connect('system', 'Nitish91', '');
    if (!$link)
    {
	die('Could not connect to Oracle: ');
    }
    else
    {
    	$stmt = oci_parse($link,'insert into user_to_song values(\''.$songid.'\',\''.$userid.'\',\''.$clusterid.'\')');
        $resultset = oci_execute($stmt);
	oci_commit($link);
	$var = "";
	$a = "";	
       	if($sectiontoberefreshed == 'Random')
	{
		$randomorsuggested = "Random"; 
		$stmt2 = oci_parse($link,'select * from(select * from song order by DBMS_RANDOM.VALUE) where rownum=1'); 
		$resultset2 = oci_execute($stmt2);
		$ToBeRefreshed = "Random";
		$RefreshSection = '\''.$ToBeRefreshed.'\'';
		$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:RefreshSection(".$RefreshSection."); return false;\">Refresh</a></span></div></div>";
		while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
		{
			$songid = $resultset2['SONG_ID'];
			$clusterid = $resultset2['CLUSTER_ID'];
			$parameters = '\''.$songid.'\',\''.$clusterid.'\',\''.$userid.'\',\''.$pass.'\',\''.$randomorsuggested.'\'';
			$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><a style=\"color:#24211d\" href=\"#\" onclick=\"return false;\">".$resultset2['SONG_TITLE']."</a><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:LikeThisSong(".$parameters.");\"); return false;\">Like</a></span></div></div>";
		}
		$var = "<h1>Random track</h1>";
		$var .= $a;
	}
     }
     oci_close($link);
     echo $var;
?>