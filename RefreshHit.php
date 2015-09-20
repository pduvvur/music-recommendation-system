<?php 
    $uname = $_GET['user_id'];
    $pass = $_GET['password'];
    $sectionToBeRefreshed = $_GET['toberefreshed'];
    $link = oci_connect('system', 'Nitish91', '');
    if (!$link)
    {
        die('Could not connect to Oracle: ');
    }
    else
    {
    	if($sectionToBeRefreshed == 'Random')
	{
		$a = "";
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
			$parameters = '\''.$songid.'\',\''.$clusterid.'\',\''.$uname.'\',\''.$pass.'\',\''.$randomorsuggested.'\'';
			$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><a style=\"color:#24211d\" href=\"#\" onclick=\"return false;\">".$resultset2['SONG_TITLE']."</a><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:LikeThisSong(".$parameters.");\"); return false;\">Like</a></span></div></div>";
		}
		$var = "<h1>Random track</h1>";
		$var .= $a;
	}
	else
	{
		$a = "";
		$stmt2 = oci_parse($link, 'select max(count(cluster_id)) as count from user_to_song where user_id=\''.$uname.'\' group by cluster_id');
		$resultset2 = oci_execute($stmt2);
		$count=0;
		while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
		{
			$count = $resultset2['COUNT'];
		}
		if($count>0)
		{
			$stmt2 = oci_parse($link, 'select cluster_id from user_to_song where user_id=\''.$uname.'\' having count(cluster_id)='.$count.'group by cluster_id');
			$resultset2 = oci_execute($stmt2);	
			$cluster_id='cluster0';
			while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
			{
				$cluster_id = $resultset2['CLUSTER_ID'];
			}
			$stmt2 = oci_parse($link,'select * from(select * from song order by DBMS_RANDOM.VALUE) where rownum<=5 and cluster_id=\''.$cluster_id.'\'');
			$randomorsuggested = "Suggested";
			$resultset2 = oci_execute($stmt2);
			$ToBeRefreshed = "Suggestions";
			$RefreshSection = '\''.$ToBeRefreshed.'\'';
			$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:RefreshSection(".$RefreshSection."); return false;\">Refresh</a></span></div></div>";
			while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
			{
				$songid = $resultset2['SONG_ID'];
				$clusterid = $resultset2['CLUSTER_ID'];
				$parameters = '\''.$songid.'\',\''.$clusterid.'\',\''.$uname.'\',\''.$pass.'\',\''.$randomorsuggested.'\'';
				$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><a style=\"color:#24211d\" href=\"#\" onclick=\"return false;\">".$resultset2['SONG_TITLE']."</a><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:LikeThisSong(".$parameters.");\"); return false;\">Like</a></span></div></div>";
			}
		}
		$var = "<h1>Suggestions</h1>";
		$var .= $a;
	}
    }
    oci_close($link);
    echo $var;
?>