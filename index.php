<?php

//require_once('vendor/autoload.php');
//use Screen\Capture;
set_time_limit(0);

require 'vendor/autoload.php'; 
use GuzzleHttp\Client;

function check_status_with_heroku($http_code,$host, $notify_flag)
{
	$disable_web_page_preview = null;
	$reply_to_message_id = null;
	$reply_markup = null;
	//$chat_id = "676415365";
	$chat_id = "449412519";
	$smp_telegram_token = "676415365:AAFZWGH-kaUBpPR9xspIYz8n5MUA5AQ-EYs";
	$url = "https://api.telegram.org/bot".$smp_telegram_token."/sendMessage";	
	
	$heroku_url = "https://pw-url-checker.herokuapp.com/check?url=".$http_code.$host;
	//$heroku_url = "https://livemonitor-api.herokuapp.com/check_url.php?host=".$host;
	
	$ch = curl_init($heroku_url);  
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);  
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
	if (curl_errno($ch))
	{
		echo '<pre>'.('Couldn\'t send request: ' . curl_error($ch)).'</pre>';
	}
	$data = json_decode($data, true);
	$httpcode = $data['status'];
	curl_close($ch);
	//echo '<pre>'.$httpmsg.'</pre>';	
    if($httpcode>=200 && $httpcode<300)
	{  
        //return $online_style;
		return 1;
    }
	else
	{
		if($notify_flag)
		{
			$host_msg = "<b>".$host."</b>". " went offline";
			$fields=array(
							'chat_id' => $chat_id,
							'parse_mode'=>'html',
							'text' => "Oops, ".$host_msg,
							'disable_web_page_preview' => urlencode($disable_web_page_preview),
							'reply_to_message_id' => urlencode($reply_to_message_id),
							'reply_markup' => urlencode($reply_markup)
						);
			//$url = "https://api.telegram.org/bot676415365:AAFZWGH-kaUBpPR9xspIYz8n5MUA5AQ-EYs/sendMessage?chat_id=449412519&text=test123456";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);	
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//$result = curl_exec($ch);		
			curl_close($ch);
		}
        //return $offline_style;
		return -99;
    }
}

function check_status($host)
{		
	$disable_web_page_preview = null;
	$reply_to_message_id = null;
	$reply_markup = null;
	//$chat_id = "676415365";
	$chat_id = "449412519";
	$smp_telegram_token = "676415365:AAFZWGH-kaUBpPR9xspIYz8n5MUA5AQ-EYs";
	$url = "https://api.telegram.org/bot".$smp_telegram_token."/sendMessage";
	
	$offline_style = "background-color:#e84118 !important; border-radius:5px";
	$online_style = "background-color:#4cd137 !important; border-radius:5px";
	$exception_style = "background-color:#2980b9 !important; border-radius:5px";
	    
    $ch = curl_init($host);  
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);  
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);  
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
    curl_close($ch);  
    if($httpcode>=200 && $httpcode<300)
	{  
        return $online_style;
    }
	else
	{
		$host_msg = "<b>".$host."</b>". " went offline";
		$fields=array(
						'chat_id' => $chat_id,
						'parse_mode'=>'html',
						'text' => "Oops, ".$host_msg,
						'disable_web_page_preview' => urlencode($disable_web_page_preview),
						'reply_to_message_id' => urlencode($reply_to_message_id),
						'reply_markup' => urlencode($reply_markup)
					);
		//$url = "https://api.telegram.org/bot676415365:AAFZWGH-kaUBpPR9xspIYz8n5MUA5AQ-EYs/sendMessage?chat_id=449412519&text=test123456";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//$result = curl_exec($ch);		
		curl_close($ch);
        return $offline_style;
    }
}

/* function get_screenshot($host)
{
	$url = 'http://'.$host;
	$url = 'http://sayaarief.blogspot.my';
	
	$screenCapture = new Capture($url);	
	$screenCapture->setImageType('png');	
	
	//$screenCapture->jobs->setLocation('img');
	//echo $screenCapture->jobs->getLocation(); // -> /path/to/jobs/dir/
	
	$fileLocation = 'img';
	echo "<pre>";
	print_r($screenCapture);
	echo "</pre>";
	$screenCapture->save($fileLocation); // Will automatically determine the extension type			

	echo $screenCapture->getImageLocation(); // --> /some/dir/test.png
	echo $url;
	die;

} */

/* function get_snapshot($host)
{
	$host = 'http://'.$host;	
	
	//call Google PageSpeed Insights API	
	//$google_api_url = "https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url=".$host."&key=AIzaSyCAQM8rZDg-LCt5hcVSlr75WFqJurK3kiE&screenshot=true";
	$google_api_url = "https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url=".$host."&filter_third_party_resources=true&locale=Malaysia&screenshot=true&snapshots=false&strategy=desktop&fields=screenshot";
	//$googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url=".$host."&screenshot=true");
	//$googlePagespeedData = file_get_contents($google_api_url);

	// 1. initialize
	$ch = curl_init();
	
	$timeout = 100;
	// 2. set the options, including the url
	curl_setopt($ch, CURLOPT_URL, $google_api_url);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);	
	
	// 3. execute and fetch the resulting HTML output
	$googlePagespeedData = curl_exec($ch);	
	
	// 4. free up the curl handle
	curl_close($ch);
	
	//decode json data
	$googlePagespeedData = json_decode($googlePagespeedData, true);	

	//screenshot data
	$screenshot = $googlePagespeedData['screenshot']['data'];	
	$screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);	
	
	return $screenshot;
} */

