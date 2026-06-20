<?php
require "config.php";
require "Dados.php";
require "Info.php";

// Verifica se $pdo está definido
if (!isset($pdo)) {
    die("Erro: conexão com o banco de dados não estabelecida.");
}

$dados = new Info($pdo);
$resultados = $dados->buscar();

# Criando um array associativo

$dadosAgrupados = [];

foreach ($resultados as $dado) {

    $chave = $dado->getDataScan();
    if (!isset($dadosAgrupados[$chave])) {
        $dadosAgrupados[$chave] = [];
    }

    #Colocando dados por grupo

    $dadosAgrupados[$chave][] = [
        'ip' => $dado->getIp(),
        'name' => $dado->getName(),
        'port' => $dado->getPort(),
        'protocol' => $dado->getProtocol(),
        'service' => $dado->getProduct(),
        'os' => $dado->getOsType(),
        'status' => $dado->getState()
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Web Dashboard</title>
    <link rel="stylesheet" href="../src/style.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md bg-primary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">
                    <img src="../img/logo-home-horizontal.svg" alt="IMPA" width="105" height="38">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="history.php">Histórico</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search" method="post" action="results.php">
                        <input class="form-control me-2" type="search" name="search_ip" placeholder="Digite aqui" aria-label="Filtrar">
                        <button class="btn btn-success" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <!-- Titulo -->
        <h5 class="titulo">IWD- Interface Web Dashboard</h5>

        <?php foreach ($dadosAgrupados as $chave => $grupo): ?>

            <h5>Data do scan: <?php echo htmlspecialchars($chave); ?></h5>

            <table class="table apresentacao">
                <thead>
                    <tr>
                        <th scope="col">IP</th>
                        <th scope="col">Name</th>
                        <th scope="col">Port</th>
                        <th scope="col">Protocol</th>
                        <th scope="col">Service</th>
                        <th scope="col">OS</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grupo as $dado): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dado['ip']); ?></td>
                            <td><?php echo htmlspecialchars($dado['name']); ?></td>
                            <td><?php echo htmlspecialchars($dado['port']); ?></td>
                            <td><?php echo htmlspecialchars($dado['protocol']); ?></td>
                            <td><?php echo htmlspecialchars($dado['service']); ?></td>
                            <td><?php echo htmlspecialchars($dado['os']); ?></td>
                            <?php
                            switch ($dado['status']) {
                                case 'open':
                                    $badgeClass = 'text-bg-success';
                                    break;
                                case 'filtered':
                                    $badgeClass = 'text-bg-warning';
                                    break;
                                default:
                                    $badgeClass = 'text-bg-danger';
                            }
                            ?>
                            <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($dado['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../src/js/searchBar.js"></script>
</body>

</html>
