<?PHP
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "bs";
$db = "bs_";
$url = "http://localhost/bs/";
$mysqli = new mysqli($servidor,$usuario,$senha,$banco);
mysqli_set_charset($mysqli,"utf8");
if(mysqli_connect_errno()) trigger_error(mysqli_connect_error());
