<?php
require_once __DIR__ . '/../config/db.php';

// Cliente do site público: login, cadastro e interesse em carros
class ClienteSite
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $st = $this->conn->prepare("SELECT * FROM clientes WHERE email = :e");
        $st->execute([':e' => $email]);
        return $st->fetch() ?: null;
    }

    public function buscarPorCpf(string $cpf): ?array
    {
        $st = $this->conn->prepare("SELECT * FROM clientes WHERE cpf = :c");
        $st->execute([':c' => $cpf]);
        return $st->fetch() ?: null;
    }

    // Cria o cliente ou, se o CPF já existe (cliente que comprou na loja), ativa o acesso dele ao site
    public function cadastrar(string $nome, string $cpf, string $email, string $senhaHash): int
    {
        $existente = $this->buscarPorCpf($cpf);
        if ($existente) {
            $st = $this->conn->prepare("UPDATE clientes SET email = :e, senha = :s WHERE id_cliente = :id");
            $st->execute([':e' => $email, ':s' => $senhaHash, ':id' => $existente['id_cliente']]);
            return (int)$existente['id_cliente'];
        }
        $st = $this->conn->prepare("INSERT INTO clientes (nome, cpf, email, senha) VALUES (:n, :c, :e, :s)");
        $st->execute([':n' => $nome, ':c' => $cpf, ':e' => $email, ':s' => $senhaHash]);
        return (int)$this->conn->lastInsertId();
    }

    // INSERT IGNORE: a chave única (cliente, carro) impede interesse repetido
    public function registrarInteresse(int $idCliente, int $idCarro): void
    {
        $st = $this->conn->prepare("INSERT IGNORE INTO interesse (id_cliente, id_carro) VALUES (:c, :v)");
        $st->execute([':c' => $idCliente, ':v' => $idCarro]);
    }

    public function temInteresse(int $idCliente, int $idCarro): bool
    {
        $st = $this->conn->prepare("SELECT 1 FROM interesse WHERE id_cliente = :c AND id_carro = :v");
        $st->execute([':c' => $idCliente, ':v' => $idCarro]);
        return (bool)$st->fetchColumn();
    }
}
