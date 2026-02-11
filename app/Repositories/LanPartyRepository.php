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

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM lan_parties WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getUpcomingForUser(int $userId): array {
        $sql = "SELECT DISTINCT lp.* FROM lan_parties lp JOIN rentals r ON lp.id = r.lan_party_id
                WHERE r.user_id = :uid AND lp.start_date >= CURDATE() AND lp.status = 'approved' ORDER BY lp.start_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoryForUser(int $userId): array {
        $sql = "SELECT DISTINCT lp.* FROM lan_parties lp JOIN rentals r ON lp.id = r.lan_party_id
                WHERE r.user_id = :uid AND lp.start_date < CURDATE() AND lp.status = 'approved' ORDER BY lp.start_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
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
        // Slug generatie
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        // Uniek maken indien nodig
        if ($this->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $sql = "INSERT INTO lan_parties (name, slug, description, expected_attendees, contact_email, start_date, end_date, status, organizer_id, created_at) 
            VALUES (:name, :slug, :description, :attendees, :email, :start_date, :end_date, 'proposed', :organizer_id, NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
            'attendees'   => $attendees,
            'email'       => $email,
            'start_date'  => $start,
            'end_date'    => $end,
            'organizer_id'=> $organizerId
        ]);
    }

    public function getAllForAdmin(): array {
        $sql = "SELECT lp.*, u.username 
            FROM lan_parties lp 
            LEFT JOIN users u ON lp.organizer_id = u.id 
            ORDER BY lp.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): bool {
        $sql = "UPDATE lan_parties SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    /**
     * Haal alle proposals op (status = 'proposed')
     */
    public function getProposals(): array {
        $sql = "SELECT lp.*, u.username as organizer
                FROM lan_parties lp 
                LEFT JOIN users u ON lp.organizer_id = u.id 
                WHERE lp.status = 'proposed' 
                ORDER BY lp.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApprovedParties(): array {
        $sql = "SELECT lp.*, u.username as organizer
                FROM lan_parties lp 
                LEFT JOIN users u ON lp.organizer_id = u.id 
                WHERE lp.status = 'approved' 
                ORDER BY lp.start_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Stem op een proposal (of schrijf je in)
     */
    public function vote(int $proposalId, int $userId): bool {
        // Check of al gestemd is
        if ($this->hasVoted($proposalId, $userId)) {
            return false;
        }
        $stmt = $this->db->prepare("INSERT INTO proposal_votes (lan_party_id, user_id) VALUES (:pid, :uid)");
        return $stmt->execute(['pid' => $proposalId, 'uid' => $userId]);
    }

    /**
     * Check of user al gestemd heeft
     */
    public function hasVoted(int $proposalId, int $userId): bool {
        $stmt = $this->db->prepare("SELECT id FROM proposal_votes WHERE lan_party_id = :pid AND user_id = :uid");
        $stmt->execute(['pid' => $proposalId, 'uid' => $userId]);
        return (bool)$stmt->fetch();
    }

    /**
     * Haal stemmers op voor een proposal
     */
    public function getVotes(int $proposalId): array {
        $sql = "SELECT u.username, u.id 
                FROM proposal_votes pv 
                JOIN users u ON pv.user_id = u.id 
                WHERE pv.lan_party_id = :pid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pid' => $proposalId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function unvote(int $proposalId, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM proposal_votes WHERE lan_party_id = :pid AND user_id = :uid");
        return $stmt->execute(['pid' => $proposalId, 'uid' => $userId]);
    }

    /**
     * Haal proposals op waar de user aan mee doet
     */
    public function getProposalsForUser(int $userId): array {
        $sql = "SELECT lp.* 
                FROM lan_parties lp 
                JOIN proposal_votes pv ON lp.id = pv.lan_party_id 
                WHERE pv.user_id = :uid AND lp.status = 'proposed' 
                ORDER BY lp.start_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update(int $id, array $data): bool {
        $sql = "UPDATE lan_parties SET 
                name = :name, 
                description = :description, 
                expected_attendees = :attendees, 
                contact_email = :email, 
                start_date = :start_date, 
                end_date = :end_date 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name'        => $data['name'],
            'description' => $data['description'],
            'attendees'   => $data['attendees'],
            'email'       => $data['email'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'id'          => $id
        ]);
    }
    
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM lan_parties WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findForDate(string $date): ?array {
        $stmt = $this->db->prepare("SELECT * FROM lan_parties WHERE status = 'approved' AND :date BETWEEN start_date AND end_date LIMIT 1");
        $stmt->execute(['date' => $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllUpcoming(): array {
        $stmt = $this->db->prepare("SELECT * FROM lan_parties WHERE start_date >= CURDATE() AND status = 'approved' ORDER BY start_date ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNextUpcoming(): ?array {
        $stmt = $this->db->prepare("SELECT * FROM lan_parties WHERE start_date >= CURDATE() AND status = 'approved' ORDER BY start_date ASC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}