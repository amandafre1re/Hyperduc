<?php
/**
 * Add User Skills Endpoint
 * 
 * This endpoint assigns skills to the logged-in user during registration.
 * Used in US1.AC1 (registration) to save the user's selected skills.
 * 
 * The process uses a transaction to ensure data integrity:
 * 1. Delete existing user skills (if any)
 * 2. Insert all new selected skills
 * If any step fails, everything is rolled back.
 */

session_start();
include_once '../connection.php';

// Set response header for JSON content
header("Content-Type: application/json; charset=utf-8");

$resposta = [];

// Check if user is logged in (required to assign skills to a specific user)
if (!isset($_SESSION['user_id'])) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Usuário não logado.'; // User-facing message in Portuguese
    echo json_encode($resposta);
    exit;
}

// Get the logged-in user's ID from session
$user_id = $_SESSION['user_id'];

// Get skills array from POST data (array of skill IDs like [1, 2, 3, 5])
$skills = json_decode($_POST['skills']); // Array of skill IDs

// Validate that at least one skill was selected
if (empty($skills)) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Nenhuma habilidade selecionada.'; // User-facing message in Portuguese
    echo json_encode($resposta);
    exit;
}

/**
 * Use database transaction to ensure atomicity (all-or-nothing operation)
 * This prevents partial updates if something goes wrong
 */
$conn->begin_transaction();

try {
    /**
     * Step 1: Delete all existing skills for this user first
     * This allows the user to update their skills by replacing the old set with new ones
     * Otherwise, skills would just keep accumulating
     */
    $stmt_delete = $conn->prepare("DELETE FROM user_skill WHERE id_user = ?");
    $stmt_delete->bind_param("i", $user_id);
    $stmt_delete->execute();
    $stmt_delete->close();

    /**
     * Step 2: Insert all new selected skills
     * Loop through the array of skill IDs and insert each one
     */
    $stmt_insert = $conn->prepare("INSERT INTO user_skill (id_user, id_skill) VALUES (?, ?)");
    
    foreach ($skills as $skill_id) {
        // Bind parameters: i = integer, ii = two integers (user_id, skill_id)
        $stmt_insert->bind_param("ii", $user_id, $skill_id);
        
        // If insertion fails, throw exception to trigger rollback
        if (!$stmt_insert->execute()) {
            throw new Exception("Erro ao adicionar habilidade: " . $stmt_insert->error);
        }
    }
    $stmt_insert->close();

    /**
     * Step 3: If we got here without exceptions, commit the transaction
     * This makes all changes permanent in the database
     */
    $conn->commit();

    // Prepare success response
    $resposta['codigo'] = true;
    $resposta['msg'] = 'Habilidades adicionadas com sucesso!'; // User-facing message in Portuguese
    
} catch (Exception $e) {
    /**
     * If any error occurred (validation failed, database error, etc.),
     * rollback the transaction to undo all changes made in this operation
     * This keeps the database in a consistent state
     */
    $conn->rollback();
    
    // Return error response
    $resposta['codigo'] = false;
    $resposta['msg'] = $e->getMessage(); // Error message (may contain technical details)
}

// Send JSON response
echo json_encode($resposta);

// Close database connection
$conn->close();
?>
