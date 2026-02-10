<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\LanPartyRepository;

class ProposeController
{
    private LanPartyRepository $lanRepo;

    public function __construct()
    {
        $this->lanRepo = new LanPartyRepository();
    }

    // Toon de lijst met open proposals
    public function list(): void
    {
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }

        $proposals = $this->lanRepo->getProposals();
        
        foreach ($proposals as &$prop) {
            $prop['votes'] = $this->lanRepo->getVotes($prop['id']);
            $prop['has_voted'] = $this->lanRepo->hasVoted($prop['id'], $_SESSION['user']['id']);
        }
        unset($prop);

        view('user/proposals/index', ['proposals' => $proposals]);
    }

    // Toon het formulier ("Propose LAN")
    public function create(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        view('user/propose');
    }

    public function join(): void {
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }
        if (!csrf_verify()) die('Invalid CSRF');

        $proposalId = (int)$_POST['proposal_id'];
        $this->lanRepo->vote($proposalId, $_SESSION['user']['id']);

        header('Location: /proposals?status=joined');
        exit;
    }

    public function unjoin(): void {
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }
        if (!csrf_verify()) die('Invalid CSRF');

        $proposalId = (int)$_POST['proposal_id'];
        $this->lanRepo->unvote($proposalId, $_SESSION['user']['id']);

        header('Location: /proposals?status=unjoined');
        exit;
    }

    // Verwerk het formulier (POST)
    // Verwerk het formulier (POST)
    public function store(): void
    {
        // 1. Veiligheidschecks
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if (!csrf_verify()) {
            die('Beveiligingsfout: Ongeldig CSRF-token.');
        }

        // 2. Data ophalen uit het formulier
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? ''); // Het nieuwe tekstveld
        $attendees   = (int)($_POST['attendees'] ?? 0);     // Verwachte mensen
        $contactEmail= trim($_POST['proposed_email'] ?? ''); // Handmatig ingevulde email
        $date        = $_POST['date'] ?? '';
        $startTime   = $_POST['start_time'] ?? '';
        $endTime     = $_POST['end_time'] ?? '';

        // 3. Datum & Tijd logica: Samenvoegen tot SQL DATETIME
        $startDateTime = $date . ' ' . $startTime . ':00';

        // Controle of het event na middernacht eindigt (bijv. van 22:00 tot 03:00)
        $endDate = $date;
        if (strtotime($endTime) < strtotime($startTime)) {
            $endDate = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        $endDateTime = $endDate . ' ' . $endTime . ':00';

        // 4. Opslaan via de Repository
        // We gebruiken de organizer_id uit de sessie van de ingelogde gebruiker
        $organizerId = $_SESSION['user']['id'];

        $success = $this->lanRepo->create(
            $name,
            $description,
            $attendees,
            $contactEmail,
            $startDateTime,
            $endDateTime,
            $organizerId
        );

        // 5. Afronding
        if ($success) {
            // Stuur terug naar dashboard met een succes-status in de URL
            header('Location: /dashboard?status=proposal_sent');
            exit;
        } else {
            // Als er iets misgaat in de database
            die("Systeemfout: De aanvraag kon niet worden opgeslagen in de database.");
        }
    }
}