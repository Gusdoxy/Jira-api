<?php 

require_once 'vendor/autoload.php';
include 'conexao.php';
include 'receiveIssue.php';

set_time_limit(60);

Unirest\Request::auth('godoygustavofernandes@gmail.com', 'ATATT3xFfGF0wWH5ls1CxBL4SQe_yDMIKX2lOD5DCdK1V2JIn69oRbv-qFwEUs8RFqZMEMQk5G9apFV8TvESQquxabUNgs7TijZ6RvCrb7ffMRlUtk6pitZApriZY1TLCo2y-SqQnJRHjOurSesI28pjUwjVLmSICs8KKrHJ6A0OcbKBw8Ht9nU=06A17DCD');


$headers = array(
    'Accept' => 'application/json',
    'Content-Type' => 'application/json'
);

echo $os_code;

// Tratativa caso não há chamados no Jira
if($os_code == ''){
$cons_os = "SELECT  cd_os,
                    ds_servico,
                    dbamv.fnc_long_para_char_os(CD_OS) AS DS_OBSERVACAO
            FROM SOLICITACAO_OS
            WHERE CD_OFICINA = 28
            AND tp_situacao NOT IN ('C','D')";
            
            echo 'Adicionando todos os chamados ao Jira';  

} 
// Tratativa quando há chamados no Jira
else { $cons_os = "SELECT  cd_os,
                    ds_servico,
                    dbamv.fnc_long_para_char_os(CD_OS) AS DS_OBSERVACAO
            FROM SOLICITACAO_OS
            WHERE CD_OFICINA = 28
            AND tp_situacao NOT IN ('C','D')
            AND cd_os NOT IN ($os_code)";

            echo 'Adicionando novos chamados ao Jira';

            }
            

$result_cons_os = oci_parse($conn_ora, $cons_os);
oci_execute($result_cons_os);


$row_cons_os = oci_fetch_array($result_cons_os);




while($row_cons_os = oci_fetch_array($result_cons_os, OCI_ASSOC)){


  $var_cd_os = $row_cons_os['CD_OS'];
  $var_ds_servico = $row_cons_os['DS_SERVICO'];
  if(isset($row_cons_os['DS_OBSERVACAO'])){
  $var_ds_observacao = $row_cons_os['DS_OBSERVACAO'];
  } else $var_ds_observacao = 'Sem informações';
  


  $bodyarr = array(
    "fields" => array(
        "summary" => "$var_cd_os - $var_ds_servico",
        "issuetype" => array(
            "id" => "10008"
        ),
        "project" => array(
            "id" => "10002"
        ),
        "description" => array(
            "content" => array(
                array(
                    "content" => array(
                        array(
                            "text" => $var_ds_observacao,
                            "type" => "text"
                        )
                    ),
                    "type" => "paragraph"
                )
            ),
            "type" => "doc",
            "version" => 1
        )
    )
);

  $body = json_encode($bodyarr);


  $IssueCreate = Unirest\Request::post(
    'https://godoygustavofernandes.atlassian.net/rest/api/3/issue',
    $headers,
    $body,
    ['timeout' => 60]
  );

  echo $Inserido;
};