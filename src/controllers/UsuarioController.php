<?php

namespace controllers;

use rn\RnUsuario;
use models\Usuario;
use models\Login;
use rn\RnLogin;
use rn\RnDepartamento;
use rn\RnChecklist;
use rn\RnObjeto;
use Util\Sessao;

require __DIR__ . '/../../vendor/autoload.php';

class UsuarioController
{
    private $rnUsuario;

    public function __construct(RnUsuario $rnUsuario)
    {
        $this->rnUsuario = $rnUsuario;
    }

    // =========================
    // Página inicial de usuários
    // =========================
    public function index()
    {
        require_once __DIR__ . '/../views/features/usuarios/index.php';
    }

    // =========================
    // Cadastrar novo usuário
    // =========================
    public function cadastrarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ===============================
            // Recebe dados do formulário
            // ===============================
            $departamento  = 1;             // fixo
            $cargo         = 2;             // fixo
            $nomeUsuario   = $_POST['nomeusuario'] ?? '';
            $nome          = $_POST['nome'] ?? '';
            $statusUsuario = $_POST['statususuario'] ?? 1;
            $checklist     = isset($_POST['checklistveicular']) ? 1 : 0;
            $fk_empresa    = $_POST['fk_empresa'] ?? 1; // Matriz padrão
            $fk_perfil     = $_POST['fk_perfil'] ?? 2;  // padrão perfil 2

            // ===============================
            // Cria objeto Usuario
            // ===============================
            $usuario = new \models\Usuario(
                0,
                $nome,
                $departamento,
                $cargo,
                $nomeUsuario,
                "",        // senha vazia
                $statusUsuario,
                $checklist,
                $fk_empresa,
                $fk_perfil
            );

            // ===============================
            // Chama RN para cadastrar
            // ===============================
            $idUsuario = $this->rnUsuario->cadastrarNovoUsuario($usuario);

            // Mensagem de alerta e redirecionamento
            echo "<script>
                    alert('Usuário cadastrado com sucesso! ID: $idUsuario');
                    window.location.href = '/syscheck/usuario/cadastrarUsuario';
                </script>";
            exit;
        }

        // GET → apenas carrega o formulário
        require_once __DIR__ . '/../views/features/usuarios/cadastrousuario.php';
    }

    // =========================
    // Gerenciar usuários
    // =========================
    public function gerenciarUsuarios()
    {
        $listaUsuario = $this->rnUsuario->listarUsuarios();
        require_once __DIR__ . '/../views/features/usuarios/consultausuario.php';
    }

    // =========================
    // Alterar cadastro de usuário
    // =========================
    public function alterarCadastroUsuario($idUsuario)
    {
        $usuario = $this->rnUsuario->selecionarUsuario($idUsuario);
        require_once __DIR__ . '/../views/features/usuarios/alterarcadastrousuario.php';
    }

    // =========================
    // Salvar alterações do usuário
    // =========================
    public function salvaralteracao()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario(
                $_POST['idusuario'] ?? null,
                $_POST['nome'] ?? null,
                $_POST['departamento'] ?? null,
                $_POST['cargo'] ?? null,
                $_POST['nomeusuario'] ?? null,
                "", // senha não alterada aqui
                $_POST['statususuario'] ?? 1,
                isset($_POST['checklistveicular']) ? 1 : 0
            );

            $this->rnUsuario->alterarUsuario($usuario);
            Sessao::salvarMensagemNaSessao("Usuário alterado com sucesso.");
            header("Location: /syscheck/usuario/gerenciarUsuarios");
            exit;
        }
    }

    // =========================
    // Login de usuário
    // =========================
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? null;
            $senha   = $_POST['senha'] ?? null;

            $login = new Login($usuario, $senha);
            $rnLogin = new RnLogin(1);

            if ($rnLogin->verficarPrimerioAcesso($login)) {
                $usuario = $rnLogin->selecionarUsuarioPeloNomeUsuario($login);
                require_once __DIR__ . '/../../cadastrar_senha.php';
                exit;
            } else {
                $usuarioLogado = $rnLogin->realizarLogin($login);

                if ($usuarioLogado) {
                    Sessao::iniciarSessao($usuarioLogado);
                    require_once __DIR__ . '/../../index2.php';
                    exit;
                } else {
                    header("Location: /syscheck/login.php?erro=1");
                    exit;
                }
            }
        } else {
            echo "Método de envio de dados incorreto";
        }
    }

    // =========================
    // Cadastro de senha para primeiro acesso
    // =========================
    public function cadastrarSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (($_POST['senha'] ?? '') === ($_POST['conf_senha'] ?? '')) {

                $usuario = new Usuario(
                    $_POST['idUsuario'] ?? null,
                    "",
                    "",
                    "",
                    "",
                    $_POST['senha'] ?? '',
                    1,
                    0,
                    0,
                    0
                );

                if ($this->rnUsuario->alterarSenhaUsuario($usuario) > 0) {
                    Sessao::salvarMensagemNaSessao("Senha cadastrada com sucesso");
                    header("Location: /syscheck");
                    exit;
                } else {
                    Sessao::salvarMensagemNaSessao("Não foi possível cadastrar a senha do usuário.");
                    header("Location: /syscheck/usuario/login");
                    exit;
                }
            } else {
                Sessao::salvarMensagemNaSessao("Os campos senha e confirmação de senha não são iguais.");
                header("Location: /syscheck/usuario/login");
                exit;
            }
        }
    }

    // =========================
    // Desativar usuário (exclusão lógica)
    // =========================
    public function excluirUsuario($idUsuario)
    {
        $usuario = $this->rnUsuario->selecionarUsuario($idUsuario);
        $usuario->setStatusUsuario(0);
        $this->rnUsuario->alterarUsuario($usuario);
        Sessao::salvarMensagemNaSessao("Usuário desativado com sucesso.");
        header("Location: /syscheck/usuario/gerenciarUsuarios");
        exit;
    }
    public function ativarUsuario($idUsuario)
    {
        $usuario = $this->rnUsuario->selecionarUsuario($idUsuario);
        $usuario->setStatusUsuario(1);
        $this->rnUsuario->alterarUsuario($usuario);

        \Util\Sessao::salvarMensagemNaSessao("Usuário ativado com sucesso.");

        header("Location: /syscheck/usuario/gerenciarUsuarios");
        exit;
    }

    // =========================
    // Logout
    // =========================
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /syscheck');
        exit();
    }
}
