<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

try {
    $db = new Database();
    
    // Haal alle resultaten op
    $db->query("SELECT * FROM stemwijzer_results ORDER BY completed_at DESC");
    $results = $db->resultSet() ?? [];
    
    // Haal vragen op voor kolomnamen
    $db->query("SELECT id, title, order_number FROM stemwijzer_questions ORDER BY order_number ASC");
    $questions = $db->resultSet() ?? [];
    
    // Stel CSV headers in
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=stemwijzer-export-' . date('Y-m-d-H-i-s') . '.csv');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Maak output stream
    $output = fopen('php://output', 'w');
    
    // BOM voor UTF-8 ondersteuning in Excel
    fwrite($output, "\xEF\xBB\xBF");
    
    // CSV kolomnamen maken
    $headers = [
        'ID',
        'Session ID', 
        'Voltooid op',
        'IP Adres'
    ];
    
    // Voeg vraag kolommen toe
    foreach ($questions as $question) {
        $headers[] = 'Vraag ' . $question->order_number . ': ' . substr($question->title, 0, 50) . '...';
    }
    
    // Voeg partij score kolommen toe
    $allParties = [];
    foreach ($results as $result) {
        if ($result->results && $result->results != 'null') {
            $resultData = json_decode($result->results, true);
            if (is_array($resultData)) {
                foreach (array_keys($resultData) as $party) {
                    if (!in_array($party, $allParties)) {
                        $allParties[] = $party;
                    }
                }
            }
        }
    }
    
    foreach ($allParties as $party) {
        $headers[] = 'Score: ' . $party;
    }
    
    // Schrijf header rij
    fputcsv($output, $headers, ';');
    
    // Schrijf data rijen
    foreach ($results as $result) {
        $row = [
            $result->id,
            $result->session_id,
            date('d-m-Y H:i:s', strtotime($result->completed_at)),
            $result->ip_address
        ];
        
        // Voeg antwoorden toe
        $answers = [];
        if ($result->answers && $result->answers != 'null') {
            $answerData = json_decode($result->answers, true);
            if (is_array($answerData) || is_object($answerData)) {
                $answers = (array) $answerData;
            }
        }
        
        foreach ($questions as $question) {
            $questionIndex = $question->order_number - 1;
            $answer = isset($answers[$questionIndex]) ? $answers[$questionIndex] : 'Geen antwoord';
            $row[] = $answer;
        }
        
        // Voeg partij scores toe
        $partyScores = [];
        if ($result->results && $result->results != 'null') {
            $resultData = json_decode($result->results, true);
            if (is_array($resultData)) {
                $partyScores = $resultData;
            }
        }
        
        foreach ($allParties as $party) {
            $score = isset($partyScores[$party]['agreement']) ? 
                     round($partyScores[$party]['agreement'], 1) . '%' : 
                     'Geen score';
            $row[] = $score;
        }
        
        fputcsv($output, $row, ';');
    }
    
    fclose($output);
    
} catch (Exception $e) {
    // Bij fout, redirect terug naar statistieken pagina
    redirect('stemwijzer-statistieken.php?error=export_failed');
}
?> 