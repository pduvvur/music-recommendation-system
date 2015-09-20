<?php 
$uname = $_GET['email_address'];
$pass = $_GET['password'];
$link = oci_connect('system', 'Nitish91', '');
$var = "";
$a = "<h1>Random track</h1>";
    if (!$link)
    {
        die('Could not connect to Oracle: ');
    }
    else
    {
    	$stmt = oci_parse($link,'select * from user_system where user_id = \''.$uname.'\'');   
        $resultset = oci_execute($stmt);
	$flag = 'false';
        while($resultset = oci_fetch_array($stmt,OCI_BOTH))
        {
			$flag = 'true';
			if($resultset['PASSWORD'] == $pass){
				$delim = "---";
				$randomorsuggested = "Random";
				
				//$stmt2 = oci_parse($link,'select song_title from song where song_id in(select song_id from user_to_song where user_id = \''.$uname.'\') and rownum <=5'); 
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
				
				$stmt2 = oci_parse($link, 'select max(count(cluster_id)) as count from user_to_song where user_id=\''.$uname.'\' group by cluster_id');
				$resultset2 = oci_execute($stmt2);
				$count=0;
				while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
				{
					$count = $resultset2['COUNT'];
				}
				$a .= $delim."<h1>Suggestions</h1>";
				$ToBeRefreshed = "Suggestions";
				$RefreshSection = '\''.$ToBeRefreshed.'\'';
				$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:RefreshSection(".$RefreshSection."); return false;\">Refresh</a></span></div></div>";
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
					while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
					{
						$songid = $resultset2['SONG_ID'];
						$clusterid = $resultset2['CLUSTER_ID'];
						$parameters = '\''.$songid.'\',\''.$clusterid.'\',\''.$uname.'\',\''.$pass.'\',\''.$randomorsuggested.'\'';
						$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><a style=\"color:#24211d\" href=\"#\" onclick=\"return false;\">".$resultset2['SONG_TITLE']."</a><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:LikeThisSong(".$parameters.");\"); return false;\">Like</a></span></div></div>";
					}
				}
				
				$var= $a.$delim."<h2> Welcome ".$resultset['FIRST_NAME']."</h2>";	
			}
			else
				$var = "<h1>Random Track</h1>---<h1>Suggestions</h1>---<h2>Invalid Credentials</h2>
              			<div class=\"left_col_box\"><form method=\"get\" action=\"#\" onsubmit=\"return ajaxFunction();\"><div class=\"form_row\">User ID
                      		<input class=\"inputfield\" name=\"email_address\" type=\"text\" id=\"email_address\"/></div>
                    		<div class=\"form_row\"><label>Password</label><input class=\"inputfield\" name=\"password\" type=\"password\" id=\"password\"/></div>
                      		<input class=\"button\" type=\"button\" name=\"Submit\" value=\"Login\" onclick=\"ajaxFunction()\"/></form>";
        }


	if($flag == 'false')
	{	
    		$stmt = oci_parse($link,'insert into user_system(user_id,first_name,password) values (\''.$uname.'\',\''.$uname.'\',\''.$pass.'\')');   
        	$resultset = oci_execute($stmt);
		oci_commit($link);
		$delim = "---";
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
		$stmt2 = oci_parse($link, 'select max(count(cluster_id)) as count from user_to_song where user_id=\''.$uname.'\' group by cluster_id');
		$resultset2 = oci_execute($stmt2);
		$count=0;
		while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
		{
			$count = $resultset2['COUNT'];
		}

		$a .= $delim."<h1>Suggestions</h1>";
		$ToBeRefreshed = "Suggestions";
		$RefreshSection = '\''.$ToBeRefreshed.'\'';
		$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:RefreshSection(".$RefreshSection."); return false;\">Refresh</a></span></div></div>";
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
			while($resultset2 = oci_fetch_array($stmt2,OCI_BOTH))
			{
				$songid = $resultset2['SONG_ID'];
				$clusterid = $resultset2['CLUSTER_ID'];
				$parameters = '\''.$songid.'\',\''.$clusterid.'\',\''.$uname.'\',\''.$pass.'\',\''.$randomorsuggested.'\'';
				$a .= "<div class=\"topdownload_box\"><div class=\"title_singer\"><a style=\"color:#24211d\" href=\"#\" onclick=\"return false;\">".$resultset2['SONG_TITLE']."</a><span class=\"download_button\"><a class=\"download_button\" href=\"javascript:LikeThisSong(".$parameters.");\"); return false;\">Like</a></span></div></div>";
			}
		}	
		$var= $a.$delim."<h2> Welcome new user ".$uname."</h2>";	
	}
    }
    oci_close($link);
	echo $var;
?>