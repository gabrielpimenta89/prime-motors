<?php
class Venda
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function registrar(int $id_cliente, int $id_produto, float $valor, string $data)
    {
        $sql = "INSERT INTO vendas (id_cliente, id_produto, valor_venda, data_venda) 
                VALUES (:cliente, :produto, :valor, :data)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'cliente' => $id_cliente,
            'produto' => $id_produto,
            'valor'   => $valor,
            'data'    => $data
        ]);
    }

    public function listarTodas()
    {
        $sql = "SELECT v.*, c.nome as nome_cliente, p.nome as nome_produto 
                FROM vendas v 
                JOIN clientes c ON v.id_cliente = c.id_cliente 
                JOIN produtos p ON v.id_produto = p.id_produto";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarVendasPorPeriodo(string $inicio, string $fim)
    {
        $sql = "SELECT * FROM vendas WHERE data_venda BETWEEN :inicio AND :fim";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fim' => $fim]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularTotalMensal(int $mes, int $ano)
    {
        $sql = "SELECT SUM(valor_venda) as total FROM vendas 
                WHERE MONTH(data_venda) = :mes AND YEAR(data_venda) = :ano";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'] ?? 0;
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM vendas WHERE id_venda = :id");
        return $stmt->execute([':id' => $id]);
    }
}
