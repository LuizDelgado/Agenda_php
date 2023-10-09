<?php

session_start();

include_once("connection.php");
include_once("url.php");

$data = $_POST;
$id;

//modificações do banco
if(!empty($data)){

    //criar contato
    if($data["type"] === "create"){
        // echo "CRIAR DADO"; //3:34

        $name = $data["name"];
        $phone = $data["phone"];
        $observations = $data["observations"];
        
        $query = "INSERT INTO contats(name, phone, observations) VALUES (:name, :phone, :observations)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":observations", $observations);

        try{
            $stmt->execute();
            $_SESSION["msg"] = "Contato criado com sucesso!";
            
        }catch(PDOException $e) {
                $error = $e->getMessage();
                echo "Erro: $error";
            }

    }  if($data["type"] === "edit"){
        $name = $data["name"];
        $phone = $data["phone"];
        $observations = $data["observations"];
        $id = $data["id"];

        $query = "UPDATE contats SET name = :name, phone = :phone, observations = :observations WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":observations", $observations);
        $stmt->bindParam(":id", $id);
        try{
            $stmt->execute();
            $_SESSION["msg"] = "Contato atualizado com sucesso!";
            
        }catch(PDOException $e) {
                $error = $e->getMessage();
                echo "Erro: $error";
            }
    } else if($data["type"]==="delete"){
        $id = $data["id"];
        $query = "DELETE FROM contats WHERE id= :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id",$id);
        try{
            $stmt->execute();
            $_SESSION["msg"] = "Removido com sucesso!";
            
        }catch(PDOException $e) {
                $error = $e->getMessage();
                echo "Erro: $error";
            }
    }
//Redirecionamento 
header("Location:" . $BASE_URL . "../index.php");

} else{ //Seleção de dados
    if(!empty($_GET)){
        $id = $_GET["id"];
    }
    //Retorna o dado de um contato
    if(!empty($id)) {
    
    $query = "SELECT * FROM contats WHERE id = :id";
    
    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(":id", $id);
    
    $stmt->execute();
    
    $contact = $stmt->fetch();
    
    
    } else{
        //Retorna todos os contatos
        $contats = [];
    
        $query = "SELECT * FROM contats"; // 
    
        $stmt = $conn->prepare($query);
    
        $stmt->execute();
    
        $contats = $stmt->fetchAll(); // Correção da variável $stmt
    
    }
    
}

//FECHAR CONEXÃO
$conn = null;
