<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Cadastro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">
</head>

<body>
    <?php include __DIR__ . '/../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Abertura de chamados</h2>
        <form action="/syscheck/chamado/abrirchamado" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="nome">Usuario</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= $usuario->getNome() ?>" readonly>
                <input type="hidden" name="fkusuario" value="<?=$usuario->getIdUsuario()?>">
            </div>

            <div class="form-group">
                <label for="departamento">Tipo do chamado</label>
                <select class="form-control" name="fktipo" id="">
                    <option value="--" selected disabled>Selecione o tipo do chamado</option>
                    <?php foreach($listaTipos as $tipo){?>
                        <option value="<?=$tipo->getIdTipoChecklist()?>"><?=$tipo->getDescricaoTipoChecklist()?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="cargo">Equipamento</label>
                <select class="form-control" name="fkequipamento" id="">
                    <option value="--" selected disabled>Selecione o equipamento</option>
                    <?php foreach($listaEquipamentos as $equipamento){?>
                        <option value="<?=$equipamento->getIdObjeto()?>"><?=$equipamento->getDescricaoObjeto()?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group">
                <label for="nomeusuario">Descrição do chamado</label>
                <textarea class="form-control" name="deschamado" id=""></textarea>
            </div>

            <!--<div class="form-group">
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <div class="input-group">
                        <input type="file" class="form-control d-none" id="foto" name="foto">
                        <label class="input-group-text btn btn-primary" for="foto">Selecionar</label>
                        <input type="text" class="form-control" id="file-name" placeholder="Nenhum arquivo selecionado" readonly>
                    </div>
                </div>
            </div>-->

            <div class="form-group">
                <label for="foto" class="form-label">Fotos</label>
                <div class="input-group">
                    <input type="file" class="form-control d-none" id="foto" name="fotos[]" multiple>
                    <label class="input-group-text btn btn-primary" for="foto">Selecionar</label>
                    <input type="text" class="form-control" id="file-name" placeholder="Nenhum arquivo selecionado" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="statususuario">Data e hora de abertura do</label>
                <input type="text" class="form-control" name="datahora" value="<?= $dataHora ?>" readonly>
            </div>

            <button type="submit" class="btn btn-danger">Salvar cadastro</button>
        </form>

        <hr>

    </div>

    <?php include __DIR__ . '/../../public/components/footer.php'; ?>

    <!--<script>
        document.getElementById("foto").addEventListener("change", function() {
            var file = this.files[0];
            var fileNameInput = document.getElementById("file-name");
            var errorDiv = document.getElementById("file-error");

            if (file) {
                var fileName = file.name;
                var fileExtension = fileName.split('.').pop().toLowerCase();
                var validExtensions = ["jpg", "jpeg", "png", "gif"];

                if (validExtensions.includes(fileExtension)) {
                    fileNameInput.value = fileName; // Exibe o nome do arquivo
                    errorDiv.style.display = "none"; // Oculta mensagem de erro
                } else {
                    alert("Apenas fotos podem ser enviadas.");
                    fileNameInput.value = ""; // Limpa o campo
                    errorDiv.style.display = "block"; // Exibe mensagem de erro
                    this.value = ""; // Reseta o input file
                }
            } else {
                fileNameInput.value = "Nenhum arquivo selecionado"; // Caso o usuário remova a seleção
                errorDiv.style.display = "none"; // Oculta mensagem de erro
            }
        });
    </script>-->

    <script>
        document.getElementById("foto").addEventListener("change", function() {
            var files = this.files;
            var fileNameInput = document.getElementById("file-name");
            var errorDiv = document.getElementById("file-error");
            var validExtensions = ["jpg", "jpeg", "png", "gif"];
            var fileNames = [];

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var fileExtension = file.name.split('.').pop().toLowerCase();

                if (validExtensions.includes(fileExtension)) {
                    fileNames.push(file.name);
                } else {
                    alert("Apenas fotos podem ser enviadas.");
                    this.value = ""; // Reseta o input file
                    fileNameInput.value = "";
                    return;
                }
            }

            fileNameInput.value = fileNames.length > 0 ? fileNames.join(", ") : "Nenhum arquivo selecionado";
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>