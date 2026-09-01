<?php
$_SESSION = [];
session_destroy();

header("Location: " . url('/inicio'));
exit();
