<?php
class Controller {
    public function __construct() {
        // Basis constructor
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 