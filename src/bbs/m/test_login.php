<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Mobile Login Test</h1>";
echo "<p>PHP 버전: " . phpversion() . "</p>";
echo "<p>현재 시간: " . date('Y-m-d H:i:s') . "</p>";

echo "<h2>GET 파라미터:</h2>";
var_dump($_GET);

echo "<h2>파일 확인:</h2>";
echo "<p>_common_guest.php 존재: " . (file_exists('_common_guest.php') ? 'YES' : 'NO') . "</p>";
echo "<p>_head.php 존재: " . (file_exists('_head.php') ? 'YES' : 'NO') . "</p>";
echo "<p>all_login.php 존재: " . (file_exists('all_login.php') ? 'YES' : 'NO') . "</p>";

echo "<h2>Common 파일 테스트:</h2>";
try {
    include_once("_common_guest.php");
    echo "<p>_common_guest.php 포함 성공!</p>";
    
    if (isset($iw)) {
        echo "<p>iw 배열 존재함</p>";
        echo "<p>level: " . ($iw['level'] ?? 'undefined') . "</p>";
        echo "<p>store: " . ($iw['store'] ?? 'undefined') . "</p>";
        echo "<p>group: " . ($iw['group'] ?? 'undefined') . "</p>";
    } else {
        echo "<p>iw 배열이 설정되지 않음</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
} catch (Error $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<h2>실제 로그인 페이지 include 테스트:</h2>";
try {
    if ($iw['level'] != "guest") {
        echo "<p>이미 로그인됨: " . $iw['level'] . "</p>";
    } else {
        echo "<p>게스트 상태 - 로그인 폼을 표시해야 함</p>";
    }
    
    echo "<p>all_login.php 파일 크기: " . filesize('all_login.php') . " bytes</p>";
    
    // all_login.php의 첫 10줄 읽기
    $lines = file('all_login.php', FILE_IGNORE_NEW_LINES);
    echo "<h3>all_login.php 첫 10줄:</h3>";
    echo "<pre>";
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo htmlspecialchars($lines[$i]) . "\n";
    }
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
} catch (Error $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p>테스트 완료</p>";
?> 