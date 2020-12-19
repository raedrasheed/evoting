<?PHP
    
		$username = $_POST['username'];	
		
	/*	$dbConnection = new mysqli("localhost", "almaelvl_govotelive", "KV0;Vl9wm*]a", "almaelvl_govotelive");
		
		if (mysqli_connect_errno()) {
            echo "3";
            exit();
        }*/
        
        $db_servername = "localhost";
        $db_username = "almaelvl_govotelive";
        $db_password = "KV0;Vl9wm*]a";
        $db_name = "almaelvl_govotelive";
        
        // Create connection
        $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM users WHERE username='".$username."'";
        $result = $conn->query($sql);
        
        
        $send_sms_username = "";
		$send_sms_mobile = "";
		$vc_token_rnd = 0;
		$found = 0;
		
        
        if ($result->num_rows == 1) {
            // output data of each row
            $found = 1;
            $row = $result->fetch_assoc();
            $send_sms_mobile = "972".$row['mobile'];
            $vc_token_rnd = mt_rand(100000, 999999);
            $vc_token_msg = $vc_token_rnd;//."%0a%0aOfflineSMS%0ahttps://cutt.ly/crV6aWo";
    		$vc_token = password_hash($vc_token_rnd, PASSWORD_BCRYPT);//mt_rand(100000, 999999);
    		$new_login_count = $row['login_count'] + 1;
    		$new_login_date = date("Y-m-d");
    		if($row['login_date'] == $new_login_date){
    		    if($new_login_count > 3){
						$found = 2;
					}else{
							 $sql = 'UPDATE users SET password = "'.$vc_token.'" , login_count = '.$new_login_count.' , login_date = "'.$new_login_date.'" WHERE username ="'. $username.'"'; 
							 $result1 = $conn->query($sql);
					}
    		}else{
					$new_login_count = 1;
					$sql = 'UPDATE users SET password = "'.$vc_token.'" , login_count = '.$new_login_count.' , login_date = "'.$new_login_date.'" WHERE username ="'. $username.'"';
					$result2 = $conn->query($sql);
    		}
    		
    		if($found == 1){
    			// create a new cURL resource
    			
    			
                $result = file_get_contents("https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=offlinesms2020&to=".$send_sms_mobile."&message=".$vc_token_msg."&sender=OfflineSMS");
    
                //'https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=GJiZsa&to=972599345342&message=testmessage&sender=OfflineSMS');
    			/*$ch = curl_init();
    			
    			$send_sms_url="https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=GJiZsa&to=".$send_sms_mobile."&message=".$vc_token_msg."&sender=OfflineSMS";
    
    			// set URL and other appropriate options
    			curl_setopt($ch, CURLOPT_URL, $send_sms_url);
    			curl_setopt($ch, CURLOPT_HEADER, 0);
    
    			// grab URL and pass it to the browser
    			$wer = curl_exec($ch);
    
    			// close cURL resource, and free up system resources
    			curl_close($ch);
    			*/
    			
    			echo $found;
    		}
    		else echo $found;
    		
           //echo $send_sms_mobile;
           
        }else {
            echo  $found;
        }
        $conn->close();
        //echo 1;
        
		
?>