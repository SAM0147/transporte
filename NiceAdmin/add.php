<?php
    // Funções

    function addDados($tabela,$usuario_Id,$ano,$conn,$valorDiario){
        // Comando SQL para selecionar a tabela a "corridaanual".
        $sql = "SELECT * FROM '$tabela' WHERE Usuario_Id='$usuario_Id' AND Ano = '$ano'";
        // Comando fazer a conexão com a tabela.
        $result = mysqli_query($conn, $sql);
        // Teste para verificar se a tabela está vazia ou com informações.
        $row = mysqli_fetch_assoc($result);
        if(mysqli_num_rows($result) != 0){
            if($row['Usuario_Id'] == $usuario_Id){
                $valorTotal = $row['ValorTotalAnual'];
                $valorTotal = $valorTotal + $valorDiario;
                $sql_corrida = "UPDATE '$tabela' SET ValorTotalAnual = '".$valorTotal."' WHERE Usuario_Id='$usuario_Id'";
                $idAno = $row['id'];
            }
        }else{
            $valorTotal = $valorDiario;
            $sql_corrida = "INSERT INTO '$tabela' (Usuario_Id, Ano, ValorTotalAnual) VALUES ('$usuario_Id', '$ano','$valorTotal')";
            $conn->query($sql_corrida);
            $idAno = mysqli_insert_id($conn);
            $statusAno = 1;
        }
    }
    
    include "./db.php";
    # Inserir novos dados no banco.
    $usuario_Id = $_POST['Usuario_Id'];
    $ano = $_POST['Ano'];
    $mes = $_POST['Mes'];
    $dia = $_POST['Dia'];
    $valorDiario = $_POST['ValorDiario'];
    $valorTotalAnual;
    $valorTotalMes;
    $sql_corridaAnual;
    $sql_corridaMensal;
    $sql_corridaDiaria;
    $statusAno = 0;
    $statusMes = 0;

    
    if(isset($_POST['enviar'])){
        try{ 
            // Comando SQL para selecionar a tabela a "corridaanual".
            $sqlTabAno = "SELECT * FROM corridaanual WHERE Usuario_Id='$usuario_Id' AND Ano = '$ano'";
            // Comando fazer a conexão com a tabela.
            $resultAno = mysqli_query($conn, $sqlTabAno);
            // Teste para verificar se a tabela está vazia ou com informações.
            $row = mysqli_fetch_assoc($resultAno);
            if(mysqli_num_rows($resultAno) != 0){
                if($row['Usuario_Id'] == $usuario_Id){
                    $valorTotalAnual = $row['ValorTotalAnual'];
                    $valorTotalAnual = $valorTotalAnual + $valorDiario;
                    $sql_corridaAnual = "UPDATE corridaanual SET ValorTotalAnual = '".$valorTotalAnual."' WHERE Usuario_Id='$usuario_Id'";
                    $idAno = $row['id'];
                }
            }else{
                $valorTotalAnual = $valorDiario;
                $sql_corridaAnual = "INSERT INTO corridaanual (Usuario_Id, Ano, ValorTotalAnual) VALUES ('$usuario_Id', '$ano','$valorTotalAnual')";
                $conn->query($sql_corridaAnual);
                $idAno = mysqli_insert_id($conn);
                $statusAno = 1;
            }


            // Comando SQL para selecionar a tabela a "corridamensal".
            $sqlTabMes = "SELECT * FROM corridamensal WHERE CorridaAnual_Id='$idAno' AND Mes='$mes'";
            // Comando fazer a conexão com a tabela.
            $resultMes = mysqli_query($conn, $sqlTabMes);
            // Teste para verificar se a tabela está vazia ou com informações.
            $row = mysqli_fetch_assoc($resultMes);
            if(mysqli_num_rows($resultMes) != 0){
                if($row['CorridaAnual_Id'] == $idAno){
                    $valorTotalMes = $row['ValorTotalMensal'];
                    $valorTotalMes = $valorTotalMes + $valorDiario;
                    $sql_corridaMensal = "UPDATE corridamensal SET ValorTotalMensal = '".$valorTotalMes."' WHERE Mes='$mes' AND CorridaAnual_Id='$idAno'";
                    $idMes = $row['id'];
                }
            }else{
                $valorTotalMes = $valorDiario;
                $sql_corridaMensal = "INSERT INTO corridamensal (CorridaAnual_Id, Mes, ValorTotalMensal) VALUES ('$idAno', '$mes','$valorTotalMes')";
                $conn->query($sql_corridaMensal);
                $idMes = mysqli_insert_id($conn);
                $statusMes = 1;
            }


            // Comando SQL para selecionar a tabela a "corridadiaria".
            $sqlTabDia = "SELECT * FROM corridadiaria WHERE CorridaMensal_Id='$idMes' AND Dia='$dia'";
            // Comando fazer a conexão com a tabela.
            $resultDia = mysqli_query($conn, $sqlTabDia);
            // Teste para verificar se a tabela está vazia ou com informações.
            $row = mysqli_fetch_assoc($resultDia);
            //$valorTotalMes = $row['ValorTotalMensal'];
            //$valorTotalMes = $valorTotalMes + $valorDiario;
            if(mysqli_num_rows($resultDia) == 0){
                $sql_corridaDiaria = "INSERT INTO corridadiaria(Dia,CorridaMensal_Id, ValorDiario) VALUES ('$dia', '$idMes','$valorDiario')";
                $conn->query($sql_corridaDiaria);
                if($statusAno == 0){
                    $conn->query($sql_corridaAnual);
                }
                if($statusMes == 0){
                    $conn->query($sql_corridaMensal);
                }
            }else{
                echo "Já foi cadastrado uma corrida nesta data.";
            }
            
            echo "Dados inseridos com sucesso.";

        } catch(Exception $e){
            $conn->rollback();
            echo "Erro na transação: " . $e->getMessage();
        }
    }

    $conn->close();
        

    ?>