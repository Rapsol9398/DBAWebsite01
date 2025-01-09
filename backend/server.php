<?php
// Verbindung zur Datenbank herstellen
$conn = new mysqli("localhost", "root", "password", "ProgrammingLanguagesApp");

// Prüfen, ob die Verbindung erfolgreich ist
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Daten vom Frontend empfangen
$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'];
$language = $data['language'];
$userId = $data['userId'];

// Die ID der Programmiersprache abrufen
$sql = "SELECT id FROM programming_languages WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $language);
$stmt->execute();
$result = $stmt->get_result();
$languageId = $result->fetch_assoc()['id'];

// Nachricht in die Datenbank einfügen
$sql = "INSERT INTO messages (user_id, language_id, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $userId, $languageId, $message);
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$conn->close();
?>
