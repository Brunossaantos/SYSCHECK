<?php

namespace controllers;

use rn\RnUsuario;
use models\Usuario;
use models\Login;
use rn\RnLogin;
use Util\Sessao;

require __DIR__ . '/../../vendor/autoload.php';

class UsuarioController
{
    private $rnUsuario;

    public function __construct(RnUsuario $rnUsuario)
    {
        $this->rnUsuario = $rnUsuario;
    }

    public function index()
    {
        require_once __DIR__ . '/../views/features/usuarios/index.php';
    }

    public function cadastrarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $departamento  = 1;
            $cargo         = 2;
            $nomeUsuario   = $_POST['nomeusuario'] ?? '';
            $nome          = $_POST['nome'] ?? '';
            $statusUsuario = $_POST['statususuario'] ?? 1;
            $checklist     = isset($_POST['checklistveicular']) ? 1 : 0;
            $fk_empresa    = $_POST['fk_empresa'] ?? 1;
            $fk_perfil     = $_POST['fk_perfil'] ?? 2;

            $usuario = new Usuario(
                0,
                $nome,
                $departamento,
                $cargo,
                $nomeUsuario,
                "",
                $statusUsuario,
                $checklist,
                $fk_empresa,
                $fk_perfil
            );

            try {
                $idUsuario = $this->rnUsuario->cadastrarNovoUsuario($usuario);
                echo "<script>
                        alert('Usuário cadastrado com sucesso! ID: $idUsuario');
                        window.location.href = '/syscheck/usuario/cadastrarUsuario';
                    </script>";
                exit;
            } catch (\Throwable $e) {
                registrarLog("Erro ao cadastrar usuário: " . $e->getMessage(), "ERROR");
                echo "Erro ao cadastrar usuário.";
            }
        }
        require_once __DIR__ . '/../views/features/usuarios/cadastrousuario.php';
    }

    public function gerenciarUsuarios()
    {
        $listaUsuario = $this->rnUsuario->listarUsuarios();
        require_once __DIR__ . '/../views/features/usuarios/consultausuario.php';
    }

    public function alterarCadastroUsuario($idUsuario)
    {
        $usuario = $this->rnUsuario->selecionarUsuario($idUsuario);
        require_once __DIR__ . '/../views/features/usuarios/alterarcadastrousuario.php';
    }

    public function salvaralteracao()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $usuario = new Usuario(
                    $_POST['idusuario'] ?? null,
                    $_POST['nome'] ?? null,
                    $_POST['departamento'] ?? null,
                    $_POST['cargo'] ?? null,
                    $_POST['nomeusuario'] ?? null,
                    "",
                    $_POST['statususuario'] ?? 1,
                    isset($_POST['checklistveicular']) ? 1 : 0
                );

                $this->rnUsuario->alterarUsuario($usuario);
                Sessao::salvarMensagemNaSessao("Usuário alterado com sucesso.");
                header("Location: /syscheck/usuario/gerenciarUsuarios");
                exit;
            } catch (\Throwable $e) {
                registrarLog("Erro ao alterar usuário: " . $e->getMessage(), "ERROR");
                echo "Erro ao alterar usuário.";
            }
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioStr = $_POST['usuario'] ?? null;
            $senha   = $_POST['senha'] ?? null;
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP desconhecido';

            $login = new Login($usuarioStr, $senha);
            $rnLogin = new RnLogin(1);

            try {
                if ($rnLogin->verficarPrimerioAcesso($login)) {
                    $usuario = $rnLogin->selecionarUsuarioPeloNomeUsuario($login);
                    require_once __DIR__ . '/../../cadastrar_senha.php';
                    exit;
                } else {
                    $usuarioLogado = $rnLogin->realizarLogin($login);

                    if ($usuarioLogado) {
                        // AQUI ESTAVA O ERRO: Substituímos o session_start manual pelo método da classe
                        Sessao::iniciarSessao($usuarioLogado);

                        // Agora o $_SESSION já está disponível sem erro de Notice
                        $_SESSION['LAST_ACTIVITY'] = time();

                        header("Location: /syscheck/index2.php");
                        exit;
                    } else {
                        registrarLog("Falha no login | Usuario: $usuarioStr | IP: $ip", "ERROR");
                        header("Location: /syscheck/login.php?erro=1");
                        exit;
                    }
                }
            } catch (\Throwable $e) {
                registrarLog("Erro no processo de login: " . $e->getMessage(), "ERROR");
                echo "Erro no login.";
            }
        } else {
            echo "Método de envio de dados incorreto";
        }
    }
    public function cadastrarSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $senha = $_POST['senha'] ?? '';
            $confSenha = $_POST['conf_senha'] ?? '';

            if ($senha === $confSenha && !empty($senha)) {
                try {
                    // Instanciando o modelo Usuario com os 10 parâmetros
                    $usuario = new \models\Usuario(
                        $_POST['idUsuario'] ?? null, // 1. ID
                        "",                          // 2. Nome
                        "",                          // 3. Depto
                        "",                          // 4. Cargo
                        "",                          // 5. NomeUsuario
                        $senha,                      // 6. Senha
                        1,                           // 7. Status (Ativo)
                        0,                           // 8. Checklist
                        1,                           // 9. Empresa
                        2                            // 10. Perfil
                    );

                    if ($this->rnUsuario->alterarSenhaUsuario($usuario) > 0) {
                        // MENSAGEM DE SUCESSO: Trava a tela com alert antes de redirecionar
                        echo "<script>
                            alert('Senha cadastrada com sucesso!');
                            window.location.href = '/syscheck';
                          </script>";
                        exit;
                    } else {
                        registrarLog("Erro ao cadastrar senha | UsuarioID: " . ($_POST['idUsuario'] ?? 'desconhecido'), "ERROR");
                        echo "<script>
                            alert('❌ Erro: O banco de dados não pôde salvar a senha.');
                            history.back();
                          </script>";
                        exit;
                    }
                } catch (\Throwable $e) {
                    registrarLog("Erro ao cadastrar senha: " . $e->getMessage(), "ERROR");
                    echo "Erro fatal ao processar cadastro.";
                }
            } else {
                // Caso as senhas não coincidam
                echo "<script>
                    alert('⚠️ As senhas digitadas não são iguais!');
                    history.back();
                  </script>";
                exit;
            }
        }
    }
    public function excluirUsuario($idUsuario)
    {
        try {
            $usuario = $this->rnUsuario->selecionarUsuario($idUsuario);
            $usuario->setStatusUsuario(0);
            $this->rnUsuario->alterarUsuario($usuario);
            Sessao::salvarMensagemNaSessao("Usuário desativado com sucesso.");
            header("Location: /syscheck/usuario/gerenciarUsuarios");
            exit;
        } catch (\Throwable $e) {
            registrarLog("Erro ao desativar usuário: " . $e->getMessage(), "ERROR");
            echo "Erro ao desativar usuário.";
        }
    }

    public function ativarUsuario($idUsuario)
    {
        try {
            $usuario = $this->rnUsuario->selecionarUsuario($idUsuario);
            $usuario->setStatusUsuario(1);
            $this->rnUsuario->alterarUsuario($usuario);
            Sessao::salvarMensagemNaSessao("Usuário ativado com sucesso.");
            header("Location: /syscheck/usuario/gerenciarUsuarios");
            exit;
        } catch (\Throwable $e) {
            registrarLog("Erro ao ativar usuário: " . $e->getMessage(), "ERROR");
            echo "Erro ao ativar usuário.";
        }
    }

    public function logout()
    {
        // USANDO O MÉTODO NOVO QUE CRIAMOS NA CLASSE SESSAO
        Sessao::destruirSessao();
        header('Location: /syscheck/login.php');
        exit();
    }
}
