<?php
/**
 * User model – gebruikersbeheer
 */
class User extends BaseModel
{
    public function findByUsername(string $username): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT g.*, r.rolnaam FROM gebruikers g
             JOIN rollen r ON g.rol_id = r.rol_id
             WHERE g.gebruikersnaam = ? AND g.actief = 1'
        );
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT g.*, r.rolnaam FROM gebruikers g
             JOIN rollen r ON g.rol_id = r.rol_id
             WHERE g.gebruiker_id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT g.*, r.rolnaam FROM gebruikers g
             JOIN rollen r ON g.rol_id = r.rol_id
             ORDER BY g.gebruiker_id'
        )->fetchAll();
    }

    public function create(string $username, string $password, int $roleId): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO gebruikers (gebruikersnaam, wachtwoord_hash, rol_id, actief)
             VALUES (?, ?, ?, 1)'
        );
        return $stmt->execute([$username, $hash, $roleId]);
    }

    public function update(int $id, string $username, int $roleId, bool $actief): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE gebruikers SET gebruikersnaam = ?, rol_id = ?, actief = ?
             WHERE gebruiker_id = ?'
        );
        return $stmt->execute([$username, $roleId, (int)$actief, $id]);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'UPDATE gebruikers SET wachtwoord_hash = ? WHERE gebruiker_id = ?'
        );
        return $stmt->execute([$hash, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM gebruikers WHERE gebruiker_id = ?');
        return $stmt->execute([$id]);
    }

    public function usernameExists(string $username, int $excludeId = 0): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM gebruikers WHERE gebruikersnaam = ? AND gebruiker_id != ?'
        );
        $stmt->execute([$username, $excludeId]);
        return (bool)$stmt->fetchColumn();
    }
}
