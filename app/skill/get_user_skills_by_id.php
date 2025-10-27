<?php
/**
 * Get User Skills by ID Endpoint
 * 
 * This endpoint retrieves skills for a specific user by their user_id.
 * Used in US4.AC2 (task assignment) when a manager needs to see what skills
 * a selected user has, so they can filter and assign relevant hard skills to tasks.
 * 
 * Unlike get_user_skills.php which gets skills for the logged-in user,
 * this endpoint allows getting skills for ANY user (needed when selecting
 * team members for task assignment).
 */

session_start();
include_once '../connection.php';

// Set response header for JSON content
header("Content-Type: application/json; charset=utf-8");

$resposta = [];

// Check if user is logged in (authentication required)
if (!isset($_SESSION['user_id'])) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Usuário não logado.'; // User-facing message in Portuguese
    echo json_encode($resposta);
    exit;
}

// Get target user ID from URL parameter (e.g., ?user_id=5)
$target_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

// Validate that user_id was provided
if (!$target_user_id) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'User ID not provided.';
    echo json_encode($resposta);
    exit;
}

/**
 * Query Explanation:
 * 
 * SELECT s.id_skill, s.name_skill, s.skill_type - Get skill details
 * 
 * FROM skill s - Main skills table
 * INNER JOIN user_skill us - Join with the user_skill mapping table
 *   ON s.id_skill = us.id_skill - Match skills
 * 
 * WHERE us.id_user = ? - Filter by the specified user ID
 * 
 * ORDER BY s.skill_type DESC, s.name_skill ASC - Sort by type (hard skills first),
 * then alphabetically by name
 * 
 * This returns all skills that the specified user has, grouped by type.
 */
$sql = "SELECT s.id_skill, s.name_skill, s.skill_type 
        FROM skill s 
        INNER JOIN user_skill us ON s.id_skill = us.id_skill 
        WHERE us.id_user = ?
        ORDER BY s.skill_type DESC, s.name_skill ASC";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind parameter (i = integer for user_id)
$stmt->bind_param("i", $target_user_id);

// Execute the query
$stmt->execute();

// Get the result set
$resultado = $stmt->get_result();

// Loop through results and build the skills array
$skills = [];
while ($row = $resultado->fetch_assoc()) {
    $skills[] = $row;
}

// Prepare success response with skills
$resposta['codigo'] = true;
$resposta['skills'] = $skills;

// Send JSON response
echo json_encode($resposta);

// Close statement and connection
$stmt->close();
$conn->close();
?>
