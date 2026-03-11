<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Chamado;
use Exception;
use Util\Util;
use DateTime;

class DaoChamado
{
    /**
     * DAO responsável pelas operações de banco dos chamados.
     * Inclui salvar, atualizar, listar, follow-up e fotos.
     */

    private $conexao;
    private $idUsuarioSessao;

    // Referências das tabelas
    private $tbl_chamados = TBL_CHAMADOS;
    private $tbl_fotos_chamados = TBL_FOTOS_CHAMADOS;
    private $tbl_follow_up_chamados = TBL_FOLLOW_UP_CHAMADOS;

    function __construct($conexao, $idUsuarioSessao)
    {
        // Conexão MySQLi e usuário logado para registrar erros
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    /**
     * Salva um novo chamado no banco
     * @return int|null ID inserido, -1 erro execute, null erro interno
     */
    function salvarChamado(Chamado $chamado)
    {
        // Extrai dados do model
        $item = $chamado->getFkItemChamado();
        $descricao = $chamado->getDescricaoChamado();
        $dataAberturaChamado = $chamado->getDataAberturaChamado();
        $usuario = $chamado->getFkUsuario();
        $status = $chamado->getStatusChamado();

        try {
            // Insert principal
            $stmt = $this->conexao->prepare(
                "INSERT INTO {$this->tbl_chamados} 
                (FK_ITEM_CHAMADO, DESCRICAO_CHAMADO, DATA_ABERTURA_CHAMADO, FK_USUARIO, STATUS_CHAMADO) 
                VALUES (?,?,?,?,?)"
            );

            // Tipos: i (int), s (string)
            $stmt->bind_param("issii", $item, $descricao, $dataAberturaChamado, $usuario, $status);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1; // Falha sem exceção
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarChamado", $this->idUsuarioSessao);
            return null;
        }
    }

    /**
     * Atualiza status e data de finalização do chamado
     * @return int número de linhas afetadas | -1 erro execute | -2 exceção
     */
    function atualizarChamado(Chamado $chamado)
    {
        // Dados essenciais
        $idChamado = $chamado->getIdChamado();
        $dataFinalizacaoChamado = $chamado->getDataFinalizacaoChamado();
        $status = $chamado->getStatusChamado();

        try {
            // Atualiza apenas o que realmente muda
            $stmt = $this->conexao->prepare(
                "UPDATE {$this->tbl_chamados} 
                SET DATA_FINALIZACAO_CHAMADO = ?, STATUS = ? 
                WHERE ID_CHAMADO = ?"
            );

            $stmt->bind_param("sii", $dataFinalizacaoChamado, $status, $idChamado);

            if ($stmt->execute()) {
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "atualizarChamado", $this->idUsuarioSessao);
            return -2;
        }
    }

    /**
     * Seleciona um chamado específico pelo ID
     * @return Chamado|null
     */
    function selecionarChamado($idChamado)
    {
        try {
            $stmt = $this->conexao->prepare(
                "SELECT ID_CHAMADO, FK_ITEM_CHAMADO, DESCRICAO_CHAMADO, 
                        DATA_ABERTURA_CHAMADO, DATA_FINALIZACAO_CHAMADO, FK_USUARIO, STATUS_CHAMADO
                 FROM {$this->tbl_chamados}
                 WHERE ID_CHAMADO = ?"
            );

            $stmt->bind_param("i", $idChamado);

            $stmt->execute();
            $result = $stmt->get_result();

            // Constrói o model se existir
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                return new Chamado(
                    $row['ID_CHAMADO'],
                    $row['FK_ITEM_CHAMADO'],
                    $row['DESCRICAO_CHAMADO'],
                    $row['DATA_ABERTURA_CHAMADO'],
                    $row['DATA_FINALIZACAO_CHAMADO'],
                    $row['FK_USUARIO'],
                    $row['STATUS_CHAMADO']
                );
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarChamado", $this->idUsuarioSessao);
            return null;
        }
    }

    /**
     * Lista todos os chamados com joins
     * Preenche automaticamente nome do usuário e nome do equipamento
     */
    function listarChamados()
    {
        try {
            // Query completa já trazendo os nomes necessários
            $sql = "
                SELECT 
                    c.ID_CHAMADO,
                    c.FK_ITEM_CHAMADO,
                    c.DESCRICAO_CHAMADO,
                    c.DATA_ABERTURA_CHAMADO,
                    c.DATA_FINALIZACAO_CHAMADO,
                    c.FK_USUARIO,
                    c.STATUS_CHAMADO,

                    u.NOME AS nomeUsuario,
                    o.DESCRICAO_OBJETO AS nomeEquipamento

                FROM {$this->tbl_chamados} c
                LEFT JOIN tbl_usuarios u ON u.ID_USUARIO = c.FK_USUARIO
                LEFT JOIN tbl_objetos o ON o.ID_OBJETO = c.FK_ITEM_CHAMADO
                ORDER BY c.ID_CHAMADO DESC
            ";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();

            $listaChamados = [];

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    // Monta o objeto Chamado completo
                    $chamado = new Chamado(
                        $row['ID_CHAMADO'],
                        $row['FK_ITEM_CHAMADO'],
                        $row['DESCRICAO_CHAMADO'],
                        $row['DATA_ABERTURA_CHAMADO'],
                        $row['DATA_FINALIZACAO_CHAMADO'],
                        $row['FK_USUARIO'],
                        $row['STATUS_CHAMADO']
                    );

                    // Preenche dados adicionais vindos do JOIN
                    $chamado->setNomeUsuario($row['nomeUsuario'] ?? "Desconhecido");
                    $chamado->setNomeEquipamento($row['nomeEquipamento'] ?? "Sem equipamento");

                    $listaChamados[] = $chamado;
                }
            }

            return $listaChamados;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarChamados", $this->idUsuarioSessao);
            return null;
        }
    }

    /**
     * Salva um follow-up textual do chamado
     * @return int id inserido | -1 falha execute | -2 exceção
     */
    function salvarFollowup($followup)
    {
        $fkChamado = $followup['fkChamado'];
        $fkUsuario = $followup['fkUsuario'];
        $desc = $followup['desc'];

        // Data gerada no backend
        $data = (new DateTime())->format('d/m/Y H:i');

        try {
            $stmt = $this->conexao->prepare(
                "INSERT INTO {$this->tbl_follow_up_chamados} 
                (FK_CHAMADO, FK_USUARIO, FOLLOW_UP, DATA_HORA) 
                 VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param("iiss", $fkChamado, $fkUsuario, $desc, $data);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarFollowUp", $this->idUsuarioSessao);
            return -2;
        }
    }

    /**
     * Lista todos os follow-ups de um chamado
     * @return array
     */
    function listarFollowUp($fkChamado)
    {
        try {
            $stmt = $this->conexao->prepare(
                "SELECT ID_FOLLOW_UP, FK_CHAMADO, FK_USUARIO, FOLLOW_UP, DATA_HORA
                 FROM {$this->tbl_follow_up_chamados}
                 WHERE FK_CHAMADO = ?
                 ORDER BY ID_FOLLOW_UP DESC"
            );

            $stmt->bind_param("i", $fkChamado);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                // Monta array manual simples
                while ($row = $result->fetch_assoc()) {
                    $followUp[] = [
                        'idFollowUp' => $row['ID_FOLLOW_UP'],
                        'fkChamado' => $row['FK_CHAMADO'],
                        'fkUsuario' => $row['FK_USUARIO'],
                        'followUp' => $row['FOLLOW_UP'],
                        'dataHora' => $row['DATA_HORA']
                    ];
                }

                return $followUp;
            }

            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "listarFollowUp", $this->idUsuarioSessao);
            return [];
        }
    }

    /**
     * Salva caminho da imagem vinculada ao chamado
     * @return int|mixed
     */
    function salvarFoto($foto)
    {
        $fkChamado = $foto['fkChamado'];
        $caminhoImagem = $foto['path'];

        try {
            $stmt = $this->conexao->prepare(
                "INSERT INTO {$this->tbl_fotos_chamados} (FK_CHAMADO, CAMINHO_IMAGEM)
                 VALUES (?,?)"
            );

            $stmt->bind_param("is", $fkChamado, $caminhoImagem);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarFoto", $this->idUsuarioSessao);
            return -2;
        }
    }

    /**
     * Lista todas as fotos de um chamado
     * @return array
     */
    function listarFotosChamado($fkChamado)
    {
        try {
            $stmt = $this->conexao->prepare(
                "SELECT ID_FOTO_CHAMADO, FK_CHAMADO, CAMINHO_IMAGEM
                 FROM {$this->tbl_fotos_chamados}
                 WHERE FK_CHAMADO = ?"
            );

            $stmt->bind_param("i", $fkChamado);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $listaFotos[] = [
                        'idFoto' => $row['ID_FOTO_CHAMADO'],
                        'fkChamado' => $row['FK_CHAMADO'],
                        'caminhoImagem' => $row['CAMINHO_IMAGEM']
                    ];
                }

                return $listaFotos;
            }

            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "listarFotosChamado", $this->idUsuarioSessao);
            return [];
        }
    }

    public function listarChamadosPorUsuario($idUsuario)
    {
        try {
            $sql = "
            SELECT 
                c.ID_CHAMADO,
                c.FK_ITEM_CHAMADO,
                c.DESCRICAO_CHAMADO,
                c.DATA_ABERTURA_CHAMADO,
                c.DATA_FINALIZACAO_CHAMADO,
                c.FK_USUARIO,
                c.STATUS_CHAMADO,

                u.NOME AS nomeUsuario,
                o.DESCRICAO_OBJETO AS nomeEquipamento

            FROM {$this->tbl_chamados} c
            LEFT JOIN tbl_usuarios u ON u.ID_USUARIO = c.FK_USUARIO
            LEFT JOIN tbl_objetos o ON o.ID_OBJETO = c.FK_ITEM_CHAMADO
            WHERE c.FK_USUARIO = ?
            ORDER BY c.ID_CHAMADO DESC
        ";

            $stmt = $this->conexao->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Erro ao preparar SQL: ({$this->conexao->errno}) {$this->conexao->error}");
            }

            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            $lista = [];
            while ($row = $result->fetch_assoc()) {
                $chamado = new \models\Chamado(
                    $row['ID_CHAMADO'],
                    $row['FK_ITEM_CHAMADO'],
                    $row['DESCRICAO_CHAMADO'],
                    $row['DATA_ABERTURA_CHAMADO'],
                    $row['DATA_FINALIZACAO_CHAMADO'],
                    $row['FK_USUARIO'],
                    $row['STATUS_CHAMADO']
                );

                // Preenche dados adicionais
                $chamado->setNomeUsuario($row['nomeUsuario'] ?? "Desconhecido");
                $chamado->setNomeEquipamento($row['nomeEquipamento'] ?? "Sem equipamento");

                $lista[] = $chamado;
            }

            return $lista;
        } catch (\Exception $e) {
            \Util\Util::inserirErro($e, "listarChamadosPorUsuario", $this->idUsuarioSessao);
            return [];
        }
    }

    public function listarChamadosPorPerfis(array $perfis)
    {
        try {

            // Monta placeholders (?, ?, ?) dinamicamente
            $placeholders = implode(',', array_fill(0, count($perfis), '?'));
            $types = str_repeat('i', count($perfis));

            $sql = "
            SELECT 
                c.ID_CHAMADO,
                c.FK_ITEM_CHAMADO,
                c.DESCRICAO_CHAMADO,
                c.DATA_ABERTURA_CHAMADO,
                c.DATA_FINALIZACAO_CHAMADO,
                c.FK_USUARIO,
                c.STATUS_CHAMADO,

                u.NOME AS nomeUsuario,
                u.FK_PERFIL,
                o.DESCRICAO_OBJETO AS nomeEquipamento

            FROM {$this->tbl_chamados} c
            LEFT JOIN tbl_usuarios u ON u.ID_USUARIO = c.FK_USUARIO
            LEFT JOIN tbl_objetos o ON o.ID_OBJETO = c.FK_ITEM_CHAMADO

            WHERE u.FK_PERFIL IN ($placeholders)
            ORDER BY c.ID_CHAMADO DESC
        ";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bind_param($types, ...$perfis);
            $stmt->execute();

            $result = $stmt->get_result();
            $lista = [];

            while ($row = $result->fetch_assoc()) {

                $chamado = new \models\Chamado(
                    $row['ID_CHAMADO'],
                    $row['FK_ITEM_CHAMADO'],
                    $row['DESCRICAO_CHAMADO'],
                    $row['DATA_ABERTURA_CHAMADO'],
                    $row['DATA_FINALIZACAO_CHAMADO'],
                    $row['FK_USUARIO'],
                    $row['STATUS_CHAMADO']
                );

                $chamado->setNomeUsuario($row['nomeUsuario'] ?? "Desconhecido");
                $chamado->setNomeEquipamento($row['nomeEquipamento'] ?? "Sem equipamento");

                $lista[] = $chamado;
            }

            return $lista;
        } catch (\Exception $e) {
            \Util\Util::inserirErro($e, "listarChamadosPorPerfis", $this->idUsuarioSessao);
            return [];
        }
    }
}
