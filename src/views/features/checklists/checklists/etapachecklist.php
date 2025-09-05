<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etapa</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: #fff;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
            font-size: 1.2rem;
        }
        .content-cell {
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e9ecef;
            border-radius: 10px;
            text-align: center;
            width: 100%;
        }
        .btn-group-custom .btn {
            width: 120px;
            margin: 0 5px;
            font-size: 1rem;
        }
    </style>
</head>

<?php

    // Inicializa a variável de observação
    $observacao = isset($_POST['observacao']) ? $_POST['observacao'] : "";    
    $url = $_SERVER['REQUEST_URI'];
    $partes = explode('/', $url);
    $idChecklist = $partes[4];
?>
<body>
    <?php 
        include_once __DIR__ . "/../../../public/components/navbar.php"
    ?>
    <div class="container mt-5">
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?=$numeroEtapa?></th>
                        <th><?=$titulo?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <div class="content-cell">
                                <table style="border: 0px">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?=$conteudo?>
                                            </td>
                                        </tr>
                                        <?php if(isset($observacao) && $observacao != ""){?>
                                            <tr>
                                                <td>
                                                    <span style="font-weight: bold; font-size: 14px">Observação:</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-weight: bold; font-size: 14px"><?=$observacao?></span></td>
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3 btn-group-custom">                
                <a href="/syscheck/etaparealizada/confirmaretapa/<?=$idChecklist?>/<?=$fkTipo?>/<?=$numeroEtapa?>/<?=urlencode($observacao)?>" class="btn btn-success">Aprovado</a>
                <a href="/syscheck/etaparealizada/reprovarEtapa/<?=$idChecklist?>/<?=$fkTipo?>/<?=$numeroEtapa?>/<?=urlencode($observacao)?>" class="btn btn-warning text-white">Reprovado</a>
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalFoto">Foto</button>
                <!-- Botão para abrir o modal -->
                <button class="btn btn-danger" data-toggle="modal" data-target="#modalObservacao">Observação</button>
            </div>
        </div>
    </div>

    <!-- Modal para observações -->
    <div class="modal fade" id="modalObservacao" tabindex="-1" role="dialog" aria-labelledby="modalObservacaoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalObservacaoLabel">Adicionar Observação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Formulário que envia a observação -->
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="4"><?= htmlspecialchars($observacao) ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar Observação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para envio de foto -->

    <div class="modal fade" id="modalFoto" tabindex="-1" role="dialog" aria-labelledby="modalFotoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFotoLabel">Enviar Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/syscheck/foto/uploadimagem" enctype="multipart/form-data">
                    <input type="hidden" name="url" value="<?=$url?>">
                    <input type="hidden" name="fkchecklist" value="<?=$idChecklist?>">
                    <input type="hidden" name="numeroEtapa" value="<?=$numeroEtapa?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="foto">Escolher uma foto</label>
                            <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Enviar Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

<script>
    
    

</script>

<!-- Bloqueio de reprovação sem observação e sem foto -->
<!--<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector(".btn-warning").addEventListener("click", function (event) {
            event.preventDefault(); // Impede a navegação imediata

            let observacao = document.getElementById("observacao").value.trim();
            let idChecklist = "<?=$idChecklist?>";
            let numeroEtapa = "<?=$numeroEtapa?>";

            let observacaoEncoded = encodeURIComponent(observacao).replace(/%20/g, "+");

            if (!observacao) {
                alert("Para reprovar uma etapa é obrigatório o envio de uma observação como justificativa.");
                return;
            }

            let urlVerificacao = `http://10.80.0.10:8080/syscheck/checklist/verificarFotoPorEtapa/${idChecklist}/${numeroEtapa}`;

            fetch(urlVerificacao)
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "") {
                        alert("Para reprovar uma etapa é obrigatório o envio de uma foto e observação como justificativa.");
                    } else {
                        window.location.href = `/syscheck/etaparealizada/reprovarEtapa/${idChecklist}/<?=$fkTipo?>/${numeroEtapa}/${observacaoEncoded}`;
                    }
                })
                .catch(error => {
                    console.error("Erro ao verificar a foto:", error);
                    alert("Erro ao verificar a foto. Tente novamente.");
                });
        });
    });
