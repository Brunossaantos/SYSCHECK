<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Checklist;
use Exception;
use Util\Util;
use service\PermissaoService;
use Util\Sessao;

class DaoChecklist
{
    private \mysqli $conexao;
    private int $idUsuarioSessao;
    private int $idEmpresaSessao;
    private string $tbl_checklists = TBL_CHECKLISTS;
    private string $view_checklists = V_CHECKLIS_VISAO_GERAL;
    private string $view_checklists_horimetros = V_CHECKLISTS_HORIMETRO;

    public function __construct($conexao, int $idUsuarioSessao, int $idEmpresaSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
        $this->idEmpresaSessao = $idEmpresaSessao;
    }

    // =========================
    // CRUD BÁSICO
    // =========================



    public function iniciarChecklist(Checklist $checklist): int
    {
        try {
            $stmt = $this->conexao->prepare("
            INSERT INTO {$this->tbl_checklists} 
            (FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, STATUS_CHECKLIST, fk_empresa)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

            // CORREÇÃO: Usar as propriedades da classe ($this->), 
            // que foram injetadas com o ID do crachá (49)
            $fkUsuario       = $this->idUsuarioSessao;
            $fkEmpresa       = $this->idEmpresaSessao;

            $fkTipo          = $checklist->getFkTipo();
            $fkObjeto        = $checklist->getFkObjeto();
            $dataInicio      = $checklist->getDataInicio();
            $statusChecklist = $checklist->getStatusChecklist();

            $stmt->bind_param(
                "iiisii",
                $fkUsuario,
                $fkTipo,
                $fkObjeto,
                $dataInicio,
                $statusChecklist,
                $fkEmpresa
            );

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }
            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "iniciarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    public function selecionarChecklist(int $idChecklist): ?Checklist
    {
        try {
            $stmt = $this->conexao->prepare("
            SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO,
                   DATA_INICIO, DATA_FIM, STATUS_CHECKLIST
            FROM {$this->tbl_checklists}
            WHERE ID_CHECKLIST = ?
        ");

            $stmt->bind_param("i", $idChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) return null;

            $row = $result->fetch_assoc();

            return new Checklist(
                $row['ID_CHECKLIST'],   // idChecklist
                $row['FK_USUARIO'],     // fkUsuario
                '',                     // usuario (vazio se não houver)
                $row['FK_TIPO'],        // fkTipo
                $row['FK_OBJETO'],      // fkObjeto
                $row['DATA_INICIO'],    // dataInicio
                $row['DATA_FIM'],       // dataFim
                $row['STATUS_CHECKLIST'] // statusChecklist
            );
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarChecklist", $this->idUsuarioSessao);
            return null;
        }
    }
    public function atualizarChecklist(Checklist $checklist): int
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE {$this->tbl_checklists}
                SET DATA_FIM = ?, STATUS_CHECKLIST = ?
                WHERE ID_CHECKLIST = ?
            ");

            $dataFim    = $checklist->getDataFim();
            $status     = $checklist->getStatusChecklist();
            $idChecklist = $checklist->getIdChecklist();

            $stmt->bind_param(
                "sii",
                $dataFim,
                $status,
                $idChecklist
            );

            return $stmt->execute() ? $stmt->affected_rows : -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "atualizarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    // =========================
    // HORÍMETRO
    // =========================

    public function recuperarHorimetrosPorChecklist(int $fkUsuario): array
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO, 
                       DATA_INICIO, DATA_FIM, STATUS_CHECKLIST,
                       HORIMETRO_INICIAL, HORIMETRO_FINAL
                FROM {$this->view_checklists_horimetros}
                WHERE FK_USUARIO = ?
                ORDER BY ID_CHECKLIST DESC
            ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            $lista = [];
            while ($row = $result->fetch_assoc()) {
                $lista[] = [
                    'idChecklist'      => $row['ID_CHECKLIST'],
                    'usuario'          => $row['FK_USUARIO'],
                    'tipo'             => $row['FK_TIPO'],
                    'empilhadeira'     => $row['FK_OBJETO'],
                    'dataInicio'       => $row['DATA_INICIO'],
                    'dataFim'          => $row['DATA_FIM'],
                    'status'           => $row['STATUS_CHECKLIST'],
                    'horimetroInicial' => $row['HORIMETRO_INICIAL'],
                    'horimetroFinal'   => $row['HORIMETRO_FINAL'],
                ];
            }

            return $lista;
        } catch (Exception $e) {
            Util::inserirErro($e, "recuperarHorimetrosPorChecklist", $this->idUsuarioSessao);
            return [];
        }
    }

    // =========================
    // PENDÊNCIA CHECKLIST
    // =========================

    public function verificarChecklistPorUsuario(int $fkUsuario): ?Checklist
    {
        try {
            $stmt = $this->conexao->prepare("
            SELECT *
            FROM {$this->tbl_checklists}
            WHERE FK_USUARIO = ?
              AND STATUS_CHECKLIST = 1
            ORDER BY ID_CHECKLIST DESC
            LIMIT 1
        ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) return null;

            $row = $result->fetch_assoc();

            return new Checklist(
                $row['ID_CHECKLIST'],   // idChecklist
                $row['FK_USUARIO'],     // fkUsuario
                '',                     // usuario (vazio se não houver)
                $row['FK_TIPO'],        // fkTipo
                $row['FK_OBJETO'],      // fkObjeto
                $row['DATA_INICIO'],    // dataInicio
                $row['DATA_FIM'],       // dataFim
                $row['STATUS_CHECKLIST'] // statusChecklist
            );
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarChecklistPorUsuario", $this->idUsuarioSessao);
            return null;
        }
    }
    public function verificarChecklistPendente(int $fkUsuario): int
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT COUNT(ID_CHECKLIST) AS QTD
                FROM {$this->tbl_checklists}
                WHERE FK_USUARIO = ?
                  AND STATUS_CHECKLIST = 1
            ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return (int)$row['QTD'];
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarChecklistPendente", $this->idUsuarioSessao);
            return 0;
        }
    }

    // =========================
    // CONTROLE VEICULAR
    // =========================

    public function veicularAtivo(): bool
    {
        $stmt = $this->conexao->prepare("
            SELECT checklist_veicular 
            FROM tbl_usuarios 
            WHERE ID_USUARIO = ? 
            LIMIT 1
        ");

        if (!$stmt) return false;

        $stmt->bind_param("i", $this->idUsuarioSessao);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) return false;

        $dados = $result->fetch_assoc();
        return (int)$dados['checklist_veicular'] === 1;
    }

    public function listarChecklistsVeiculares()
    {
        // 1. Mudamos o SQL para fazer INNER JOIN e buscar as descrições/nomes
        $sql = "
        SELECT 
            c.*, 
            u.NOME AS NOME_USUARIO, 
            o.DESCRICAO_OBJETO AS NOME_VEICULO
        FROM tbl_checklists c
        INNER JOIN tbl_usuarios u ON c.FK_USUARIO = u.ID_USUARIO
        INNER JOIN tbl_objetos o ON c.FK_OBJETO = o.ID_OBJETO
        WHERE c.FK_TIPO = 1 
        AND c.FK_EMPRESA = ?
        ORDER BY c.ID_CHECKLIST DESC
    ";

        $stmt = $this->conexao->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conexao->error);
        }

        $stmt->bind_param("i", $this->idEmpresaSessao);
        $stmt->execute();

        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()) {
            // 2. No lugar dos IDs (FK_USUARIO e FK_OBJETO), 
            // passamos os Nomes que vieram do JOIN para o Model
            $lista[] = new \models\Checklist(
                $row['ID_CHECKLIST'],
                $row['NOME_USUARIO'], // Antes era FK_USUARIO
                null,
                $row['FK_TIPO'],
                $row['NOME_VEICULO'], // Antes era FK_OBJETO
                $row['DATA_INICIO'],
                $row['DATA_FIM'],
                $row['STATUS_CHECKLIST']
            );
        }

        return $lista;
    }
    // =========================
    // FILTRAGEM E LISTAGEM
    // =========================

    public function filtrarChecklists(array $filtros = []): array
    {
        try {
            $permissaoService = new \service\PermissaoService(
                $this->conexao,
                $this->idUsuarioSessao,
                $this->idEmpresaSessao
            );

            $usuariosPermitidos = $permissaoService->getUsuariosPermitidos();

            if (empty($usuariosPermitidos)) {
                return [];
            }

            $placeholdersUsuarios = implode(',', array_fill(0, count($usuariosPermitidos), '?'));

            $sql = "
            SELECT NUMERO_CHECKLIST, USUARIO, TIPO, OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST, FK_USUARIO
            FROM {$this->view_checklists}
            WHERE FK_EMPRESA = ?
            AND FK_USUARIO IN ($placeholdersUsuarios)
        ";

            $params = [$this->idEmpresaSessao];
            $types  = "i";

            foreach ($usuariosPermitidos as $idUsuario) {
                $params[] = $idUsuario;
                $types .= "i";
            }

            // Aplicar filtros opcionais
            if (!empty($filtros['numero'])) {
                $sql .= " AND NUMERO_CHECKLIST = ?";
                $params[] = $filtros['numero'];
                $types .= "i";
            }

            if (!empty($filtros['status']) && $filtros['status'] != 0) {
                $sql .= " AND STATUS_CHECKLIST = ?";
                $params[] = $filtros['status'];
                $types .= "i";
            }

            if (!empty($filtros['usuario'])) {
                $sql .= " AND FK_USUARIO = ?";
                $params[] = $filtros['usuario'];
                $types .= "i";
            }

            if (!empty($filtros['tipo'])) {
                $sql .= " AND TIPO = ?";
                $params[] = $filtros['tipo'];
                $types .= "s";
            }

            if (!empty($filtros['objeto'])) {
                $sql .= " AND OBJETO = ?";
                $params[] = $filtros['objeto'];
                $types .= "s";
            }

            if (!empty($filtros['data_inicio'])) {
                $sql .= " AND DATE(DATA_INICIO) = ?";
                $params[] = $filtros['data_inicio'];
                $types .= "s";
            }

            $sql .= " ORDER BY NUMERO_CHECKLIST DESC";

            $stmt = $this->conexao->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Erro na SQL: " . $this->conexao->error . " | SQL: " . $sql);
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            $result = $stmt->get_result();
            $checklists = [];

            while ($row = $result->fetch_assoc()) {
                $checklists[] = new \models\Checklist(
                    $row['NUMERO_CHECKLIST'], // idChecklist
                    $row['FK_USUARIO'],       // fkUsuario
                    $row['USUARIO'] ?? '',    // usuario
                    $row['TIPO'],             // fkTipo
                    $row['OBJETO'],           // fkObjeto
                    $row['DATA_INICIO'],      // dataInicio
                    $row['DATA_FIM'],         // dataFim
                    $row['STATUS_CHECKLIST']  // statusChecklist
                );
            }
            return $checklists;
        } catch (\Exception $e) {
            \Util\Util::inserirErro($e, "filtrarChecklists", $this->idUsuarioSessao);
            return [];
        }
    }

    public function listarChecklists(): array
    {
        // Retorna todos aplicando apenas as permissões do usuário
        return $this->filtrarChecklists([]);
    }
    public function buscarHorimetroPendente(int $fkUsuario): ?array
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST,
                       HORIMETRO_INICIAL, HORIMETRO_FINAL
                FROM {$this->view_checklists_horimetros}
                WHERE FK_USUARIO = ?
                  AND DATA_FIM IS NOT NULL
                  AND (HORIMETRO_FINAL IS NULL OR HORIMETRO_FINAL = '')
                ORDER BY ID_CHECKLIST DESC
                LIMIT 1
            ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows ? $result->fetch_assoc() : null;
        } catch (Exception $e) {
            Util::inserirErro($e, "buscarHorimetroPendente", $this->idUsuarioSessao);
            return null;
        }
    }
}