/* $array_site = array(
					array("url" => 'sayaarief.blogspot.my', "title" => 'arief', "icon" => "fa fa-building"),
					array("url" => 'amanz.my', "title" => 'amanz', "icon" => "fa fa-building"),
					array("url" => 'kfzoom.blogspot.my', "title" => 'kfzoom', "icon" => "fa fa-building"),
				); */

$array_site = array(
					array("pc"=>"www.", "url" => 'm3tech.com.my', "title" => 'M3 Tech', "icon" => "fa fa-building fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"", "url" => 'm3asia.com', "title" => 'M3 Asia', "icon" => "fa fa-credit-card fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"", "url" => 'm3online.com', "title" => 'M3 Online', "icon" => "fa fa-desktop fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"", "url" => 'getsnapps.com', "title" => 'GetSnapps', "icon" => "fa fa-android fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"", "url" => 'apps.m3tech.asia', "title" => 'Apps M3 Tech', "icon" => "fa fa-apple fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"http://", "url" => 'i3apps.com.my', "title" => 'i3 Apps', "icon" => "fa fa-mobile fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"", "url" => 'support.m3asia.com', "title" => 'Support M3 Asia', "icon" => "fa fa-child fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"http://www.", "url" => 'i3display.com', "title" => 'i3 Display', "icon" => "fa fa-play fa-2x", "snapshot"=>0, "notify"=>1),					
					array("pc"=>"https://", "url" => 'm3.i3teamworks.com/login.php', "title" => 'M3 i3tw', "icon" => "fa fa-universal-access fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"https://", "url" => 'oa.i3teamworks.com/login.php', "title" => 'OA i3tw', "icon" => "fa fa-universal-access fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"https://", "url" => 'intl.i3teamworks.com/login.php', "title" => 'INTL i3tw', "icon" => "fa fa-universal-access fa-2x", "snapshot"=>0, "notify"=>1),
					array("pc"=>"https://", "url" => 'cn.i3teamworks.cn/login.php', "title" => 'CN i3tw', "icon" => "fa fa-universal-access fa-2x", "snapshot"=>0, "notify"=>1),
				);
				
$array_snapshots = array();
/* foreach($array_site as $k=> $v)
{
	$check_snapshot = $v['snapshot'];
	if($check_snapshot)
	{
		$array_snapshots[$v['url']] =  get_snapshot($v['url']);
	}
} */

?>
<title>Live Monitor</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="moments.js"></script>
<script src="moment-timezone-with-data.js"></script>
<link rel="stylesheet" type="text/css" href="index.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
<meta http-equiv="refresh" content="1800;url=index.php">
<header class="rad-page-title"></header>
<body style="background-color: #15202c;">
<style type='text/css'>
	.frame {
    width: 100%;    
    border: 0;
	border-radius:5px;
    -ms-transform: scale(1);
    -moz-transform: scale(1);
    -o-transform: scale(1);
    -webkit-transform: scale(1);
    transform: scale(1);
    
    -ms-transform-origin: 0 0;
    -moz-transform-origin: 0 0;
    -o-transform-origin: 0 0;
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;
}

.server {
    padding: 15px;       
    border: 1px solid rgba(255, 255, 255, .1);
    border-radius: 5px;
	float:left;
	width:100%;
}

#first {
    width: 50px;
    float: left;
}

#second {    
    float: left;    
}
</style>
<main style="padding-left:10px">
<div class="row" style="color:white !important; width:100% !important">
	<?php 
		$offline_style = "border-color:#e84118 !important;";
		$online_style = "border-color:#4cd137 !important;";
		$exception_style = "border-color:#2980b9 !important;";
		foreach($array_site as $k=> $v)
		{ ?>			
			<div class="col-lg-3 col-sm-6 col-xs-12">
				<?php
					//$x = check_status($v['url']);
					$result = check_status_with_heroku($v['pc'],$v['url'], $v['notify']);
					if($result == 1)
					{
						$x = $online_style;
					}
					else
					{
						$x = $offline_style;
					}
					$check_snapshot = $v['snapshot'];
				?>
				<!--<div class="rad-info-box" style="<?php echo $x; ?>">-->
				<div class="server" style="<?php echo $x; ?>">
					<div id="first">
						<i class="<?php echo $v['icon']; ?>"></i>
					</div>
					<div style="float:left">
					<?php if($result == 1) { ?>
						<span class="heading" style="font-size:10px !important; color:#4cd137"><b style="font-size:15px !important">&#8226;</b> <b>ONLINE</b></span>
					<?php } else { ?>
						<span class="heading" style="font-size:10px !important; color:#e84118"><b style="font-size:15px !important">&#8226;</b> <b>OFFLINE</b></span>
					<?php } ?>
					<br clear='all'/>
					<a href="<?php echo $v['pc']."".$v['url']; ?>" style="font-size:13px !important;"><?php echo $v['pc']."".$v['url']; ?></a>
					<br clear='all'/>
					<span class="value" style="font-size:15px !important;"><span><?php echo $v['title']; ?></span></span>
					</div>
				</div>
				<br clear='all'/><br clear='all'/>
			</div>
		<?php 
		}
	?>
	<i id="timestamp" style="right: 0;bottom: 0;position: fixed;">		
	</i>
</div>
</main>
<script type="text/javascript">
	$(document).ready(function()
	{
		//var dt = new Date();
		//var utcDate = dt.toUTCString();
		
		var dt = new Date();
		var utcDate = moment.tz(dt, "Asia/Kuala_Lumpur").format('YYYY-MM-DD HH:mm:ss');
		
		document.getElementById('timestamp').innerHTML = "Last checked on "+utcDate;
		$('.heading').on('click',function()
		{
			var url = 'http://'+$(this).text();			
			window.open(url, 'name'); 			
		});
	});
</script>
</body>
