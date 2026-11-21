<?php
require_once __DIR__ . '/../models/interesse.php';

class InteresseController
{
    // Tela da equipe: quem se interessou por cada carro (admin e vendedor)
    public function index(): void
    {
        $this->check();

        $grupos = [];
        foreach ((new Interesse())->porCarro() as $linha) {
            $grupos[$linha['id_carro']][] = $linha;
        }

        require __DIR__ . '/../views/interesses.php';
    }

    private function check(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=form");
            exit;
        }
    }
}
