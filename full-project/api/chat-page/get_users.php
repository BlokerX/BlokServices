<?php
include '../script_template.php'; // Wczytaj szablon skryptu

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Użytkownik nie jest zalogowany']);
    exit;
}

$current_user_id = $_SESSION['user_id'];

try {
    $conn = connect_db($config);
    
    // Pobierz wszystkich użytkowników z wyjątkiem zalogowanego
    $stmt = $conn->prepare("
        SELECT id, login, name, last_name, avatar, 
            (last_login_date > last_logout_date OR last_logout_date IS NULL) AS is_online
        FROM users 
        WHERE id != ?
        ORDER BY 
            (last_login_date > last_logout_date OR last_logout_date IS NULL) DESC,
            name ASC, last_name ASC
    ");
    $stmt->bind_param('i', $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        // Konwertuj is_online na boolean
        $row['is_online'] = (bool)$row['is_online'];
        $users[] = $row;
    }
    
    echo json_encode([
        'status' => 'success', 
        'users' => $users,
        'current_user_id' => $current_user_id
    ]);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>