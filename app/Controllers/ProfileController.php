<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\LanPartyRepository;

class ProfileController
{
    private UserRepository $userRepo;
    private LanPartyRepository $lanRepo;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        $this->userRepo = new UserRepository();
        $this->lanRepo = new LanPartyRepository();
    }

    /**
     * Toont het eigen profiel van de ingelogde gebruiker
     */
    public function index(): void {
        $userId = (int)$_SESSION['user']['id'];

        view('user/profile', [
            'user' => $this->userRepo->findById($userId),
            'friends' => $this->userRepo->getFriends($userId),
            'pendingRequests' => $this->userRepo->getPendingRequests($userId),
            'history' => $this->lanRepo->getHistoryForUser($userId),
            'upcoming' => $this->lanRepo->getUpcomingForUser($userId),
            'isOwnProfile' => true
        ]);
    }

    /**
     * Zoeken naar andere operatives
     */
    public function search(): void {
        $query = $_GET['q'] ?? '';
        $results = !empty($query) ? $this->userRepo->searchUsers($query, (int)$_SESSION['user']['id']) : [];
        view('user/search', ['results' => $results, 'query' => $query]);
    }

    /**
     * Het profiel van iemand anders bekijken
     * Opgelost: Gebruikt nu de logica van de 'dev' branch (slugs + id support)
     */
    public function viewUser(?string $slug = null): void {
        $targetId = 0;
        $user = null;

        // 1. Zoek op basis van slug (nieuwe manier)
        if ($slug) {
            $user = $this->userRepo->findBySlug($slug);
        } 
        // 2. Fallback: Zoek op basis van ID (oude manier, via $_GET)
        elseif (isset($_GET['id'])) {
            $user = $this->userRepo->findById((int)$_GET['id']);
        }

        // 3. Validatie: Bestaat de user niet? Terug naar dashboard.
        if (!$user) { 
            header('Location: /dashboard'); 
            exit; 
        }

        // Zorg dat user altijd een array is voor consistentie
        $user = (array)$user;
        $targetId = (int)$user['id'];
        $myId = (int)$_SESSION['user']['id'];

        // Als je naar jezelf kijkt, stuur door naar je eigen profiel
        if ($targetId === $myId) {
            header('Location: /profile');
            exit;
        }

        $status = $this->userRepo->getFriendshipStatus($myId, $targetId);

        view('user/profile', [
            'user' => $user,
            'friendshipStatus' => $status,
            'isOwnProfile' => false,
            'friends' => $this->userRepo->getFriends($targetId),
            // Alleen missies tonen als ze vrienden zijn (privacy check)
            'history' => ($status === 'accepted') ? $this->lanRepo->getHistoryForUser($targetId) : [],
            'upcoming' => ($status === 'accepted') ? $this->lanRepo->getUpcomingForUser($targetId) : []
        ]);
    }

    /**
     * Vriendschapsverzoek versturen
     */
    public function addFriend(): void {
        if (!csrf_verify()) die('Invalid CSRF');
        $friendId = (int)($_POST['friend_id'] ?? 0);
        if ($friendId > 0) {
            $this->userRepo->sendFriendRequest((int)$_SESSION['user']['id'], $friendId);
        }
        header('Location: /user/profile?id=' . $friendId);
        exit;
    }

    /**
     * Vriendschapsverzoek accepteren
     */
    public function acceptFriend(): void {
        if (!csrf_verify()) die('CSRF Token Mismatch');

        $requestId = (int)($_POST['request_id'] ?? 0);
        if ($requestId > 0) {
            $this->userRepo->acceptFriendRequest($requestId, (int)$_SESSION['user']['id']);
        }

        header('Location: /profile');
        exit;
    }

    /**
     * Edit formulier tonen
     */
    public function edit(): void {
        $user = $this->userRepo->findById((int)$_SESSION['user']['id']);
        view('user/edit', ['user' => $user]);
    }

    /**
     * Gegevens en foto updaten
     */
    public function update(): void {
        if (!csrf_verify()) die('CSRF Mismatch');

        $userId = (int)$_SESSION['user']['id'];
        $user = $this->userRepo->findById($userId);

        $publicPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/avatars/';
        $imagePath = $user['profile_image'] ?? null;

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0777, true);
            }

            // Veiligheidscheck: spaties naar underscores
            $safeFileName = str_replace(' ', '_', $_FILES['avatar']['name']);
            $filename = time() . '_' . $safeFileName;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $publicPath . $filename)) {
                // Oude foto opruimen
                if ($imagePath && file_exists($publicPath . $imagePath)) {
                    unlink($publicPath . $imagePath);
                }
                $imagePath = $filename;
            }
        }

        $formData = [
            'first_name'    => $_POST['first_name'] ?? '',
            'last_name'     => $_POST['last_name'] ?? '',
            'email'         => $_POST['email'] ?? '',
            'profile_image' => $imagePath
        ];

        if ($this->userRepo->updateProfile($userId, $formData)) {
            // Update sessie data
            $_SESSION['user']['first_name'] = $formData['first_name'];
            $_SESSION['user']['last_name'] = $formData['last_name'];
            $_SESSION['user']['email'] = $formData['email'];

            $_SESSION['user']['profile_image'] = $imagePath;
        }

        header('Location: /profile');
        exit;
    }
}