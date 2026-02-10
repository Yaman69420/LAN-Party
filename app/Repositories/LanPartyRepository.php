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
        // We voegen 'WHERE lp.status = 'approved'' toe
        $sql = "SELECT 
                lp.id, 
                lp.name, 
                lp.start_date, 
                lp.description,
                lp.status,
                i.name AS item_name, 
                r.quantity 
            FROM lan_parties AS lp
            LEFT JOIN rentals AS r ON lp.id = r.lan_party_id
            LEFT JOIN items AS i ON r.item_id = i.id
            WHERE lp.status = 'approved'
            ORDER BY lp.start_date ASC";

        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $parties = [];
        foreach ($results as $row) {
            $date = date('Y-m-d', strtotime($row['start_date']));

            if (!isset($parties[$date])) {
                $parties[$date] = [
                    'id'       => $row['id'],
                    'title'    => $row['name'],
                    'location' => $row['description'],
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

    /**
     * Slaat een nieuwe LAN-party aanvraag op in de database
     */
    public function create(string $name, string $description, int $attendees, string $email, string $start, string $end, int $organizerId): bool
    {
        $sql = "INSERT INTO lan_parties (name, description, expected_attendees, contact_email, start_date, end_date, status, organizer_id, created_at) 
            VALUES (:name, :description, :attendees, :email, :start_date, :end_date, 'proposed', :organizer_id, NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'        => $name,
            'description' => $description,
            'attendees'   => $attendees,
            'email'       => $email,
            'start_date'  => $start,
            'end_date'    => $end,
            'organizer_id'=> $organizerId
        ]);
    }
}