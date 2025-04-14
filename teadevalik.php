<?php
require("conf.php");
global $yhendus;
if(isSet($_REQUEST["uusleht"])){
    $kask=$yhendus->prepare("INSERT INTO lehed (pealkiri, sisu) VALUES (?, ?)");
    $kask->bind_param("ss", $_REQUEST["pealkiri"], $_REQUEST["sisu"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
if(isSet($_REQUEST["kustutusid"])){
    $kask=$yhendus->prepare("DELETE FROM lehed WHERE id=?");
    $kask->bind_param("i", $_REQUEST["kustutusid"]);
    $kask->execute();
}
if(isSet($_REQUEST["muutmisid"])){
    $kask=$yhendus->prepare("UPDATE lehed SET pealkiri=?, sisu=? WHERE id=?");
    $kask->bind_param("ssi", $_REQUEST["pealkiri"], $_REQUEST["sisu"],
        $_REQUEST["muutmisid"]);
    $kask->execute();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Teated lehel</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <style type="text/css">
        #menyykiht{
            float: left;
            padding-right: 30px;
        }
        #sisukiht{
            float:left;
        }
        #jalusekiht{
            clear: left;
        }
    </style>
</head>
<body>
<div id="menyykiht">
    <h2>Teated</h2>
    <ul>
        <?php
        $kask=$yhendus->prepare("SELECT id, pealkiri FROM lehed");
        $kask->bind_result($id, $pealkiri);
        $kask->execute();
        while($kask->fetch()){
            echo "<li><a href='$_SERVER[PHP_SELF]?id=$id'>".htmlspecialchars($pealkiri)."</a></li>";
        }
        ?>
    </ul>
    <a href='<?="$_SERVER[PHP_SELF]?lisamine=jah" ?>'>Lisa ...</a>
</div>
<div id="sisukiht">
    <?php
    if(isSet($_REQUEST["id"])){
        $kask=$yhendus->prepare("SELECT id, pealkiri, sisu FROM lehed WHERE id=?");
        $kask->bind_param("i", $_REQUEST["id"]);
        $kask->bind_result($id, $pealkiri, $sisu);
        $kask->execute();
        if($kask->fetch()){
            if(isSet($_REQUEST["muutmine"])){
                echo "
<form action='$_SERVER[PHP_SELF]'>
<input type='hidden' name='muutmisid' value='$id'/>
<h2>Teate muutmine</h2>
<dl>
<dt>Pealkiri:</dt>
<dd>
<input type='text' name='pealkiri' value='".
                    htmlspecialchars($pealkiri)."'/>
 </dd>
 <dt>Teate sisu:</dt>
 <dd>
 <textarea rows='20' cols='30' name='sisu'>".
                    htmlspecialchars($sisu)."</textarea>
 </dd>
 </dl>
 <input type='submit' value='Muuda' />
 </form>
 ";
            } else {
                echo "<h2>".htmlspecialchars($pealkiri)."</h2>";
                echo htmlspecialchars($sisu);
                echo "<br /><a href='$_SERVER[PHP_SELF]?kustutusid=$id'>kustuta</a> ";
                echo "<a href='$_SERVER[PHP_SELF]?id=$id&amp;muutmine=jah'>muuda</a>";
            }
        } else {
            echo "Vigased andmed.";
        }
    }
    if(isSet($_REQUEST["lisamine"])){
        ?>
        <form action='<?=$_SERVER["PHP_SELF"] ?>'>
            <input type="hidden" name="uusleht" value="jah" />
            <h2>Uue teate lisamine</h2>
            <dl>
                <dt>Pealkiri:</dt>
                <dd>
                    <input type="text" name="pealkiri" />
                </dd>
                <dt>Teate sisu:</dt>
                <dd>
                    <textarea rows="20" cols="30" name="sisu"></textarea>
                </dd>
            </dl>
            <input type="submit" value="sisesta" />
        </form>
        <?php
    }
    ?>
</div>
<div id="jalusekiht">
    Lehe tegi Jaagup
</div>
</body>
</html>
<?php
$yhendus->close();
?>

