<?

session_start();
session_register('hosts');
session_register('prefs');
global $hosts , $prefs;

$act=$_GET[act];

IF ( $act == "info") {
phpinfo();
die;
}

function get_microtime(){ 
$tmp=split(" ",microtime()); 
$rt=$tmp[0]+$tmp[1]; 
return $rt; 
}
 
class status
{ var $server;
 var $port_pop = 110;
 var $port_imap = 143;
 var $port_smtp = 25;
 var $port_ftp = 21;
 
 function OKAY()
 { switch ($this->tip)
 {
 case pop3:
 if(ereg("^\+OK", $this->answer));
 case imap:
 if(ereg("^\* OK", $this->answer));
 case smtp:
 		if(ereg("^\220", $this->answer));
 case ftp:
 			if(ereg("^\220", $this->answer));
 }
 return true;
 }
 
 function BYE()
 {
 switch ($this->tip)
 {
 case pop3:
 if(ereg("^\+OK", $this->answer));
 case imap:
 if(ereg("^\* BYE", $this->answer));
 case smtp:
 		if(ereg("^\221", $this->answer));
 case ftp:
 		if(ereg("^\221", $this->answer));
 }
 return true;
 }
 function CONNECT()
 {
 switch ($this->tip)
 {
 case pop3:
 if($this->sockfd =@fsockopen($this->server, $this->port_pop)) {
 if($this->OKAY()) {
 return true;
 }
 }
 return false;
 case imap:
 if($this->sockfd = @fsockopen($this->server, $this->port_imap)) {
 if($this->OKAY()) {
 return true;
 }
 }
 return false;
 case smtp:
 if($this->sockfd = @fsockopen($this->server, $this->port_smtp)) {
 if($this->OKAY()) {
 return true;
 }
 }
 			return false;
 case ftp:
 if($this->sockfd = @fsockopen($this->server, $this->port_ftp)) {
 if($this->OKAY()) {
 return true;
 }
 }
 }
 return true;
 }
 function DISCONNECT()
 {
 switch ($this->tip)
 {
 case pop3:
 fwrite($this->sockfd, "QUIT\r\n");
 $this->BYE();
 case imap:
 fwrite($this->sockfd, "A LOGOUT\r\n");
 $this->BYE();
 case smtp:
 fwrite($this->sockfd, "QUIT\r\n");
 $this->BYE();
 case ftp:
 fwrite($this->sockfd, "QUIT\r\n");
 $this->BYE();
 }
 fclose($this->sockfd);
 }
}
$cpuinfo = file("/proc/cpuinfo");
for ($i = 0; $i < count($cpuinfo); $i++) {
 list($item, $data) = split(":", $cpuinfo[$i], 2);
 $item = chop($item);
 $data = chop($data);
 if ($item == "processor") {
 $total_cpu++;
 $cpu_info = $total_cpu;
 }
 if ($item == "vendor_id") { $cpu_info .= $data; }
 if ($item == "model name") { $cpu_info .= $data; }
 if ($item == "cpu MHz") {
 $cpu_info .= " " . floor($data);
 $found_cpu = "yes";
 }
 if ($item == "cache size") { $cache = $data;}
 if ($item == "bogomips") { $bogomips = $data;}
}
 
if($found_cpu != "yes") { $cpu_info .= " unknown"; }
$cpu_info .= " MHz\n";

IF ( $act == "cpu") {
print_r($cpu_info);
die;
}

$memory_info = " " . round(filesize("/proc/kcore") / 1024 / 1024) . " MB\n";
$meminfo = file("/proc/meminfo");
for ($i = 0; $i < count($meminfo); $i++) {
 list($item, $data) = split(":", $meminfo[$i], 2);
 $item = chop($item);
 $data = chop($data);
 if ($item == "MemTotal") { $total_mem =$data; }
 if ($item == "MemFree") { $free_mem = $data; }
 if ($item == "SwapTotal") { $total_swap = $data; }
 if ($item == "SwapFree") { $free_swap = $data; }
 if ($item == "Buffers") { $buffer_mem = $data; }
 if ($item == "Cached") { $cache_mem = $data; }
 if ($item == "MemShared") {$shared_mem = $data; }
}
$freeKB = str_replace("kb", "", chop($free_mem));
$freeMB = round($freeKB / 1024);

IF ( $act == "mem") {
print_r($meminfo);
die;
}

