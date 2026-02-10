<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class LanPartyRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Haalt alle LAN-party's op met hun bijbehorende reservaties
     */
    public function getAllPartiesWithReservations(): array {
        // Expliciete selectie van kolommen voor veiligheid en performance
        $sql = "SELECT 
                    lp.id, 
                    lp.name, 
                    lp.start_date, 
                    i.name AS item_name, 
                    t.quantity 
                FROM lan_parties AS lp
                LEFT JOIN rentals AS t ON lp.id = t.lan_party_id
                LEFT JOIN items AS i ON t.item_id = i.id
                ORDER BY lp.start_date ASC";

        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Herstructureren naar een datum-index voor de kalender
        $parties = [];
        foreach ($results as $row) {
            $date = $row['start_date'];

            if (!isset($parties[$date])) {
                $parties[$date] = [
                    'id'       => $row['id'],
                    'title'    => $row['name'],
                    'items'    => []
                ];
            }

            if ($row['item_name']) {
                $parties[$date]['items'][] = [
                    'name' => $row['item_name'],
                    'qty'  => $row['quantity']
                ];
            }
        }
        return $parties;
    }
}