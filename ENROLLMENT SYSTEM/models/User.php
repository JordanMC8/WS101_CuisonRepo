<?php
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get user by ID
    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Get user by username
    public function getUserByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    // Get all users
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY role, last_name, first_name");
        return $stmt->fetchAll();
    }

    // Create a new user
    public function createUser($username, $password, $email, $firstName, $lastName, $role)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $email, $firstName, $lastName, $role]);
    }

    // Update user profile picture
    public function updateProfilePicture($userId, $profilePicturePath)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        return $stmt->execute([$profilePicturePath, $userId]);
    }

    // Update user signature
    public function updateSignature($userId, $signaturePath)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET signature = ? WHERE id = ?");
        return $stmt->execute([$signaturePath, $userId]);
    }

    // Update user information
    public function updateUser($userId, $email, $firstName, $lastName)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ? WHERE id = ?");
        return $stmt->execute([$email, $firstName, $lastName, $userId]);
    }

    // Delete user
    public function deleteUser($userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    // Get users by role
    public function getUsersByRole($role)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = ? ORDER BY last_name, first_name");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }
}