$used_mem = ( $total_mem - $free_mem );
$used_swap = ( $total_swap - $free_swap );
$percent_free = round( $free_mem / $total_mem * 100 );
$percent_used = round( $used_mem / $total_mem * 100 );
$percent_swap = round( ( $total_swap - $free_swap ) / $total_swap * 100 );
$percent_swap_free = round( $free_swap / $total_swap * 100 );
$percent_buff = round( $buffer_mem / $total_mem * 100 );
$percent_cach = round( $cache_mem / $total_mem * 100 );
$percent_shar = round( $shared_mem / $total_mem * 100 );


$THOST = str_replace("WWW.", "" , strtoupper($_SERVER[HTTP_HOST]));
$time = (exec("date"));
//$uptime_info = trim(exec("uptime")) . "\n\n";
$uptime = `uptime`; 
$top_info = explode("," , $uptime);
$Mtime = explode(" " , $top_info[0]);
$MUsers = explode(" " , $top_info[2]);
$MLoad1 = explode(":" , $top_info[3]);

IF ( $act == "uptime") {
print_r($uptime);
die;
}

$Ktimer = get_microtime(); 
for($i = 0; $i == 10000; $i++) 
	{ 
	$K = 1; 
	$K = $K * 10000; 
	} 
$Ktimerf = get_microtime(); 
$Kloop = ($Ktimer - $Ktimerf) * -10000;
$Kloop = round($Kloop * 100 );
#$IP = gethostbyname($THOST);
$IP = gethostbyname($THOST);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!--/////\....../////  NAVE Internet e Comunicacao
.../////\\\....///// Omar Ballabio web@nave.net
../////..\\\..///// www.nave.net
./////....\\\///// Sao Paulo Brasil
///// .....\///// 55 + 11 5594 2794 -->
<html><head><title>ADMIN</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<meta name="Author" content="Omar Ballabio - web@nave.net"> 
<meta name="Description" content="nave"> 
<meta name="Keywords" content="nave">
<style>
TD {
	font-family : Verdana;
	font-size : 10px;
}
.ph{font-family : Arial; font-size : 8pt; color : eee;}
.ff0{font-family : Arial; font-size : 8pt; color : ff0;}
.fff{font-family : Arial; font-size : 8pt; color : fff;}
.f00{font-family : Arial; font-size : 8pt; color : f00;}
.0f0{font-family : Arial; font-size : 8pt; color : 0f0;}
input{font-size:6pt;}
.mini { font-size : 8pt; font-family :Arial, Helvetica, sans-serif;}
.top{font-family : Arial; font-size : 8pt; color : 008; background-color : fc0; font-weight : bold; text-align : center;}
</style>
</head>
<body bgcolor="#000000" link="#00FFFF" vlink="#00FFFF" leftmargin=0 topmargin=0>
<font face="Verdana, Arial" size="1" color="#eeeeee">
<?
IF ( $act == "custom") {
$Chosts = explode(" " , $hosts);
$Numhosts = count($Chosts);
print '
<form method="post" action="' .$PHP_SELF. '" class="mini">
<input type="Hidden" name="act" value="add">
<textarea name="Newhosts" cols="45" rows="' .$Numhosts. '">';
$t = 0;
while($t <= $Numhosts)
{
echo chop($Chosts[$t]);
echo "\n";
$t++;
}
print '
</textarea>
<br>
<input type="Submit" value="ADD"> ';
die;
}
?>

<table width="99%" cellpadding="1" cellspacing="0"><tr>
<td class="top"><? echo $THOST ?></td></tr><tr><td class="fff" align="center"><? echo $IP ?></td>
</tr></table>

<table width="99%"  border="0" cellpadding="1" cellspacing="1">
<tr><td class="fff" >Time</td><td class="ff0"><? echo $Mtime[2] ?></td></tr>
<tr><td class="fff" >Upime</td><td class="ff0"><? echo $Mtime[5] ?> <? echo $Mtime[6] ?></td></tr>
<tr><td class="fff" >Users</td><td class="ff0"><? echo $MUsers[2] ?></td></tr>
<tr><td class="fff" >Load 1'</td><td class="ff0"><? echo $MLoad1[1] ?></td></tr>
<tr><td class="fff" >Load 5'</td><td class="ff0"><? echo $top_info[4] ?></td></tr>
<tr><td class="fff">Load 15'</td><td class="ff0"><? echo $top_info[5] ?></td></tr>
<tr><td class="ph">Bmips</td>
<? 
if($bogomips > 1700) print '<td class="0f0"> ' .$bogomips. '</td></tr>';
else if($bogomips < 1200) print '<td class="f00"> ' .$bogomips. '</td></tr>';
else print '<td class="ff0"> ' .$bogomips. '</td></tr>';

