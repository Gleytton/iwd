<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Web Dashboard</title>
    <link rel="stylesheet" href="src/style.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <header>
        <!--Navebar -->
        <nav class="navbar navbar-expand-md bg-primary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo-home-horizontal.svg" alt="IMPA" width="105" height="38">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <!-- Histórico -->
                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="./php/history.php">
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
                    <dt><a class="botao" href="./php/index.php" role="presentation" class="botao" active>IP</a></dt>
                    <dt><a class="botao" href="./php/redes.php" class="botao">Redes</a></dt>
                </div>
                        <dl class="entrada">                      
                                <div class="formulario">
                                    <form id="ip-form" action="./php/results.php" method="post" >
                                        <label for="ip-input">IP:</label>
                                        <input type="text" name="ip" id="ip-input" required>
                                        <button id="button" class="btn btn-primary" type="submit" class="loading" onclick="click(event)">Scan</button>

                                        <div id="res"></div>
                                        <div class="d-flex justify-content-center d-none" id="loading" >
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        </dl>
                    </div>
            </section>
        </div>
    </main>
    <footer>
        <div class="container">
            <p class="text-center">© 2025 IMPA - Instituto de Matemática Pura e Aplicada</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
    <script>
        document.getElementById("ip-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Impede o envio imediato do formulário
        
        document.getElementById("loading").classList.remove("d-none");
        
        this.submit(); // Envia o formulário após o alerta
    });
</script>
</body>

</html>