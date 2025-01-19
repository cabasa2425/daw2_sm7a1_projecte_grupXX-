<?php

class FileHandler {
    protected function readLines($filename) {
        if (file_exists($filename)) {
            $size = filesize($filename);

            if ($size > 0) {
                $file = fopen($filename, 'r');
                $lines = explode(PHP_EOL, fread($file, $size));
                fclose($file);
                return $lines;
            } else {
                return [];
            }
        } else {
            echo "File not found: $filename";
            die();
        }
    }

    protected function writeLines($filename, $lines) {
        $file = fopen($filename, 'w');
        fwrite($file, implode(PHP_EOL, $lines));
        fclose($file);
    }
}

class Admin extends FileHandler {
    public function deleteClient($clientId) {
        // Implementation here
    }

    public function modifyManager($managerId, $data) {
        // Implementation here
    }

    public function deleteManager($managerId) {
        // Implementation here
    }

    public function registerManager($name, $password, $type) {
        // Implementation here
    }

    public function registerClient($name, $password) {
        // Implementation here
    }

    public function clientToPdf($clientId) {
        // Implementation here
    }

    public function managerToPdf($managerId) {
        // Implementation here
    }

    public function showManagers() {
        // Implementation here
    }

    public function showClients() {
        // Implementation here
    }

    public function modifyAdminInformation($adminId, $data) {
        // Implementation here
    }
}

class Manager extends FileHandler {
    public function sendEmail($to, $subject, $body) {
        // Implementation here
    }

    public function registerProduct($name, $price, $iva, $available) {
        // Implementation here
    }

    public function modifyProduct($productId, $data) {
        // Implementation here
    }

    public function productToPdf($productId) {
        // Implementation here
    }

    public function eraseCommand($commandId) {
        // Implementation here
    }

    public function showClientsToManager() {
        // Implementation here
    }

    public function showProductToManager() {
        // Implementation here
    }

    public function deleteProduct($productId) {
        // Implementation here
    }

    public function showCommand($commandId) {
        // Implementation here
    }
}

class Client extends FileHandler {
    public function addToCart($productId, $quantity) {
        // Implementation here
    }

    public function createCommand($cartItems) {
        // Implementation here
    }

    public function eraseCommand($commandId) {
        // Implementation here
    }

    public function modifyCommand($commandId, $data) {
        // Implementation here
    }

    public function deleteProductFromCart($productId) {
        // Implementation here
    }

    public function changeQuantity($productId, $quantity) {
        // Implementation here
    }

    public function commandToPdf($commandId) {
        // Implementation here
    }

    public function showProductsToClient() {
        // Implementation here
    }

    public function viewCart() {
        // Implementation here
    }
}

?>
