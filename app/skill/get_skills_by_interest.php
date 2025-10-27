<?php
/**
 * Get Skills by Interest Areas Endpoint
 * 
 * This endpoint filters skills based on the interest areas selected by the user.
 * Used during registration (US4.AC1) to show relevant skills after user selects
 * interest areas (Design, Development, Data Intelligence, Business Management).
 * 
 * Example: If user selects "Design" and "Development", this returns all skills
 * that belong to those interest areas.
 */

session_start();
include_once '../connection.php';

// Set response header for JSON content
header("Content-Type: application/json; charset=utf-8");

$resposta = [];

// Get interest areas from URL parameter (e.g., ?areas=design,development,data)
// This can be a comma-separated string like "design,development" or an array
$interest_areas = isset($_GET['areas']) ? $_GET['areas'] : null;

// Validate that interest areas were provided
if (!$interest_areas) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Interest areas not provided.';
    echo json_encode($resposta);
    exit;
}

// Convert comma-separated string to array (e.g., "design,development" -> ["design", "development"])
if (is_string($interest_areas)) {
    $interest_areas = explode(',', $interest_areas);
}

/**
 * Map interest area names to their database IDs
 * This is needed because the database stores IDs (1, 2, 3, 4) but 
 * the frontend sends names (design, development, data, business)
 * 
 * - design -> 1
 * - development -> 2
 * - data -> 3
 * - business -> 4
 */
$area_map = [
    'design' => 1,
    'development' => 2,
    'data' => 3,
    'business' => 4
];

// Convert area names to database IDs
$area_ids = [];
foreach ($interest_areas as $area) {
    // Check if the area name exists in our mapping
    if (isset($area_map[$area])) {
        // Add the corresponding ID to our array
        $area_ids[] = $area_map[$area];
    }
}

// Validate that we have valid IDs
if (empty($area_ids)) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Invalid interest areas.';
    echo json_encode($resposta);
    exit;
}

// Create SQL placeholders for prepared statement (e.g., "?,?,?" for 3 IDs)
// This prevents SQL injection by using parameterized queries
$placeholders = implode(',', array_fill(0, count($area_ids), '?'));

/**
 * Query Explanation:
 * 
 * SELECT DISTINCT - Get unique skills (a skill might be in multiple areas)
 * s.id_skill, s.name_skill, s.skill_type - Skill details
 * 
 * FROM skill s - Main skills table
 * INNER JOIN interest_area_skill ias - Join with mapping table that links areas to skills
 *   ON s.id_skill = ias.id_skill - Match skills
 * 
 * WHERE ias.id_interest_area IN ($placeholders) - Filter by selected interest areas
 * 
 * ORDER BY s.skill_type DESC, s.name_skill ASC - Sort by type (hard first), then alphabetically
 * 
 * Example result: If user selects "design" (ID: 1), returns skills like:
 * - UI/UX Design, Figma, Photoshop, Adobe Illustrator, etc.
 */
$sql = "SELECT DISTINCT s.id_skill, s.name_skill, s.skill_type
        FROM skill s
        INNER JOIN interest_area_skill ias ON s.id_skill = ias.id_skill
        WHERE ias.id_interest_area IN ($placeholders)
        ORDER BY s.skill_type DESC, s.name_skill ASC";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically (e.g., str_repeat('i', 2) = 'ii' for 2 integer parameters)
$stmt->bind_param(str_repeat('i', count($area_ids)), ...$area_ids);

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
