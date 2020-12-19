<?PHP
		
	/*	$dbConnection = new mysqli("localhost", "almaelvl_govotelive", "KV0;Vl9wm*]a", "almaelvl_govotelive");
		
		if (mysqli_connect_errno()) {
            echo "3";
            exit();
        }*/
        
        $db_servername = "localhost";
        $db_username = "root";//"almaelvl_govotelive";
        $db_password = "";//"KV0;Vl9wm*]a";
        $db_name = "evoting";//"almaelvl_govotelive";
        
        // Create connection
        $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM users WHERE voted = 0 and role = 2";
        $result = $conn->query($sql);
        
        
        $send_sms_username = "";
		$send_sms_mobile = "";
		$vc_token_rnd = 0;
		$found = 0;
		
       
		while ($row = mysqli_fetch_assoc($result)) {
		
			$send_sms_mobile = "972".$row['mobile'];
			$vc_token_rnd = mt_rand(100000, 999999);
			$vc_token_msg = urlencode ("نذكر بالمشاركة بالانتخابات");//$vc_token_rnd;//."%0a%0aOfflineSMS%0ahttps://cutt.ly/crV6aWo";
			$vc_token = password_hash($vc_token_rnd, PASSWORD_BCRYPT);//mt_rand(100000, 999999);
					
			$result = file_get_contents("https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=offlinesms2020&to=".$send_sms_mobile."&message=".$vc_token_msg."&sender=OfflineSMS");
			
			
		}
    	
        $conn->close();
        //echo 1;
        
		
?>