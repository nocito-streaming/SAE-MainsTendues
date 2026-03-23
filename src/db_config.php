<?php
$host = 'localhost';
$db_name = 'MainsTendues';  // PROBABLY CHANGE
$username = 'etudiant';       // CHANGE
$password = 'Isanum!';           // CHANGE

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Function to simplify the requests
function query($sql, $params = []) {
    global $db;
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}
?>