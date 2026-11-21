<?php
require_once __DIR__ . '/../config/db.php';

// Consultas da tabela interesse: página do cliente e tela da equipe
class Interesse
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Carros em que o cliente marcou interesse (os já vendidos continuam na lista, marcados como vendidos)
    public function doCliente(int $idCliente): array
    {
        $st = $this->conn->prepare("
            SELECT c.*,
                   EXISTS (SELECT 1 FROM vendas v WHERE v.id_carro = c.id_carro) AS vendido
            FROM interesse i
            JOIN carros c ON c.id_carro = i.id_carro
            WHERE i.id_cliente = :c
            ORDER BY i.data_registro DESC
        ");
        $st->execute([':c' => $idCliente]);
        return $st->fetchAll();
    }

    public function remover(int $idCliente, int $idCarro): void
    {
        $st = $this->conn->prepare("DELETE FROM interesse WHERE id_cliente = :c AND id_carro = :v");
        $st->execute([':c' => $idCliente, ':v' => $idCarro]);
    }

    // Para a equipe: uma linha por cliente interessado, do carro mais novo para o mais antigo
    public function porCarro(): array
    {
        return $this->conn->query("
            SELECT c.id_carro, c.marca, c.modelo, c.imagem,
                   EXISTS (SELECT 1 FROM vendas v WHERE v.id_carro = c.id_carro) AS vendido,
                   cl.nome, cl.email, cl.celular, i.data_registro
            FROM interesse i
            JOIN carros c ON c.id_carro = i.id_carro
            JOIN clientes cl ON cl.id_cliente = i.id_cliente
            ORDER BY c.id_carro DESC, i.data_registro DESC
        ")->fetchAll();
    }
}
