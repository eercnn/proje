<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
error_reporting(E_ALL);
ob_start();
session_start();

if(!isset($_SERVER['CONTENT_TYPE']))
$_SERVER['CONTENT_TYPE']='';

if($_SERVER['CONTENT_TYPE']=='application/json')
{
$_POST = json_decode(file_get_contents('php://input'), true);
}
if($_POST){
foreach($_POST as $a=>$k){
	$a = htmlspecialchars(strip_tags($a));
	$k = htmlspecialchars(strip_tags($k));
	$_POST[$a] = $k;
}
}
class esari_term_global{
	public $ip;
	public $referer;
	public $mysql;
	public $user = ['u'=>'deneme','p'=>'deneme','key'=>'esari_term_global_test'	];
	public $pages= ['listele','ekle'];
	public $login=true;
	public $ajax = false;
	public function isn(string $s,$m=9, $mx = 14) {
		return preg_match('/^[0-9]{'.$m.','.$mx.'}\z/', $s);
	}
	public function validphone(string $phone,$m=9, $mx = 14) {
		if (preg_match('/^[+][0-9]/', $phone)) { 
			$count = 1;
			$phone = str_replace(['+'], '', $phone, $count); 
		}
		
		$phone = str_replace([' ', '.', '-', '(', ')'], '', $phone); 
	
		return $this->isn($phone, $m, $mx); 
	}
	
	public function ekle(){
		$ekle = true;
		$r='';
		if($this->login){
			list("fullname"=>$fullname,"email"=>$email,"phone"=>$phone) = $_POST;
			
			$evalid = filter_var($email, FILTER_VALIDATE_EMAIL);
			if(!$evalid){
				$r = 'wrong Email ';
				$ekle = false;
			}
			if(!stristr($fullname,' '))
			{
				$r = 'wrong full name';
				$ekle = false;
			}

			$pvalid=$this->validphone($phone);
			if(!$pvalid){
				$r = 'wrong phone number';
				$ekle=false;
			}
			if($ekle){
			$exp = explode(' ',$fullname);
			$surname = end($exp);
			array_pop($exp);
			$name = implode(' ', $exp);
			$req = $this->mysql->query("select * from datatable where fullname='{$fullname}' and email='{$email}' and phone='{$phone}'");
			if($req->num_rows>0){
				$r =  'daha once eklenmis';
			}
			else{
				$insert = $this->mysql->query("insert into datatable (fullname,email,phone,referer,ip) values ('{$fullname}','{$email}','{$phone}','{$this->referer}','$this->ip')");
				if($insert){
					$r= 'Ekleme basarili';
				}else
				{
					$r= 'Ekleme basarisiz';
				}
			}
			}
			if($this->ajax)
				echo json_encode(['r'=>$r]);
			else
			echo $r;
		}
	}
	public function giris(){
		if($this->user['u']==$_POST['u'] && $this->user['p']==$_POST['p'])
		{
			setcookie('key','esari_term_global_test',time()+(60*60*30*24));
			@header("Location:index.php");
		}
	}
	public function post(){
		
		switch($_POST['islem']){
			case 'giris':
				$this->giris();
			break;
			case'ekle':
				$this->ekle();
			break;
		
	}

	}
	public function loginsc(){
		echo '<form action="" method="post">
		<input type="text" name="u" id="u" placeholder="Kullanici Adiniz">
		<input type="password" name="p" id="p" placeholder="Sifre">
		<input type="hidden" name="islem" value="giris" id="giris">
		<input type="submit" value="Giris yap">
	</form>
	';
	}
	public function loginct(){
		if(isset($_COOKIE['key'])){
		$k =htmlspecialchars(strip_tags($_COOKIE['key']));
		if($k == $this->user['key']){
			$this->login= true;
		}
		}
	}
	public function listelev(){

		$sor = $this->mysql->query('select id,fullname,email,phone,referer from datatable order by id desc;')->fetch_all(MYSQLI_ASSOC);

		if($this->ajax)
		echo json_encode($sor);
		else
		{
			
			echo '   <table border=1>
			<thead>
				<tr>
				<th>id
				<th>fullname
				<th>email
				<th>phone
				<th>referer
				</tr>
			</thead><tbody>
			';
			foreach($sor as $a=>$k){
				extract($k);
				echo "<tr>
				<td>{$id}</td>
				<td>{$fullname}</td>
				<td>{$email}</td>
				<td>{$phone}</td>
				<td>{$referer}</td>
				</tr>";
			}
			echo '
		</tbody>
	</table>';
		}

	}
	public function eklev(){
		if(!$this->ajax){
echo '<form action="?s=ekle" method="post">
<input type="text" name="fullname" id="fullname" placeholder="Adini Giriniz">
<input type="text" name="email" id="email" placeholder="Email adresiniz">
<input type="tel" name="phone" id="phone" placeholder="Telefon Numarasini giriniz">
<input type="hidden" name="islem" value="ekle" id="ekle">
<input type="submit" value="Ekle">';
	}
}
	public function __construct()
	{
		if($_SERVER['CONTENT_TYPE']=='application/json'){

	
		$this->ajax = true;
	}
			$this->mysql = new MYSQLI('localhost','root','asd123123','datatable');
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->loginct();
			if(isset($_SERVER['HTTP_REFERER']));
				$this->referer = @$_SERVER['HTTP_REFERER'];
				
			if(!$this->login)
				echo $this->loginsc();
			else{
				if(!$this->ajax){
				foreach($this->pages as $a=>$k){
					echo "<a style='padding:15px;text-decoration:none;font-size:15px;font-weight:bold;color:white;background:black;margin:3px;display:inline-block;' href='?s={$k}' >{$k}</a>";
				}
			}
				if(!isset($_GET['s']))
				$_GET['s'] = 's';
				switch($_GET['s']){
					default:
						$this->listelev();
					break;
					case'listele':
						$this->listelev();
					break;
					case'ekle':
						$this->eklev();
					break;
				}
			}
			if($_POST){
				$this->post();
			}
	
	}
}

$etg = new esari_term_global();
?>

