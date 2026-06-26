<?php
require_once __DIR__ . '/../config/db.php';

class Produto
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // 🔹 Listar todos os carros
    public function listarTodos(): array
    {
        $sql = "SELECT * FROM carros ORDER BY id_carro DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Buscar carro por ID
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM carros
            WHERE id_carro = :id
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        $carro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $carro ?: null;
    }

    // 🔹 Inserir novo carro
    public function inserir(
        string $marca,
        string $modelo,
        int $ano,
        ?string $cor,
        ?string $combustivel,
        ?int $km,
        string $chassi,
        float $preco
    ): int {

        $stmt = $this->conn->prepare("
            INSERT INTO carros
            (
                marca,
                modelo,
                ano,
                cor,
                combustivel,
                km,
                n_chassi,
                preco
            )
            VALUES
            (
                :marca,
                :modelo,
                :ano,
                :cor,
                :combustivel,
                :km,
                :chassi,
                :preco
            )
        ");

        $stmt->execute([
            ':marca'        => $marca,
            ':modelo'       => $modelo,
            ':ano'          => $ano,
            ':cor'          => $cor,
            ':combustivel'  => $combustivel,
            ':km'           => $km,
            ':chassi'       => $chassi,
            ':preco'        => $preco
        ]);

        return (int)$this->conn->lastInsertId();
    }

    // 🔹 Atualizar carro
    public function atualizar(
        int $id,
        string $marca,
        string $modelo,
        int $ano,
        ?string $cor,
        ?string $combustivel,
        ?int $km,
        string $chassi,
        float $preco
    ): void {

        $stmt = $this->conn->prepare("
            UPDATE carros SET
                marca = :marca,
                modelo = :modelo,
                ano = :ano,
                cor = :cor,
                combustivel = :combustivel,
                km = :km,
                n_chassi = :chassi,
                preco = :preco
            WHERE id_carro = :id
        ");

        $stmt->execute([
            ':id'           => $id,
            ':marca'        => $marca,
            ':modelo'       => $modelo,
            ':ano'          => $ano,
            ':cor'          => $cor,
            ':combustivel'  => $combustivel,
            ':km'           => $km,
            ':chassi'       => $chassi,
            ':preco'        => $preco
        ]);
    }

    // 🔹 Deletar carro
    public function deletar(int $id): bool
    {
        $stmt = $this->conn->prepare("
            DELETE FROM carros
            WHERE id_carro = :id
        ");

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}