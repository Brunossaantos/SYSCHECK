<!DOCTYPE html>
<html lang="pt-BR">

<?php

use Util\Util;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Finalizado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2,
        h3 {
            color: #333;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-header {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        .imagem {
            width: 100px;
        }

    </style>
</head>

<body>

    <div>
        <a class="btn btn-danger btn-block" href="/syscheck/usuario/logout">Finalizar checklist</a>
        <br>
        <br>
    </div>

    <h1><?= $tipo->getDescricaoTipoChecklist() ?></h1>
    <h2><?= $itemChecado->getDescricaoObjeto() ?></h2>
    <p><strong>Data de início: </strong><?= $checklist->getDataInicio() ?></p>
    <p><strong>Data de finalização do checklist: </strong><?= $checklist->getDataFim() ?></p>
    <p><strong>Status do checklist: </strong><?= Util::statusChecklist($checklist->getStatusChecklist()) ?></p>
    <p><strong>Responsável: </strong><?= $responsavel->getNome() ?></p>
    <p><strong>Número do checklist: </strong><?= $checklist->getIdChecklist() ?></p>

    <p>Caso a resposta da "Ação" da etapa seja "NÃO", descrever o motivo no campo "Observação" e informar seu Líder.</p>

    <?php
        
    ?>

    <?php if($empilhadeira){?>

        <table>
            <thead>
                <tr>
                    <th>Horimetro inicial</th>                    
                    <th><?=(isset($listaHorimetros[0]['horimetro']) ? $listaHorimetros[0]['horimetro']."horas" : "Horimetro inicial não preenchido.")?></th>                
                </tr>
                <tr>    
                    <th>Horimetro final</th>
                    <th><?=(isset($listaHorimetros[1])) ? $listaHorimetros[1]['horimetro']."horas" : "Horimetro final não preechido."?></th>                    
                </tr>
            </thead>
        </table>

    <?php }?>

    <?php if($empilhadeiraBateriaComum){ ?>
        <table>
            <thead>
                <tr>
                    <th>Nivel da bateria no inicio do expediente</th>
                    <th><?=$nivelBateria?>%</th>
                </tr>
            </thead>
        </table>
        
    <?php }?>
 
    
    <?php if($empilhadeiraEletrica){?>
    <table>
        <thead>
            <tr>
                <th>Número da bateria</th>
                <th>Descrição da bateria</th>
                <th>Nível de carga da bateria</th>
                <th>Data e hora da troca</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaBaterias as $baterias){ ?>
            <tr>
                <th><?=$baterias['BATERIA']?></th>
                <th><?=$baterias['DESC_BATERIA']?></th>
                <th><?=$baterias['NIVEL_BATERIA']."%"?></th>
                <th><?=$baterias['DATA_HORA']?></th>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <?php }?>

    <!-- Seção Ar-condicionado -->

    <?php
    foreach ($listaTitulos as $titulo) {
    ?>

        <div class="section">
            <h2><?= $titulo ?></h2>

            <table>
                <thead>
                    <tr>
                        <th>Etapas</th>
                        <th>Ações</th>
                        <th>Aprovado \  Reprovado</th>                        
                        <th>Observação</th>
                        <!--<th>Nome Analista / Hora</th>-->
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaEtapas as $etapas) {
                        if ($titulo == $etapas['TITULO']) {
                    ?>
                            <tr>
                                <td><?= $etapas['NUMERO_ETAPA'] ?></td>
                                <td><?= $etapas['CONTEUDO'] ?></td>
                                <td><?= ($etapas['ACAO'] == 1) ? 'APROVADO' : 'REPROVADO' ?></td>                                
                                <!--<td><?= $etapa['OBSERVACAO'] ?></td>-->
                                <td>
                                    <?php
                                    echo (!empty($etapas['OBSERVACAO'])) ? $etapas['OBSERVACAO'] : '';
                                    ?>
                                </td>


                                <!--<td><?= $etapas['OBSERVACAO'] ?></td>-->
                                <!--<td><?= $usuario->getNome() ?></td>-->

                                <!--<td>
                        <?php
                            foreach ($listaFotos as $foto) {
                                if ($foto->getNumeroEtapa() == $etapas['NUMERO_ETAPA']) {
                                    echo "<img class='imagem' src=/syscheck/src/views/" . $foto->getCaminhoFoto() . " alt='Foto da etapa' loading='lazy'>";
                                } else {
                                    echo "etapa sem foto";
                                }
                            }
                        ?>
                    </td>-->

                                <td>
                                    <?php
                                    $fotoEncontrada = false; // Flag para rastrear se a foto foi encontrada
                                    foreach ($listaFotos as $foto) {
                                        if ($foto->getNumeroEtapa() == $etapas['NUMERO_ETAPA']) {
                                            echo "<img class='imagem' src='/syscheck/src/views/" . $foto->getCaminhoFoto() . "' alt='--' loading='lazy'>";
                                            $fotoEncontrada = true; // Marca que encontrou a foto
                                            break; // Sai do loop ao encontrar a primeira correspondência
                                        }
                                    }
                                    if (!$fotoEncontrada) {
                                        echo "etapa sem foto"; // Mostra a mensagem apenas se nenhuma foto for encontrada
                                    }
                                    ?>
                                </td>

                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

    <?php } ?>

    <!-- 
        tbl_checklists
        tbl_departamentos
        tbl_erros
        tbl_etapas_checklists
        tbl_etapas_realizadas
        tbl_fotos
        tbl_objetos
        tbl_tipos_checklist
        tbl_usuarios

        -- views --

        v_checklist_conteudo_etapas
        v_checklist_visao_geral
        v_quantidade_etapas_checklist
    -->

    <!-- Adicionar mais seções replicando as informações conforme o PDF -->

</body>

</html>