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
    
    <?php 
    use Util\Sessao;
    include __DIR__ . '/../../../public/components/navbar.php';
    
    $idUsuario = Sessao::idusuario();
    
    ?>


    <div class="container mt-5">
        <h2>Iniciar checklist</h2>
        <form action="/syscheck/checklist/salvarInicioChecklist" method="POST">
            <input type="hidden" name="fkusuario" value="<?=$idUsuario?>">

            <div class="form-group">
                <label for="nome">Tipo do checklist</label>
                <select class="form-control" name="fktipo" id="fktipo">
                    <option value="--" selected disabled>selecione o tipo do checklist</option>
                    <?php foreach($listaTipos as $tipoChecklist){?>
                        <option value="<?=$tipoChecklist->getIdTipoChecklist()?>"><?=$tipoChecklist->getDescricaoTipoChecklist()?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group">
                <label for="fkobjeto">Item Checado</label>
                <select class="form-control" name="fkobjeto" id="fkobjeto">
                    <option value="--">Selecione o item/local do checklist</option>
                </select>
            </div>
            <?php
                $data = (new DateTime())->format('d/m/y H:i:s');
            ?>
            <div class="form-group">
                <label for="datainicio">Data e hora de inicio do checklist</label>
                <input type="text" class="form-control" name="datainicio" id="datainicio" value="<?=$data?>" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Iniciar checklist</button>
        </form>

    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

<script>
            $(document).ready(function() {
            $('#fktipo').change(function() {
                var selectedValue = $(this).val();

                console.log(selectedValue);
                
                // Faz a requisição AJAX
                $.ajax({
                    url: '/syscheck/checklist/listarItens/'+selectedValue,
                    type: 'GET',                    
                    success: function(response) {
                        $('#fkobjeto').html(response);
                    }, 
                    error: function(xhr, status, erro){
                        console.error('Erro na requisição AJAX: ' + status + ' - ' + error);
                    }
                });
            });
        });
</script>

</html>
