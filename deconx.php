<?php
// بدء الجلسة
session_start();

// تدمير جميع بيانات الجلسة
session_unset();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
header("Location: index.php");
exit;
?>
