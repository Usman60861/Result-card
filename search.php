<?php
include 'db.php';

$search = "";
$results = [];
$suggestions = [];
$search_term = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = trim($_POST['search']);
    $search_term = $search;

    if(!$conn) die("Database connection failed");

    // Get suggestions for dropdown
    $stmt_suggest = $conn->prepare("SELECT DISTINCT student_name FROM students_results 
                                  WHERE student_name LIKE CONCAT('%', ?, '%') 
                                  LIMIT 5");
    $stmt_suggest->bind_param("s", $search);
    $stmt_suggest->execute();
    $suggest_result = $stmt_suggest->get_result();
    
    while ($row = $suggest_result->fetch_assoc()) {
        $suggestions[] = $row['student_name'];
    }
    $stmt_suggest->close();

    // Main search query
    $stmt = $conn->prepare("SELECT * FROM students_results 
                          WHERE student_name LIKE CONCAT('%', ?, '%')
                          OR roll_number LIKE CONCAT('%', ?, '%')
                          ORDER BY student_name ASC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $row['highlighted_name'] = preg_replace(
            "/(".$search_term.")/i", 
            "<mark>$1</mark>", 
            $row['student_name']
        );
        $results[] = $row;
    }
    $stmt->close();
}
?>
