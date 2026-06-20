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
        <!--Navebar -->
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
                        <!-- Histórico -->
                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="history.php">
                                Histórico
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="layout">
        <div class="container">
            <!-- Titulo -->

            <h2 class="titulo">IWD - Interface Web Dashboard</h2>
            <!-- Descrição do programa -->
            <h6 class="titulo" mt-3>Ferramenta de monitoramento de redes e sub-redes do IMPA</h6>
            <!-- Entrada de dados -->
            <section>
                    <div class="opcao" >
                        <dt><a class="botao" href="../index.php" role="presentation" class="botao">IP</a></dt>
                        <dt><a class="botao" href="redes.php" class="botao" active>Redes</a></dt>
                    </div>             
                            <dl class="entrada">                               
                                <!-- Entrada de dados por redes -->
                                <div class="formulario">
                                    <form action="results.php" method="post" style="display: flex; flex-direction: row; align-items: center; gap: 10px;">
                                        <select class="form-select" aria-label="Redes do IMPA" name="ip" required>
                                            <option selected disabled>Redes IMPA</option>
                                            <option value="147.65.1.0">147.65.1.0</option>
                                            <option value="147.65.3.0">147.65.3.0</option>
                                            <option value="147.65.4.0">147.65.4.0</option>
                                            <option value="147.65.5.0">147.65.5.0</option>
                                            <option value="147.65.6.0">147.65.6.0</option>
                                            <option value="147.65.7.0">147.65.7.0</option>
                                            <option value="147.65.11.0">147.65.11.0</option>
                                            <option value="147.65.14.0">147.65.14.0</option>
                                            <option value="147.65.20.0">147.65.20.0</option>
                                            <option value="147.65.23.0">147.65.23.0</option>
                                            <option value="147.65.24.0">147.65.24.0</option>
                                            <option value="147.65.25.0">147.65.25.0</option>
                                            <option value="147.65.100.0">147.65.100.0</option>
                                            <option value="147.65.105.0">147.65.105.0</option>
                                            <option value="147.65.107.0">147.65.107.0</option>
                                            <option value="147.65.110.0">147.65.110.0</option>
                                            <option value="147.65.112.0">147.65.112.0</option>
                                            <option value="147.65.120.0">147.65.120.0</option>
                                            <option value="147.65.160.0">147.65.160.0</option>
                                            <option value="147.65.177.0">147.65.177.0</option>
                                            <option value="147.65.190.0">147.65.190.0</option>
                                            <option value="147.65.192.0">147.65.192.0</option>
                                            <option value="147.65.254.16">147.65.254.16</option>
                                            <option value="192.168.14.0">192.168.14.0</option>
                                            <option value="192.168.130.0">192.168.130.0</option>
                                            <option value="192.168.131.0">192.168.131.0</option>
                                            <option value="192.168.132.0">192.168.132.0</option>
                                            <option value="192.168.134.0">192.168.134.0</option>
                                            <option value="192.168.136.0">192.168.136.0</option>
                                            <option value="192.168.168.0">192.168.168.0</option>
                                        </select>
                                        <button class="btn btn-primary" type="submit">Scan</button>
                                    </form>
                                </div>   
                            </dl>
                    </div>
            </section>

        </div>
    </main>
        <div class="container">
            <p class="text-center">© 2025 IMPA - Instituto de Matemática Pura e Aplicada</p>
        </div>
    <script src="../src/js/searchBar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>