<?php

namespace service;

use rn\RnObjeto;
use rn\RnUsuarioEmpilhadeira;
use rn\RnChecklist;
use DAO\DaoChecklist;
use Util\Util;

class HomeService
{
    private $conexao;
    private int $idUsuario;
    private int $idPerfil;
    private int $idEmpresa;

    private bool $existeBloqueio = false;
    private array $cards = [];

    public function __construct($conexao, int $idUsuario, int $idPerfil, int $idEmpresa)
    {
        $this->conexao   = $conexao;
        $this->idUsuario = $idUsuario;
        $this->idPerfil  = $idPerfil;
        $this->idEmpresa = $idEmpresa;

        $this->gerarCards();
    }

    public function existeBloqueio(): bool
    {
        return $this->existeBloqueio;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    private function gerarCards(): void
    {
        // RN de usuário empilhadeira
        $rnUsuarioEmpilhadeira = new RnUsuarioEmpilhadeira($this->idUsuario);
        $statusUso = $rnUsuarioEmpilhadeira->verificarChecklistAberto($this->idUsuario);

        // DAOChecklist agora recebe os 3 argumentos obrigatórios
        $daoChecklist = new DaoChecklist(
            $this->conexao,   // conexão mysqli
            $this->idUsuario, // ID do usuário logado
            $this->idPerfil   // Perfil do usuário logado
        );

        $rnChecklist = new RnChecklist(
            $this->idUsuario,
            $this->idEmpresa
        );

        // Checklist pendente e horímetro
        $checklistPendente = $rnChecklist->verificarChecklistPendentes($this->idUsuario);
        $horimetroPendente = $rnChecklist->verificarSeExisteHorimetroPendente();

        $existeChecklist = !empty($checklistPendente);

        // Objeto pendente do checklist
        $objetoPendente = $existeChecklist
            ? (new RnObjeto($this->idUsuario))
            ->selecionarObjeto($checklistPendente->getFkObjeto())
            : null;

        /* ==========================
           ALERTA HORÍMETRO
        ========================== */
        if (!empty($horimetroPendente)) {

            $objetoHorimetro = (new RnObjeto($this->idUsuario))
                ->selecionarObjeto($horimetroPendente['empilhadeira']);

            $descricaoObjeto = $objetoHorimetro
                ? $objetoHorimetro->getDescricaoObjeto()
                : "Empilhadeira";

            $this->cards[] = [
                'titulo' => 'Horímetro pendente',
                'descricao' =>
                "Você iniciou um checklist para <strong>{$descricaoObjeto}</strong> e não informou o horímetro final.<br>É necessário finalizar.",
                'cor' => 'red',
                'links' => [
                    [
                        'texto' => 'Finalizar horímetro',
                        'url' => "/syscheck/checklist/Horimetro/{$horimetroPendente['idChecklist']}",
                        'cor' => 'red'
                    ]
                ]
            ];
        }

        /* ==========================
           ALERTA CHECKLIST
        ========================== */
        if ($existeChecklist) {

            $descricaoObjeto = $objetoPendente
                ? $objetoPendente->getDescricaoObjeto()
                : "Objeto não definido";

            $this->cards[] = [
                'titulo' => 'Checklist pendente',
                'descricao' =>
                "Você possui um checklist pendente para <strong>{$descricaoObjeto}</strong>.<br>Continue de onde parou.",
                'cor' => 'yellow',
                'links' => [
                    [
                        'texto' => 'Continuar checklist',
                        'url' => "/syscheck/etapaschecklist/etapa/{$checklistPendente->getIdChecklist()}/{$checklistPendente->getFkTipo()}/1",
                        'cor' => 'yellow'
                    ]
                ]
            ];
        }

        /* ==========================
           ALERTA UTILIZAÇÃO
        ========================== */
        if (!empty($statusUso)) {

            $empilhadeira = (new RnObjeto($this->idUsuario))
                ->selecionarObjeto($statusUso['FK_EMPILHADEIRA']);

            $dataFormatada = Util::formatarDataHora($statusUso['DATA_INICIO']);

            $this->cards[] = [
                'titulo' => 'Utilização da empilhadeira',
                'descricao' =>
                "Você iniciou a utilização da empilhadeira <strong>{$empilhadeira->getDescricaoObjeto()}</strong> no dia <strong>{$dataFormatada}</strong>.<br>O que deseja fazer?",
                'cor' => 'green',
                'links' => [
                    [
                        'texto' => 'Trocar bateria',
                        'url' => "/syscheck/checklist/iniciarChecklistBateriaLitio/{$statusUso['FK_CHECKLIST']}",
                        'cor' => 'green'
                    ],
                    [
                        'texto' => 'Encerrar uso',
                        'url' => "/syscheck/checklist/encerrarusoempilhadeiraeletrica/{$statusUso['FK_CHECKLIST']}",
                        'cor' => 'red'
                    ]
                ]
            ];
        }

        /* ==========================
           CARDS FIXOS POR PERFIL
        ========================== */
        $linkChecklists = [['texto' => 'Checklists', 'url' => '/syscheck/checklist', 'cor' => 'blue']];
        $linkChamados = [
            ['texto' => 'Abrir', 'url' => '/syscheck/chamado/abrirchamado', 'cor' => 'blue'],
            ['texto' => 'Consultar', 'url' => '/syscheck/chamado/gerenciarChamados', 'cor' => 'blue']
        ];
        $linkRelatorios = [
            ['texto' => 'Aprovados', 'url' => '/syscheck/src/views/features/Relatorios/index.php', 'cor' => 'blue'],
            ['texto' => 'Reprovados', 'url' => '/syscheck/src/views/features/Relatorios/index_reprovados.php', 'cor' => 'blue']
        ];
        $linkUsuarios = [['texto' => 'Gerenciar', 'url' => '/syscheck/usuario', 'cor' => 'blue']];
        $linkListaVeicular = [['texto' => 'Abrir lista', 'url' => '/syscheck/lista', 'cor' => 'blue']];
        $linkLogs = [['texto' => 'Abrir logs', 'url' => '/syscheck/dev/logs.php', 'cor' => 'blue']];

        $cardsPerfis = [
            1 => [
                ['titulo' => 'Usuários', 'descricao' => 'Gerenciamento de usuários', 'links' => $linkUsuarios],
                ['titulo' => 'Checklists', 'descricao' => 'Checklists', 'links' => $linkChecklists],
                ['titulo' => 'Chamados', 'descricao' => 'Verificação de chamados abertos', 'links' => $linkChamados],
                ['titulo' => 'Relatórios', 'descricao' => 'Relatórios de checklists', 'links' => $linkRelatorios]
            ],
            2 => [
                ['titulo' => 'Checklists', 'descricao' => 'Checklists', 'links' => $linkChecklists],
                ['titulo' => 'Chamados', 'descricao' => 'Verificação de chamados abertos', 'links' => $linkChamados]
            ],
            3 => [
                ['titulo' => 'Checklists', 'descricao' => 'Checklists', 'links' => $linkChecklists],
                ['titulo' => 'Chamados', 'descricao' => 'Verificação de chamados abertos', 'links' => $linkChamados]
            ],
            4 => [
                ['titulo' => 'Checklists', 'descricao' => 'Checklists', 'links' => $linkChecklists],
                ['titulo' => 'Chamados', 'descricao' => 'Verificação de chamados abertos', 'links' => $linkChamados]
            ],
            5 => [
                ['titulo' => 'Checklists', 'descricao' => 'Checklists', 'links' => $linkChecklists],
                ['titulo' => 'Chamados', 'descricao' => 'Verificação de chamados abertos', 'links' => $linkChamados],
                ['titulo' => 'Relatórios', 'descricao' => 'Itens de checklists', 'links' => $linkRelatorios]
            ],
            6 => [
                ['titulo' => 'Retirada / Devolução de veículo', 'descricao' => 'Lista veicular', 'links' => $linkListaVeicular]
            ],
            7 => [
                ['titulo' => 'Checklists', 'descricao' => 'Checklists', 'links' => $linkChecklists],
                ['titulo' => 'Chamados', 'descricao' => 'Verificação de chamados abertos', 'links' => $linkChamados],
                ['titulo' => 'Relatórios', 'descricao' => 'Itens de checklists', 'links' => $linkRelatorios],
                ['titulo' => 'Usuários', 'descricao' => 'Gerenciamento de usuários', 'links' => $linkUsuarios],
                ['titulo' => 'Logs', 'descricao' => 'Logs de sistema (somente desenvolvedor)', 'links' => $linkLogs]
            ],
            8 => [
                ['titulo' => 'Usuários', 'descricao' => 'Gerenciamento de usuários', 'links' => $linkUsuarios],
                ['titulo' => 'Checklists', 'descricao' => 'Checklists', 'links' => $linkChecklists],
                ['titulo' => 'Chamados', 'descricao' => 'Verificação de chamados abertos', 'links' => $linkChamados],
                ['titulo' => 'Relatórios', 'descricao' => 'Relatórios de checklists', 'links' => $linkRelatorios]
            ]
        ];

        if (isset($cardsPerfis[$this->idPerfil])) {
            $this->cards = array_merge($this->cards, $cardsPerfis[$this->idPerfil]);
        }
    }
}
