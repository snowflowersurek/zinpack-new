<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Simple Test</h1>";
echo "<p>PHP 버전: " . phpversion() . "</p>";
echo "<p>현재 시간: " . date('Y-m-d H:i:s') . "</p>";

echo "<h2>GET 파라미터:</h2>";
var_dump($_GET);

echo "<h2>서버 정보:</h2>";
echo "<p>SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'undefined') . "</p>";
echo "<p>REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'undefined') . "</p>";

echo "<h2>파일 시스템 확인:</h2>";
echo "<p>현재 디렉토리: " . getcwd() . "</p>";
echo "<p>_common.php 존재: " . (file_exists('_common.php') ? 'YES' : 'NO') . "</p>";
echo "<p>../../include/common.php 존재: " . (file_exists('../../include/common.php') ? 'YES' : 'NO') . "</p>";

// 간단한 common.php include 테스트
echo "<h2>Common.php 테스트:</h2>";
try {
    $iw_path = "../..";
    echo "<p>iw_path 설정: $iw_path</p>";
    
    if (file_exists("$iw_path/include/common.php")) {
        echo "<p>common.php 파일 존재함</p>";
        
        // GET 파라미터 기본값 설정
        if (empty($_GET['ep'])) {
            $_GET['ep'] = "ep1822322763609cab5915c89";
        }
        if (empty($_GET['gp'])) {
            $_GET['gp'] = "all";
        }
        if (empty($_GET['type'])) {
            $_GET['type'] = "dashboard";
        }
        
        echo "<p>기본 파라미터 설정 완료</p>";
        echo "<p>ep: " . $_GET['ep'] . "</p>";
        echo "<p>gp: " . $_GET['gp'] . "</p>";
        echo "<p>type: " . $_GET['type'] . "</p>";
        
        include_once("$iw_path/include/common.php");
        echo "<p>common.php include 성공!</p>";
        
        if (isset($iw)) {
            echo "<p>iw 배열 존재함</p>";
            echo "<p>level: " . ($iw['level'] ?? 'undefined') . "</p>";
            echo "<p>store: " . ($iw['store'] ?? 'undefined') . "</p>";
            echo "<p>group: " . ($iw['group'] ?? 'undefined') . "</p>";
        } else {
            echo "<p>iw 배열이 설정되지 않음</p>";
        }
    } else {
        echo "<p>common.php 파일이 존재하지 않음</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
} catch (Error $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p>테스트 완료</p>";
?> 