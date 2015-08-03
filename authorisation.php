<?php
session_start();
unset ( $_SESSION['authorisation'] );

echo '<div align="center">'."\n";
echo '<form name="authorisation" action="main.php" method="get">'."\n";
echo '<table cellpadding="5">'."\n";
echo '   <tr>'."\n";
echo '     <td align="center">Логин:</td>'."\n";
echo '     <td><input type=text name="login" value=""></td>'."\n";
echo '   </tr>'."\n";
echo '   <tr>'."\n";
echo '     <td align="center">Пароль:</td>'."\n";
echo '     <td><input type=password name="password" value=""></td>'."\n";
echo '   <tr>'."\n";
echo '     <td align="center" colspan="2"><input type=submit value="Войти в базу"></td>'."\n";
echo '   </tr>'."\n";
echo '</table>'."\n";
echo '</form>'."\n";
echo '</div>'."\n";
?>
