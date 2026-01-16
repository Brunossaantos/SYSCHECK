-- Script banco de dados SYSCHECK

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `syscheck`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_baterias`
--

CREATE TABLE `tbl_baterias` (
  `ID_BATERIA` int(11) NOT NULL,
  `NUMERO_BATERIA` int(11) NOT NULL,
  `DESCRICAO_BATERIA` varchar(50) DEFAULT NULL,
  `MEDIDAS` varchar(50) DEFAULT NULL,
  `OBSERVACAO` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estrutura para tabela `tbl_carga_bateria_comum`
--

CREATE TABLE `tbl_carga_bateria_comum` (
  `ID_CARGA_BATERIA` int(11) NOT NULL,
  `FK_CHECKLIST` int(11) NOT NULL,
  `FK_EMPILHADEIRA` int(11) NOT NULL,
  `NIVEL_BATERIA` int(11) NOT NULL,
  `DATA_HORA` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_chamados`
--

CREATE TABLE `tbl_chamados` (
  `ID_CHAMADO` int(11) NOT NULL,
  `FK_ITEM_CHAMADO` int(11) NOT NULL,
  `DESCRICAO_CHAMADO` text DEFAULT NULL,
  `DATA_ABERTURA_CHAMADO` varchar(30) DEFAULT NULL,
  `DATA_FINALIZACAO_CHAMADO` varchar(30) NOT NULL,
  `FK_USUARIO` int(11) NOT NULL,
  `STATUS_CHAMADO` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_checklists`
--

CREATE TABLE `tbl_checklists` (
  `ID_CHECKLIST` int(11) NOT NULL,
  `FK_USUARIO` int(11) NOT NULL,
  `FK_TIPO` int(11) NOT NULL,
  `FK_OBJETO` int(11) NOT NULL,
  `DATA_INICIO` varchar(30) DEFAULT NULL,
  `DATA_FIM` varchar(30) DEFAULT NULL,
  `STATUS_CHECKLIST` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_departamentos`
--

CREATE TABLE `tbl_departamentos` (
  `ID_DEPARTAMENTO` int(11) NOT NULL,
  `DESCRICAO_DEPARTAMENTO` varchar(30) NOT NULL,
  `STATUS_DEPARTAMENTO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_empilhadeira_bateria`
--

CREATE TABLE `tbl_empilhadeira_bateria` (
  `ID_EMPILHADEIRA_BATERIA` int(11) NOT NULL,
  `FK_CHECKLIST` int(11) NOT NULL,
  `FK_EMPILHADEIRA` int(11) NOT NULL,
  `FK_BATERIA` int(11) NOT NULL,
  `NIVEL_BATERIA` int(11) NOT NULL,
  `DATA_HORA` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_equipamentos_local`
--

CREATE TABLE `tbl_equipamentos_local` (
  `ID_EQUIPAMENTO_LOCAL` int(11) NOT NULL,
  `FK_LOCAL` int(11) NOT NULL,
  `FK_EQUIPAMENTO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_erros`
--

CREATE TABLE `tbl_erros` (
  `ID_ERRO` int(11) NOT NULL,
  `ERRO` text NOT NULL,
  `ARQUIVO` text NOT NULL,
  `LINHA` text NOT NULL,
  `LOCAL` text NOT NULL,
  `DATA_HORA` varchar(30) NOT NULL,
  `FK_USUARIO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_etapas_checklists`
--

CREATE TABLE `tbl_etapas_checklists` (
  `ID_ETAPA_CHECKLIST` int(11) NOT NULL,
  `FK_TIPO_CHECKLIST` int(11) NOT NULL,
  `TITULO_ETAPA` varchar(80) NOT NULL,
  `CONTEUDO_ETAPA` text NOT NULL,
  `NUMERO_ETAPA` int(11) NOT NULL,
  `FOTO_OBRIGATORIA` int(11) NOT NULL,
  `CAMPO_ADICIONAL` int(11) NOT NULL,
  `STATUS_ETAPA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_etapas_realizadas`
--

CREATE TABLE `tbl_etapas_realizadas` (
  `ID_ETAPA_REALIZADA` int(11) NOT NULL,
  `FK_CHECKLIST` int(11) NOT NULL,
  `FK_ETAPA` int(11) NOT NULL,
  `NUMERO_ETAPA` int(11) NOT NULL,
  `ACAO` int(11) NOT NULL,
  `OBSERVACAO` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_fotos`
--

CREATE TABLE `tbl_fotos` (
  `ID_FOTO` int(11) NOT NULL,
  `FK_CHECKLIST` int(11) NOT NULL,
  `NUMERO_ETAPA` int(11) NOT NULL,
  `CAMINHO_IMAGEM` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_horimetro`
--

CREATE TABLE `tbl_horimetro` (
  `ID_HORIMETRO` int(11) NOT NULL,
  `FK_CHECKLIST` int(11) NOT NULL,
  `FK_EQUIPAMENTO` int(11) NOT NULL,
  `HORIMETRO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_lista_uso_veiculo`
--

CREATE TABLE `tbl_lista_uso_veiculo` (
  `ID_USO_VEICULO` int(11) NOT NULL,
  `FK_USUARIO` int(11) NOT NULL,
  `FK_VEICULO` int(11) NOT NULL,
  `DATA_HORA` varchar(30) DEFAULT NULL,
  `DATA_HORA_DEVOLUCAO` varchar(30) NOT NULL,
  `STATUS_USO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_locais`
--

CREATE TABLE `tbl_locais` (
  `ID_LOCAL` int(11) NOT NULL,
  `DESCRICAO_LOCAL` varchar(50) NOT NULL,
  `STATUS_LOCAL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_log_finalizacao_horimetro`
--

CREATE TABLE `tbl_log_finalizacao_horimetro` (
  `ID_FINALIZACAO` int(11) NOT NULL,
  `FK_CHECKLIST` int(11) NOT NULL,
  `FK_EMPILHADEIRA` int(11) NOT NULL,
  `FK_LIDER` int(11) NOT NULL,
  `DATA_FINALIZACAO_HORIMETRO` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_objetos`
--

CREATE TABLE `tbl_objetos` (
  `ID_OBJETO` int(11) NOT NULL,
  `DESCRICAO_OBJETO` varchar(50) NOT NULL,
  `FK_TIPO_CHECKLIST` int(11) NOT NULL,
  `STATUS_OBJETO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_perifericos_baterias`
--

CREATE TABLE `tbl_perifericos_baterias` (
  `ID_PERIFERICO` int(11) NOT NULL,
  `TIPO_PERIFERICO` int(11) NOT NULL,
  `DESCRICAO_PERIFERICO` varchar(30) DEFAULT NULL,
  `STATUS_PERIFERICO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_responsaveis`
--

CREATE TABLE `tbl_responsaveis` (
  `ID_RESPONSAVEL` int(11) NOT NULL,
  `NOME_RESPONSAVEL` varchar(50) NOT NULL,
  `EMAIL_RESPONSAVEL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_tipos_checklist`
--

CREATE TABLE `tbl_tipos_checklist` (
  `ID_TIPO_CHECKLIST` int(11) NOT NULL,
  `DESCRICAO_TIPO_CHECKLIST` varchar(40) NOT NULL,
  `FK_RESPONSAVEL` int(11) NOT NULL,
  `STATUS_TIPO_CHECKLIST` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_tipos_empilhadeiras`
--

CREATE TABLE `tbl_tipos_empilhadeiras` (
  `ID_TIPO_EMPILHADEIRA` int(11) NOT NULL,
  `DESC_TIPO_EMPILHADEIRA` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_tpc_temp`
--

CREATE TABLE `tbl_tpc_temp` (
  `ID_TCTE` int(11) NOT NULL,
  `FK_TCKL` int(11) NOT NULL,
  `FK_TEMP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `ID_USUARIO` int(11) NOT NULL,
  `NOME` varchar(30) DEFAULT NULL,
  `DEPARTAMENTO` int(11) DEFAULT NULL,
  `CARGO` int(11) DEFAULT NULL,
  `NOME_USUARIO` varchar(30) DEFAULT NULL,
  `SENHA` text DEFAULT NULL,
  `STATUS_USUARIO` int(11) DEFAULT NULL,
  `TIPO_CHECKLIST` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_usuario_empilhadeira`
--

CREATE TABLE `tbl_usuario_empilhadeira` (
  `ID_USUARIO_EMPILHADEIRA` int(11) NOT NULL,
  `FK_CHECKLIST` int(11) NOT NULL,
  `FK_USUARIO` int(11) NOT NULL,
  `FK_EMPILHADEIRA` int(11) NOT NULL,
  `DATA_HORA_INICIO` varchar(30) DEFAULT NULL,
  `DATA_HORA_ENCERRAMENTO` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_checklist_conteudo_etapas`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_checklist_conteudo_etapas` (
`ID_ETAPA_REALIZADA` int(11)
,`FK_CHECKLIST` int(11)
,`CONTEUDO_ETAPA` text
,`NUMERO_ETAPA` int(11)
,`ACAO` int(11)
,`OBSERVACAO` text
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_checklist_horimetro`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_checklist_horimetro` (
`ID_CHECKLIST` int(11)
,`FK_USUARIO` int(11)
,`FK_TIPO` int(11)
,`FK_OBJETO` int(11)
,`DATA_INICIO` varchar(30)
,`DATA_FIM` varchar(30)
,`STATUS_CHECKLIST` int(11)
,`HORIMETRO_INICIAL` int(11)
,`HORIMETRO_FINAL` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_checklist_visao_geral`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_checklist_visao_geral` (
`NUMERO_CHECKLIST` int(11)
,`USUARIO` varchar(30)
,`TIPO` varchar(40)
,`OBJETO` varchar(50)
,`DATA_INICIO` varchar(30)
,`DATA_FIM` varchar(30)
,`STATUS_CHECKLIST` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_quantidade_etapas_checklist`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_quantidade_etapas_checklist` (
`FK_TIPO_CHECKLIST` int(11)
,`QUANTIDADE_ETAPAS` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura para view `v_checklist_conteudo_etapas`
--
DROP TABLE IF EXISTS `v_checklist_conteudo_etapas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_checklist_conteudo_etapas`  AS SELECT `rel`.`ID_ETAPA_REALIZADA` AS `ID_ETAPA_REALIZADA`, `rel`.`FK_CHECKLIST` AS `FK_CHECKLIST`, `etp`.`CONTEUDO_ETAPA` AS `CONTEUDO_ETAPA`, `rel`.`NUMERO_ETAPA` AS `NUMERO_ETAPA`, `rel`.`ACAO` AS `ACAO`, `rel`.`OBSERVACAO` AS `OBSERVACAO` FROM (`tbl_etapas_realizadas` `rel` join `tbl_etapas_checklists` `etp` on(`rel`.`FK_ETAPA` = `etp`.`ID_ETAPA_CHECKLIST`)) ORDER BY `rel`.`FK_CHECKLIST` ASC, `rel`.`NUMERO_ETAPA` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_checklist_horimetro`
--
DROP TABLE IF EXISTS `v_checklist_horimetro`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_checklist_horimetro`  AS SELECT `c`.`ID_CHECKLIST` AS `ID_CHECKLIST`, `c`.`FK_USUARIO` AS `FK_USUARIO`, `c`.`FK_TIPO` AS `FK_TIPO`, `c`.`FK_OBJETO` AS `FK_OBJETO`, `c`.`DATA_INICIO` AS `DATA_INICIO`, `c`.`DATA_FIM` AS `DATA_FIM`, `c`.`STATUS_CHECKLIST` AS `STATUS_CHECKLIST`, `h`.`HORIMETRO_INICIAL` AS `HORIMETRO_INICIAL`, `h`.`HORIMETRO_FINAL` AS `HORIMETRO_FINAL` FROM (`tbl_checklists` `c` left join (select `tbl_horimetro`.`FK_CHECKLIST` AS `FK_CHECKLIST`,min(`tbl_horimetro`.`HORIMETRO`) AS `HORIMETRO_INICIAL`,case when count(`tbl_horimetro`.`ID_HORIMETRO`) > 1 then max(`tbl_horimetro`.`HORIMETRO`) else NULL end AS `HORIMETRO_FINAL` from `tbl_horimetro` group by `tbl_horimetro`.`FK_CHECKLIST`) `h` on(`c`.`ID_CHECKLIST` = `h`.`FK_CHECKLIST`)) WHERE `c`.`FK_TIPO` in (3,4,14) ORDER BY `c`.`ID_CHECKLIST` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_checklist_visao_geral`
--
DROP TABLE IF EXISTS `v_checklist_visao_geral`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_checklist_visao_geral`  AS SELECT `c`.`ID_CHECKLIST` AS `NUMERO_CHECKLIST`, `u`.`NOME` AS `USUARIO`, `t`.`DESCRICAO_TIPO_CHECKLIST` AS `TIPO`, `o`.`DESCRICAO_OBJETO` AS `OBJETO`, `c`.`DATA_INICIO` AS `DATA_INICIO`, `c`.`DATA_FIM` AS `DATA_FIM`, `c`.`STATUS_CHECKLIST` AS `STATUS_CHECKLIST` FROM (((`tbl_checklists` `c` join `tbl_usuarios` `u` on(`c`.`FK_USUARIO` = `u`.`ID_USUARIO`)) join `tbl_tipos_checklist` `t` on(`c`.`FK_TIPO` = `t`.`ID_TIPO_CHECKLIST`)) join `tbl_objetos` `o` on(`c`.`FK_OBJETO` = `o`.`ID_OBJETO`)) ORDER BY `c`.`ID_CHECKLIST` DESC ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_quantidade_etapas_checklist`
--
DROP TABLE IF EXISTS `v_quantidade_etapas_checklist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_quantidade_etapas_checklist`  AS SELECT `tbl_etapas_checklists`.`FK_TIPO_CHECKLIST` AS `FK_TIPO_CHECKLIST`, count(0) AS `QUANTIDADE_ETAPAS` FROM `tbl_etapas_checklists` GROUP BY `tbl_etapas_checklists`.`FK_TIPO_CHECKLIST` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_baterias`
--
ALTER TABLE `tbl_baterias`
  ADD PRIMARY KEY (`ID_BATERIA`),
  ADD UNIQUE KEY `NUMERO_BATERIA` (`NUMERO_BATERIA`);

--
-- Índices de tabela `tbl_carga_bateria_comum`
--
ALTER TABLE `tbl_carga_bateria_comum`
  ADD PRIMARY KEY (`ID_CARGA_BATERIA`);

--
-- Índices de tabela `tbl_chamados`
--
ALTER TABLE `tbl_chamados`
  ADD PRIMARY KEY (`ID_CHAMADO`),
  ADD KEY `FK_CHAMADO_ITEM` (`FK_ITEM_CHAMADO`),
  ADD KEY `FK_CHAMADO_USUARIO` (`FK_USUARIO`);

--
-- Índices de tabela `tbl_checklists`
--
ALTER TABLE `tbl_checklists`
  ADD PRIMARY KEY (`ID_CHECKLIST`),
  ADD KEY `FK_CHECKLIST_USUARIO` (`FK_USUARIO`),
  ADD KEY `FK_CHECKLIST_TIPO` (`FK_TIPO`),
  ADD KEY `FK_CHECKLIST` (`FK_OBJETO`);

--
-- Índices de tabela `tbl_departamentos`
--
ALTER TABLE `tbl_departamentos`
  ADD PRIMARY KEY (`ID_DEPARTAMENTO`);

--
-- Índices de tabela `tbl_empilhadeira_bateria`
--
ALTER TABLE `tbl_empilhadeira_bateria`
  ADD PRIMARY KEY (`ID_EMPILHADEIRA_BATERIA`),
  ADD KEY `FK_CHECKLIST_EMPILHADEIRA` (`FK_CHECKLIST`),
  ADD KEY `FK_EMPILHADEIRA` (`FK_EMPILHADEIRA`),
  ADD KEY `FK_BATERIA` (`FK_BATERIA`);

--
-- Índices de tabela `tbl_equipamentos_local`
--
ALTER TABLE `tbl_equipamentos_local`
  ADD PRIMARY KEY (`ID_EQUIPAMENTO_LOCAL`),
  ADD KEY `FK_EQUIPAMENTO_LOCAL` (`FK_EQUIPAMENTO`),
  ADD KEY `FK_LOCAL_EQUIPAMENTO` (`FK_LOCAL`);

--
-- Índices de tabela `tbl_erros`
--
ALTER TABLE `tbl_erros`
  ADD PRIMARY KEY (`ID_ERRO`),
  ADD KEY `FK_ERROS_USUARIO` (`FK_USUARIO`);

--
-- Índices de tabela `tbl_etapas_checklists`
--
ALTER TABLE `tbl_etapas_checklists`
  ADD PRIMARY KEY (`ID_ETAPA_CHECKLIST`),
  ADD KEY `FK_TIPO_CHECKLIST_ETAPAS` (`FK_TIPO_CHECKLIST`);

--
-- Índices de tabela `tbl_etapas_realizadas`
--
ALTER TABLE `tbl_etapas_realizadas`
  ADD PRIMARY KEY (`ID_ETAPA_REALIZADA`),
  ADD KEY `FK_ETAPA_REALIZADA_ETAPA` (`FK_ETAPA`),
  ADD KEY `FK_ETAPAS_REALIZADAS_CHECKLIST` (`FK_CHECKLIST`);

--
-- Índices de tabela `tbl_follow_up_chamados`
--
ALTER TABLE `tbl_follow_up_chamados`
  ADD PRIMARY KEY (`ID_FOLLOW_UP`),
  ADD KEY `FK_CHAMADO_FOLLOW_UP` (`FK_CHAMADO`),
  ADD KEY `FK_USUARIO_FOLLOW_UP` (`FK_USUARIO`);

--
-- Índices de tabela `tbl_fotos`
--
ALTER TABLE `tbl_fotos`
  ADD PRIMARY KEY (`ID_FOTO`),
  ADD KEY `FK_CHECKLIST_FOTO` (`FK_CHECKLIST`);

--
-- Índices de tabela `tbl_fotos_chamados`
--
ALTER TABLE `tbl_fotos_chamados`
  ADD PRIMARY KEY (`ID_FOTO_CHAMADO`);

--
-- Índices de tabela `tbl_horimetro`
--
ALTER TABLE `tbl_horimetro`
  ADD PRIMARY KEY (`ID_HORIMETRO`);

--
-- Índices de tabela `tbl_lista_uso_veiculo`
--
ALTER TABLE `tbl_lista_uso_veiculo`
  ADD PRIMARY KEY (`ID_USO_VEICULO`),
  ADD KEY `FK_USUARIO_VEICULO` (`FK_USUARIO`),
  ADD KEY `FK_VEICULO_OBJETO` (`FK_VEICULO`);

--
-- Índices de tabela `tbl_locais`
--
ALTER TABLE `tbl_locais`
  ADD PRIMARY KEY (`ID_LOCAL`);

--
-- Índices de tabela `tbl_log_finalizacao_horimetro`
--
ALTER TABLE `tbl_log_finalizacao_horimetro`
  ADD PRIMARY KEY (`ID_FINALIZACAO`),
  ADD KEY `FK_LOG_CHECKLIST` (`FK_CHECKLIST`),
  ADD KEY `FK_LOG_EMPILHADEIRA` (`FK_EMPILHADEIRA`),
  ADD KEY `FK_LIDER_LOG` (`FK_LIDER`);

--
-- Índices de tabela `tbl_objetos`
--
ALTER TABLE `tbl_objetos`
  ADD PRIMARY KEY (`ID_OBJETO`),
  ADD KEY `FK_TIPO_OBJETO` (`FK_TIPO_CHECKLIST`);

--
-- Índices de tabela `tbl_perifericos_baterias`
--
ALTER TABLE `tbl_perifericos_baterias`
  ADD PRIMARY KEY (`ID_PERIFERICO`);

--
-- Índices de tabela `tbl_responsaveis`
--
ALTER TABLE `tbl_responsaveis`
  ADD PRIMARY KEY (`ID_RESPONSAVEL`);

--
-- Índices de tabela `tbl_tipos_checklist`
--
ALTER TABLE `tbl_tipos_checklist`
  ADD PRIMARY KEY (`ID_TIPO_CHECKLIST`),
  ADD KEY `FK_RESPONSAVEL_TIPO` (`FK_RESPONSAVEL`);

--
-- Índices de tabela `tbl_tipos_empilhadeiras`
--
ALTER TABLE `tbl_tipos_empilhadeiras`
  ADD PRIMARY KEY (`ID_TIPO_EMPILHADEIRA`);

--
-- Índices de tabela `tbl_tpc_temp`
--
ALTER TABLE `tbl_tpc_temp`
  ADD PRIMARY KEY (`ID_TCTE`),
  ADD KEY `FK_TIPO_CHECKLIST_TIPO_EMPILHADEIRA` (`FK_TCKL`),
  ADD KEY `FK_TIPO_EMPILHADEIRA` (`FK_TEMP`);

--
-- Índices de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`ID_USUARIO`);

--
-- Índices de tabela `tbl_usuario_empilhadeira`
--
ALTER TABLE `tbl_usuario_empilhadeira`
  ADD PRIMARY KEY (`ID_USUARIO_EMPILHADEIRA`),
  ADD KEY `FK_USUARIO_EMPILHADEIRA_USO` (`FK_USUARIO`),
  ADD KEY `FK_EMPILHADEIRA_USUARIO_USO` (`FK_EMPILHADEIRA`),
  ADD KEY `FK_CHECKLIST_USUARIO_USO` (`FK_CHECKLIST`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_baterias`
--
ALTER TABLE `tbl_baterias`
  MODIFY `ID_BATERIA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `tbl_carga_bateria_comum`
--
ALTER TABLE `tbl_carga_bateria_comum`
  MODIFY `ID_CARGA_BATERIA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1561;

--
-- AUTO_INCREMENT de tabela `tbl_chamados`
--
ALTER TABLE `tbl_chamados`
  MODIFY `ID_CHAMADO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de tabela `tbl_checklists`
--
ALTER TABLE `tbl_checklists`
  MODIFY `ID_CHECKLIST` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4187;

--
-- AUTO_INCREMENT de tabela `tbl_departamentos`
--
ALTER TABLE `tbl_departamentos`
  MODIFY `ID_DEPARTAMENTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tbl_empilhadeira_bateria`
--
ALTER TABLE `tbl_empilhadeira_bateria`
  MODIFY `ID_EMPILHADEIRA_BATERIA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT de tabela `tbl_equipamentos_local`
--
ALTER TABLE `tbl_equipamentos_local`
  MODIFY `ID_EQUIPAMENTO_LOCAL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbl_erros`
--
ALTER TABLE `tbl_erros`
  MODIFY `ID_ERRO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT de tabela `tbl_etapas_checklists`
--
ALTER TABLE `tbl_etapas_checklists`
  MODIFY `ID_ETAPA_CHECKLIST` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT de tabela `tbl_etapas_realizadas`
--
ALTER TABLE `tbl_etapas_realizadas`
  MODIFY `ID_ETAPA_REALIZADA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89420;

--
-- AUTO_INCREMENT de tabela `tbl_follow_up_chamados`
--
ALTER TABLE `tbl_follow_up_chamados`
  MODIFY `ID_FOLLOW_UP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT de tabela `tbl_fotos`
--
ALTER TABLE `tbl_fotos`
  MODIFY `ID_FOTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3030;

--
-- AUTO_INCREMENT de tabela `tbl_fotos_chamados`
--
ALTER TABLE `tbl_fotos_chamados`
  MODIFY `ID_FOTO_CHAMADO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de tabela `tbl_horimetro`
--
ALTER TABLE `tbl_horimetro`
  MODIFY `ID_HORIMETRO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6803;

--
-- AUTO_INCREMENT de tabela `tbl_lista_uso_veiculo`
--
ALTER TABLE `tbl_lista_uso_veiculo`
  MODIFY `ID_USO_VEICULO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de tabela `tbl_locais`
--
ALTER TABLE `tbl_locais`
  MODIFY `ID_LOCAL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tbl_log_finalizacao_horimetro`
--
ALTER TABLE `tbl_log_finalizacao_horimetro`
  MODIFY `ID_FINALIZACAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `tbl_objetos`
--
ALTER TABLE `tbl_objetos`
  MODIFY `ID_OBJETO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT de tabela `tbl_perifericos_baterias`
--
ALTER TABLE `tbl_perifericos_baterias`
  MODIFY `ID_PERIFERICO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_responsaveis`
--
ALTER TABLE `tbl_responsaveis`
  MODIFY `ID_RESPONSAVEL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbl_tipos_checklist`
--
ALTER TABLE `tbl_tipos_checklist`
  MODIFY `ID_TIPO_CHECKLIST` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `tbl_tipos_empilhadeiras`
--
ALTER TABLE `tbl_tipos_empilhadeiras`
  MODIFY `ID_TIPO_EMPILHADEIRA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tbl_tpc_temp`
--
ALTER TABLE `tbl_tpc_temp`
  MODIFY `ID_TCTE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de tabela `tbl_usuario_empilhadeira`
--
ALTER TABLE `tbl_usuario_empilhadeira`
  MODIFY `ID_USUARIO_EMPILHADEIRA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbl_chamados`
--
ALTER TABLE `tbl_chamados`
  ADD CONSTRAINT `FK_CHAMADO_ITEM` FOREIGN KEY (`FK_ITEM_CHAMADO`) REFERENCES `tbl_objetos` (`ID_OBJETO`),
  ADD CONSTRAINT `FK_CHAMADO_USUARIO` FOREIGN KEY (`FK_USUARIO`) REFERENCES `tbl_usuarios` (`ID_USUARIO`);

--
-- Restrições para tabelas `tbl_checklists`
--
ALTER TABLE `tbl_checklists`
  ADD CONSTRAINT `FK_CHECKLIST` FOREIGN KEY (`FK_OBJETO`) REFERENCES `tbl_objetos` (`ID_OBJETO`),
  ADD CONSTRAINT `FK_CHECKLIST_TIPO` FOREIGN KEY (`FK_TIPO`) REFERENCES `tbl_tipos_checklist` (`ID_TIPO_CHECKLIST`),
  ADD CONSTRAINT `FK_CHECKLIST_USUARIO` FOREIGN KEY (`FK_USUARIO`) REFERENCES `tbl_usuarios` (`ID_USUARIO`);

--
-- Restrições para tabelas `tbl_empilhadeira_bateria`
--
ALTER TABLE `tbl_empilhadeira_bateria`
  ADD CONSTRAINT `FK_BATERIA` FOREIGN KEY (`FK_BATERIA`) REFERENCES `tbl_baterias` (`ID_BATERIA`),
  ADD CONSTRAINT `FK_CHECKLIST_EMPILHADEIRA` FOREIGN KEY (`FK_CHECKLIST`) REFERENCES `tbl_checklists` (`ID_CHECKLIST`),
  ADD CONSTRAINT `FK_EMPILHADEIRA` FOREIGN KEY (`FK_EMPILHADEIRA`) REFERENCES `tbl_objetos` (`ID_OBJETO`);

--
-- Restrições para tabelas `tbl_equipamentos_local`
--
ALTER TABLE `tbl_equipamentos_local`
  ADD CONSTRAINT `FK_EQUIPAMENTO_LOCAL` FOREIGN KEY (`FK_EQUIPAMENTO`) REFERENCES `tbl_objetos` (`ID_OBJETO`),
  ADD CONSTRAINT `FK_LOCAL_EQUIPAMENTO` FOREIGN KEY (`FK_LOCAL`) REFERENCES `tbl_locais` (`ID_LOCAL`);

--
-- Restrições para tabelas `tbl_erros`
--
ALTER TABLE `tbl_erros`
  ADD CONSTRAINT `FK_ERROS_USUARIO` FOREIGN KEY (`FK_USUARIO`) REFERENCES `tbl_usuarios` (`ID_USUARIO`);

--
-- Restrições para tabelas `tbl_etapas_checklists`
--
ALTER TABLE `tbl_etapas_checklists`
  ADD CONSTRAINT `FK_TIPO_CHECKLIST_ETAPAS` FOREIGN KEY (`FK_TIPO_CHECKLIST`) REFERENCES `tbl_tipos_checklist` (`ID_TIPO_CHECKLIST`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tbl_etapas_realizadas`
--
ALTER TABLE `tbl_etapas_realizadas`
  ADD CONSTRAINT `FK_ETAPAS_REALIZADAS_CHECKLIST` FOREIGN KEY (`FK_CHECKLIST`) REFERENCES `tbl_checklists` (`ID_CHECKLIST`);

--
-- Restrições para tabelas `tbl_follow_up_chamados`
--
ALTER TABLE `tbl_follow_up_chamados`
  ADD CONSTRAINT `FK_CHAMADO_FOLLOW_UP` FOREIGN KEY (`FK_CHAMADO`) REFERENCES `tbl_chamados` (`ID_CHAMADO`),
  ADD CONSTRAINT `FK_USUARIO_FOLLOW_UP` FOREIGN KEY (`FK_USUARIO`) REFERENCES `tbl_usuarios` (`ID_USUARIO`);

--
-- Restrições para tabelas `tbl_fotos`
--
ALTER TABLE `tbl_fotos`
  ADD CONSTRAINT `FK_CHECKLIST_FOTO` FOREIGN KEY (`FK_CHECKLIST`) REFERENCES `tbl_checklists` (`ID_CHECKLIST`);

--
-- Restrições para tabelas `tbl_lista_uso_veiculo`
--
ALTER TABLE `tbl_lista_uso_veiculo`
  ADD CONSTRAINT `FK_USUARIO_VEICULO` FOREIGN KEY (`FK_USUARIO`) REFERENCES `tbl_usuarios` (`ID_USUARIO`),
  ADD CONSTRAINT `FK_VEICULO_OBJETO` FOREIGN KEY (`FK_VEICULO`) REFERENCES `tbl_objetos` (`ID_OBJETO`);

--
-- Restrições para tabelas `tbl_log_finalizacao_horimetro`
--
ALTER TABLE `tbl_log_finalizacao_horimetro`
  ADD CONSTRAINT `FK_LIDER_LOG` FOREIGN KEY (`FK_LIDER`) REFERENCES `tbl_usuarios` (`ID_USUARIO`),
  ADD CONSTRAINT `FK_LOG_CHECKLIST` FOREIGN KEY (`FK_CHECKLIST`) REFERENCES `tbl_checklists` (`ID_CHECKLIST`),
  ADD CONSTRAINT `FK_LOG_EMPILHADEIRA` FOREIGN KEY (`FK_EMPILHADEIRA`) REFERENCES `tbl_objetos` (`ID_OBJETO`);

--
-- Restrições para tabelas `tbl_objetos`
--
ALTER TABLE `tbl_objetos`
  ADD CONSTRAINT `FK_TIPO_OBJETO` FOREIGN KEY (`FK_TIPO_CHECKLIST`) REFERENCES `tbl_tipos_checklist` (`ID_TIPO_CHECKLIST`);

--
-- Restrições para tabelas `tbl_tipos_checklist`
--
ALTER TABLE `tbl_tipos_checklist`
  ADD CONSTRAINT `FK_RESPONSAVEL_TIPO` FOREIGN KEY (`FK_RESPONSAVEL`) REFERENCES `tbl_responsaveis` (`ID_RESPONSAVEL`);

--
-- Restrições para tabelas `tbl_tpc_temp`
--
ALTER TABLE `tbl_tpc_temp`
  ADD CONSTRAINT `FK_TIPO_CHECKLIST_TIPO_EMPILHADEIRA` FOREIGN KEY (`FK_TCKL`) REFERENCES `tbl_tipos_checklist` (`ID_TIPO_CHECKLIST`),
  ADD CONSTRAINT `FK_TIPO_EMPILHADEIRA` FOREIGN KEY (`FK_TEMP`) REFERENCES `tbl_tipos_empilhadeiras` (`ID_TIPO_EMPILHADEIRA`);

--
-- Restrições para tabelas `tbl_usuario_empilhadeira`
--
ALTER TABLE `tbl_usuario_empilhadeira`
  ADD CONSTRAINT `FK_CHECKLIST_USUARIO_USO` FOREIGN KEY (`FK_CHECKLIST`) REFERENCES `tbl_checklists` (`ID_CHECKLIST`),
  ADD CONSTRAINT `FK_EMPILHADEIRA_USUARIO_USO` FOREIGN KEY (`FK_EMPILHADEIRA`) REFERENCES `tbl_objetos` (`ID_OBJETO`),
  ADD CONSTRAINT `FK_USUARIO_EMPILHADEIRA_USO` FOREIGN KEY (`FK_USUARIO`) REFERENCES `tbl_usuarios` (`ID_USUARIO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
