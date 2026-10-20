
<?php
// Configuracoes iniciais
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
require "config.php";
$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];
$corpo = json_decode(file_get_contents("php://input"), true) ?? [];
function teste(){
    echo json_encode(["mensagem" => "Back-end da transportadora respondendo!"]);
    exit;
}
// CRUD - Cliente
// CREATE
function createCliente($con, $dados){
    $stmt = $con->prepare(
    "INSERT INTO Cliente (nome, endereco, telefone, email) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $dados["nome"],
        $dados["endereco"],
        $dados["telefone"],
        $dados["email"]
    ]);
    http_response_code(201);
    echo json_encode([
        "mensagem" => "Cliente criado com sucesso!",
        "id" => $con->lastInsertId()
    ]);
}
// READ
function readCliente($con){
    $stmt = $con->query("SELECT * FROM Cliente");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
// UPDATE
function updateCliente($con, $id, $dados){
    $stmt = $con->prepare(
    "UPDATE Cliente SET nome = ?, endereco = ?, telefone = ?, email = ? WHERE idCliente = ?"
    );
    $stmt->execute([
        $dados["nome"],
        $dados["endereco"],
        $dados["telefone"],
        $dados["email"],
        $id
    ]);
    echo json_encode(["mensagem" => "Cliente atualizado com sucesso!"]);
}
// DELETE
function deleteCliente($con, $id){
    $stmt = $con->prepare("DELETE FROM Cliente WHERE idCliente = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Cliente removido com sucesso!"]);
}
// CRUD - Funcionario
// CREATE
function createFuncionario($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO funcionario (nome, cargo, salario) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $dados["nome"],
        $dados["cargo"],
        $dados["salario"]
    ]);

    http_response_code(201);

    echo json_encode([
        "mensagem" => "Funcionario criado com sucesso!",
        "id" => $con->lastInsertId()
    ]);
}

// READ
function readFuncionario($con){
    $stmt = $con->query("SELECT * FROM funcionario");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// UPDATE
function updateFuncionario($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE funcionario SET nome = ?, cargo = ?, salario = ? WHERE idFuncionario = ?"
    );
    $stmt->execute([
        $dados["nome"],
        $dados["cargo"],
        $dados["salario"],
        $id
    ]);
    echo json_encode(["mensagem" => "Funcionario atualizado com sucesso!"]);
}
// DELETE
function deleteFuncionario($con, $id){
    $stmt = $con->prepare(
        "DELETE FROM funcionario WHERE idFuncionario = ?"
    );

    $stmt->execute([$id]);

    echo json_encode(["mensagem" => "Funcionario removido com sucesso!"]);
}
// ROTAS - Cliente
if ($rota == "cliente"){
    switch ($metodo){

case "GET":
    readCliente($con);
    break;
    case "POST":
            createCliente($con, $corpo);
            break;
        case "PUT":
            if (isset($_GET["id"])){
                updateCliente($con, $_GET["id"], $corpo);
                } else {
                http_response_code(400);
        echo json_encode(["mensagem" => "Informe o ID do cliente!"]);
        }
        break;
        case "DELETE":
            if (isset($_GET["id"])){
                deleteCliente($con, $_GET["id"]);
            } else {
        http_response_code(400);
        echo json_encode(["mensagem" => "Informe o ID do cliente!"]);
    }
    break;

    default:
            http_response_code(405);
            echo json_encode(["mensagem" => "Metodo nao permitido!"]);
    }
// ROTAS - Funcionario

} else if ($rota == "funcionario"){

    switch ($metodo){

    case "GET":
    readFuncionario($con);
    break;
    case "POST":
    createFuncionario($con, $corpo);
    break;
    case "PUT":
            if (isset($_GET["id"])){
                updateFuncionario($con, $_GET["id"], $corpo);
            } else {
                http_response_code(400);
                echo json_encode(["mensagem" => "Informe o ID do funcionario!"]);
            }
        break;
        case "DELETE":
            if (isset($_GET["id"])){
                deleteFuncionario($con, $_GET["id"]);
    } else {
    http_response_code(400);
    echo json_encode(["mensagem" => "Informe o ID do funcionario!"]);
        }
        break;
        default:
        http_response_code(405);
        echo json_encode(["mensagem" => "Metodo nao permitido!"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["mensagem" => "Rota nao encontrada!"]);
}
