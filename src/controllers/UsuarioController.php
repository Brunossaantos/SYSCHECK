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

class UsuarioController{
    private $rnUsuario;

    function __construct(RnUsuario $rnUsuario){
        $this->rnUsuario = $rnUsuario;
    }

    function index(){
        require_once __DIR__ .'/../views/features/usuarios/index.php';
    }

    function cadastrarUsuario(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario = new Usuario(1, $_POST['nome'], $_POST['departamento'], $_POST['cargo'], $_POST['nomeusuario'], "", $_POST['statususuario'], $_POST['checklistveicular']);
            
            $idUsuario = $this->rnUsuario->cadastrarNovoUsuario($usuario);

            if($idUsuario > 0){
                echo "Usuário cadastrado com sucesso";
            } else {
                echo "Não foi possível cadastrar o usuário no banco de dados.";
            }

        } else {
             
            $listaDepartamentos = (new RnDepartamento(Sessao::idusuario()))->listarDepartamentos();   
            require_once __DIR__ .'/../views/features/usuarios/cadastrousuario.php';
        }     
    }

    function gerenciarUsuarios(){
        $listaUsuario = $this->rnUsuario->listarUsuarios();
        require_once __DIR__ . '/../views/features/usuarios/consultausuario.php';
    }

    function alterarCadastroUsuario($idUsuario){
        $usuario = $this->rnUsuario->selecionarUsuario($idUsuario);
        require_once __DIR__ . '/../views/features/usuarios/alterarcadastrousuario.php';
    }

    function salvaralteracao(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario = new Usuario($_POST['idusuario'], $_POST['nome'], $_POST['departamento'], $_POST['cargo'], $_POST['nomeusuario'], "", $_POST['statususuario'], $_POST['checklistveicular']);
            $quantLinhas = $this->rnUsuario->alterarUsuario($usuario);

            if($quantLinhas > 0){
                echo "Cadastro alterado com sucesso";
            } else {
                echo "Não foi possível alterar o cadastro";
            }
        }
    }

    function login(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario = $_POST['usuario'];
            $senha = $_POST['senha'];

            //recuperar o nome de usuário e senha
            $login = new Login($usuario, $senha);

            //validar no banco
            $rnLogin = new RnLogin(1);

            //verificação se é o primeiro acesso
            //caso seja o primeiro acesso, envie para página cadastro de senha
            if($rnLogin->verficarPrimerioAcesso($login)){
                $usuario = (new RnLogin(1))->selecionarUsuarioPeloNomeUsuario($login);   

                require_once __DIR__ . '/../../cadastrar_senha.php';
            } else {
                
                if($rnLogin->realizarLogin($login)){
                    Sessao::iniciarSessao((new RnLogin(1))->selecionarUsuarioPeloNomeUsuario($login));

                    $pendencia = (new RnChecklist(Sessao::idusuario()))->verificarPendencia(Sessao::idusuario());                    

                    $existeChecklist = false;
                    $checklistPendente = (new RnChecklist(Sessao::idusuario()))->verificarChecklistPendente(Sessao::idusuario());                    

                    //verificação para fechamento do horimetro
                    $horimetroPendente = $this->verificarSeExisteHorimetroPendente(Sessao::idusuario());

                    //var_dump($horimetroPendente);

                    if(!empty($horimetroPendente)){
                        $checklist = (new RnChecklist(Sessao::idusuario()))->selecionarChecklist($horimetroPendente['idChecklist']);
                        $empilhadeira = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($horimetroPendente['empilhadeira']);
                    }

                    if($checklistPendente){
                        $existeChecklist = true;
                        $objeto = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklistPendente->getFkObjeto());                        
                    }

                    require_once __DIR__ . '/../../index2.php';

                } else {
                    Sessao::salvarMensagemNaSessao("Usuário ou senha inválidos.");
                    header("Location: /syscheck");
                    exit;
                }      
            } 

        } else {
            echo "Método de envio de dados incorreto";
        }
    }

    public function verificarSeExisteHorimetroPendente($idUsuario){
        $rnChecklist = new RnChecklist($idUsuario);
        $listaChecklists = $rnChecklist->recuperarHorimetrosPorChecklist($idUsuario);

        if(!empty($listaChecklists)){
            $tamanhoDoArray = sizeof($listaChecklists);
            if(is_null($listaChecklists[$tamanhoDoArray -1]['horimetroFinal'])){
                return $listaChecklists[$tamanhoDoArray -1];
            }
        }

        return [];
    }

    function cadastrarSenha(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            if($_POST['senha'] == $_POST['conf_senha']){

                //$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $senha = $_POST['senha'];
                $usuario = new Usuario($_POST['idUsuario'], null, null, null, null, $senha, null, null);
                
                $cadSenha = (new RnUsuario(1))->cadastrarSenha($usuario);
                
                if($cadSenha > 0){
                    Sessao::salvarMensagemNaSessao("Senha cadatrada com sucesso");
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

    function excluirUsuario($idUsuario){        
        $rnUsuario = new RnUsuario(Sessao::idusuario());
        $usuario = $rnUsuario->selecionarUsuario($idUsuario);
        //echo "<pre>";
        //var_dump($usuario);

        $usuario->setStatusUsuario(0);
        if($rnUsuario->alterarUsuario($usuario) > 0){
            echo "Usuario alterado com sucesso";
        } else {
            echo "Não foi possível alterar o cadastro do usuário";
        }
    }

    function logout(){
        session_start();
        session_destroy();
        header('Location: /syscheck');
        exit();
    }
}

?>