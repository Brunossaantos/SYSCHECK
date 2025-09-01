<?php

namespace Util;

require __DIR__ . '/../../vendor/autoload.php';

use models\Login;
use models\Usuario;


class Sessao{

    public static function iniciarSessao(Usuario $usuario){
        session_start();
        $_SESSION['idUsuario'] = $usuario->getIdUsuario();
        $_SESSION['nome'] = $usuario->getNome();
        $_SESSION['departamento'] = $usuario->getDepartamento();
        $_SESSION['cargo'] = $usuario->getCargo();
        $_SESSION['nomeUsuario'] = $usuario->getNomeUsuario();
        $_SESSION['statusUsuario'] = $usuario->getStatusUsuario();
    }

    public static function verificarSessao(){
        session_start();
        
        if(isset($_SESSION['nomeUsuario'])){
            return true;
        }

        return false;
    }

    public static function retornarUsuarioLogado(){

        if(session_status() == PHP_SESSION_NONE){
            session_start(); 
        }
               
        $usuario = new Usuario($_SESSION['idUsuario'], $_SESSION['nome'], $_SESSION['departamento'], $_SESSION['cargo'], $_SESSION['nomeUsuario'], null, $_SESSION['statusUsuario']);
        return $usuario;        
    }

    public static function idusuario() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Inicia a sessão apenas se não estiver já ativa
        }
    
        if (!isset($_SESSION['idUsuario'])) {
            header("Location: /syscheck"); // Redireciona se o ID do usuário não estiver definido
            exit(); // Garante que o script pare após o redirecionamento
        }
    
        return $_SESSION['idUsuario'];
    }

    public static function nomeUsuario(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['nome'];
    }

    public static function salvarMensagemNaSessao($mensagem){
        $_SESSION['mensagem'] = $mensagem;
    }

    public static function mostrarMensagem(){
        if (!empty($_SESSION['mensagem'])) {
            echo '<script>
                    alert('.json_encode($_SESSION['mensagem']).');                    
                  </script>';
            unset($_SESSION['mensagem']);
        }
    }

}


?>