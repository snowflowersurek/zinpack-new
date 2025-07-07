<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. 시작<br>";

try {
    echo "2. _common.php 포함 전<br>";
    include_once("_common.php");
    echo "3. _common.php 포함 후<br>";
    
    echo "4. iw 배열 확인:<br>";
    echo "level: " . (isset($iw['level']) ? $iw['level'] : 'undefined') . "<br>";
    echo "store: " . (isset($iw['store']) ? $iw['store'] : 'undefined') . "<br>";
    echo "group: " . (isset($iw['group']) ? $iw['group'] : 'undefined') . "<br>";
    
    echo "5. _head.php 포함 전<br>";
    include_once("_head.php");
    echo "6. _head.php 포함 후<br>";
    
} catch (Exception $e) {
    echo "오류 발생: " . $e->getMessage() . "<br>";
    echo "파일: " . $e->getFile() . "<br>";
    echo "줄: " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "심각한 오류 발생: " . $e->getMessage() . "<br>";
    echo "파일: " . $e->getFile() . "<br>";
    echo "줄: " . $e->getLine() . "<br>";
}

echo "7. 완료<br>";
?> 