if($Kloop > 1.8) $FKloop =  '<td class="f00"> ' .$Kloop;
else if($Kloop < 1.3) $FKloop = '<td class="0f0"> ' .$Kloop;
else $FKloop = '<td class="ff0"> ' .$Kloop;


print'
<tr><td class="ph">Kloop</td>
<!-- Kloop -->'.$FKloop.'
</td></tr>
';
?>
<tr><td class="ph">Cache</td><td class="ff0"><? echo $cache ?></td></tr>
<tr><td class="ph">RAM</td><td class="ff0"><? echo $memory_info ?></td></tr>
<tr><td class="ph">Free</td><?
if($freeMB > 10) print '<td class="0f0"> ' .$freeMB. '</td></tr>';
else if($freeMB < 5) print '<td class="f00"> ' .$freeMB. '</td></tr>';
else print '<td class="ff0"> ' .$freeMB. '</td></tr>';
 ?>
<tr><td class="ph">Buffered</td><td class="ff0"><? echo $percent_buff?>%</td></tr>
<tr><td class="ph">Swap</td><?
if($percent_swap_free > 50) print '<td class="0f0"> ' .$percent_swap_free. '% Free</td></tr>';
else if($percent_swap_free < 10) print '<td class="f00"> ' .$percent_swap_free. '% Free</td></tr>';
else print '<td class="ff0"> ' .$percent_swap_free. '% free</td></tr>';


$services = array ( "smtp"=>"Smtp" , "pop3"=>"POP3" , "imap"=>"Imap" , "ftp"=>"Ftp" );
$status = new status;
$status->server = "localhost";
while (list ($key, $val) = each ($services))
{
 $status->tip = "$key"; 
 if ($status->CONNECT()) { 
 $status->DISCONNECT(); 
 $Mesaj = "<span class='0f0'>OK</span>"; 
 } else {
 $Mesaj = "<span class='f00'>NO</span>"; 
 }
echo "<tr class='ph'><td>$val</td><td>$Mesaj</td></tr>\n"; 
} 
echo "</table>\n";


if($act == "add"){
$Newhosts = str_replace("\n" , " " ,$Newhosts);
$Newhosts =  str_replace("\r" , " " ,$Newhosts);
$GLOBALS['hosts'] = str_replace("  " , " " ,$Newhosts);
}

if($hosts)
{
$Chosts = explode(" " , chop($hosts));
$Numhosts = count($Chosts) -1;

	$t = 0 ;
 	while ($t <= $Numhosts)
	{
			$url = chop($Chosts[$t]);
			$IP = gethostbyname($url);
 
			$timer = get_microtime(); 
			$connect = fsockopen($url,80,&$errno, &$errstr,10);

			if (!$connect)
				{
				$scal = '<span class="f00">OFF</span>';
				}
			else 
				{
			$timerf = get_microtime(); 
					$contime = ($timer - $timerf) * -1000; 
					$contime = round($contime);
				if($contime < 300){ $scal = '<span class="0f0">'. $contime .'</span>';}
				else if($contime > 500){ $scal = '<span class="f00">'. $contime .'</span>';}
				else $scal = '<span class="ff0">'. $contime .'</span>' ;
			fclose ($connect);
				}
			$Ihost = "<font color='#ff0000'>?</font>";
			
			if (substr($IP,0,12) == "200.202.124.") $Ihost = "<font color='#ff00ff'>T</font>\n";
			elseif (substr($IP,0,12) == "200.190.159.") $Ihost = "<font color='#00ffff'>S</font>\n";
			elseif (substr($IP,0,11) == "216.187.79.")  $Ihost = "<font color='#ffff00'>W</font>\n";
			
			
			print "<table border=0 cellpadding=0 cellspacing=0><tr title=".$IP."><td width=15>" .$Ihost. " </td></td>\n<td width=35>" .$scal. "</td><td><a href='http://$url'>" .$url. "</a></td>\n</tr></table>";
	
				
 	$t++;
 	}
 }

		print "\n<br>\n
		<a href='" .$PHP_SELF. "?act=cpu' target='_blank'>CPU</a>
		<a href='" .$PHP_SELF. "?act=mem' target='_blank'>MEM</a>
		<a href='" .$PHP_SELF. "?act=uptime' target='_blank'>UP</a>
		<a href='" .$PHP_SELF. "?act=info' target='_blank'>PHPinfo</a>
		<a href='" .$PHP_SELF. "?act=custom' target='_blank'>Custom</a>
		<a href='javascript:history.go(0);'>Refresh</a>";

 ?> 

 </font> 
</body> 
</html>
