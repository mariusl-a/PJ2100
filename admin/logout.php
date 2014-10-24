<?php
setCookie("bib_sess", "", time()-3600);
header("Location: login.php");
?>