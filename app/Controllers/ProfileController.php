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

    public function index(): void {
        $userId = (int)$_SESSION['user']['id'];

        view('user/profile', [
            'user' => $this->userRepo->findById($userId),
            'friends' => $this->userRepo->getFriends($userId),
            'pendingRequests' => $this->userRepo->getPendingRequests($userId), // NIEUW
            'history' => $this->lanRepo->getHistoryForUser($userId),
            'upcoming' => $this->lanRepo->getUpcomingForUser($userId),
            'isOwnProfile' => true
        ]);
    }

    public function search(): void {
        $query = $_GET['q'] ?? '';
        $results = !empty($query) ? $this->userRepo->searchUsers($query, (int)$_SESSION['user']['id']) : [];
        view('user/search', ['results' => $results, 'query' => $query]);
    }

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

        // 3. Validatie
        if (!$user) { 
            header('Location: /dashboard'); 
            exit; 
        }

        // Zorg dat user altijd een array is voor consistentie
        $user = (array)$user;
        $targetId = (int)$user['id'];
        $myId = (int)$_SESSION['user']['id'];

        if ($targetId === $myId) { header('Location: /profile'); exit; }

        $status = $this->userRepo->getFriendshipStatus($myId, $targetId);
        view('user/profile', [
            'user' => (array)$user, // Zeker weten dat het een array is voor de view
            'friendshipStatus' => $status,
            'isOwnProfile' => false,
            'friends' => $this->userRepo->getFriends($targetId),
            'history' => ($status === 'accepted') ? $this->lanRepo->getHistoryForUser($targetId) : [],
            'upcoming' => ($status === 'accepted') ? $this->lanRepo->getUpcomingForUser($targetId) : []
        ]);
    }

    public function addFriend(): void {
        if (!csrf_verify()) die('Invalid CSRF');
        $friendId = (int)($_POST['friend_id'] ?? 0);
        if ($friendId > 0) { $this->userRepo->sendFriendRequest((int)$_SESSION['user']['id'], $friendId); }
        header('Location: /user/profile?id=' . $friendId);
        exit;
    }

    public function acceptFriend(): void {
        if (!csrf_verify()) die('CSRF Token Mismatch');

        $requestId = (int)($_POST['request_id'] ?? 0);
        if ($requestId > 0) {
            $this->userRepo->acceptFriendRequest($requestId, (int)$_SESSION['user']['id']);
        }

        header('Location: /profile');
        exit;
    }
}