</script>-->

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Variável que indica se a foto é obrigatória
    const fotoObrigatoria = <?= json_encode($fotoObrigatoria); ?>;
    const idChecklist = "<?=$idChecklist?>";
    const numeroEtapa = "<?=$numeroEtapa?>";

    // Função para verificar a foto
    function verificarFoto() {
        const urlVerificacao = `/syscheck/checklist/verificarFotoPorEtapa/${idChecklist}/${numeroEtapa}`;
        
        return fetch(urlVerificacao)
            .then(response => response.text())
            .then(data => {
                return data.trim(); // Retorna o conteúdo da foto ou vazio
            })
            .catch(error => {
                console.error("Erro ao verificar a foto:", error);
                alert("Erro ao verificar a foto. Tente novamente.");
                return "";
            });
    }

    // Evento de clique no botão Aprovado
    document.querySelector(".btn-success").addEventListener("click", function (event) {
        // Verifica se a foto é obrigatória
        if (fotoObrigatoria) {
            // Chama a função para verificar a foto
            verificarFoto().then(foto => {
                if (foto === "") {
                    // Se não houver foto, exibe o alerta
                    alert("Essa etapa exige que uma foto seja enviada.");
                } else {
                    // Caso contrário, segue com a navegação
                    window.location.href = `/syscheck/etaparealizada/confirmaretapa/${idChecklist}/<?=$fkTipo?>/${numeroEtapa}`;
                }
            });
            event.preventDefault(); // Impede a navegação imediata
        }
    });

    // Similar para o botão de reprovar
    document.querySelector(".btn-warning").addEventListener("click", function (event) {
        event.preventDefault(); // Impede a navegação imediata

        let observacao = document.getElementById("observacao").value.trim();

        if (!observacao) {
            alert("Para reprovar uma etapa é obrigatório o envio de uma observação como justificativa.");
            return;
        }

        if (fotoObrigatoria) {
            // Chama a função para verificar a foto
            verificarFoto().then(foto => {
                if (foto === "") {
                    alert("Para reprovar uma etapa é obrigatório o envio de uma foto e observação como justificativa.");
                } else {
                    let observacaoEncoded = encodeURIComponent(observacao).replace(/%20/g, "+");
                    window.location.href = `/syscheck/etaparealizada/reprovarEtapa/${idChecklist}/<?=$fkTipo?>/${numeroEtapa}/${observacaoEncoded}`;
                }
            });
        } else {
            let observacaoEncoded = encodeURIComponent(observacao).replace(/%20/g, "+");
            window.location.href = `/syscheck/etaparealizada/reprovarEtapa/${idChecklist}/<?=$fkTipo?>/${numeroEtapa}/${observacaoEncoded}`;
        }
    });
});




document.addEventListener("DOMContentLoaded", function () {
    const btnAprovado = document.querySelector(".btn.btn-success");
    const btnReprovado = document.querySelector(".btn.btn-warning");

    function bloquearBotao(btn) {
        if (!btn) return;

        btn.addEventListener("click", function (e) {
            if (btn.disabled) {
                e.preventDefault();
                return false;
            }

            // desabilita ANTES de continuar
            btn.disabled = true;

            // salva o texto original (caso queira restaurar depois)
            const originalText = btn.innerHTML;

            // adiciona spinner
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                ◌
            `;

            // garante que o form vai ser submetido corretamente
            const form = btn.closest("form");
            if (form) {
                form.submit();
            }
        });
    }

    // aplica nos dois botões
    bloquearBotao(btnAprovado);
    bloquearBotao(btnReprovado);
});





</script>


<?php
    // Verifica se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['observacao'])) {
        // Armazena o valor da observação na variável PHP
        $observacao = $_POST['observacao'];
        // Opcional: exibir ou salvar a observação
        //echo "<script>alert('Observação salva: " . htmlspecialchars($observacao) . "');</script>";
    }
?>
