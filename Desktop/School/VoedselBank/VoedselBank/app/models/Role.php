<?php
/**
 * Role model – rollen ophalen
 */
class Role extends BaseModel
{
    public function getAll(): array
    {
        return $this->db->query('SELECT * FROM rollen ORDER BY rol_id')->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM rollen WHERE rol_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
