<?php 

require_once 'vendor/autoload.php';
include 'conexao.php';

Unirest\Request::auth('godoygustavofernandes@gmail.com', 'ATATT3xFfGF0wWH5ls1CxBL4SQe_yDMIKX2lOD5DCdK1V2JIn69oRbv-qFwEUs8RFqZMEMQk5G9apFV8TvESQquxabUNgs7TijZ6RvCrb7ffMRlUtk6pitZApriZY1TLCo2y-SqQnJRHjOurSesI28pjUwjVLmSICs8KKrHJ6A0OcbKBw8Ht9nU=06A17DCD');

$headers = array(
    'Accept' => 'application/json',
    'Content-Type' => 'application/json'
);

$startAt = 0;
$maxResults = 100; // valor máximo do request
$total = 1; // apenas para inicialização do loop
$cd_os = '';

// Loop para trazer 100 tarefas a cada request e incrementar 
while ($startAt < $total) {

    // Cada Request só trás 100 Tarefas, por isso o parametro Startat que indica de qual ID ele começara a pegar
    $response = Unirest\Request::get(
        "https://godoygustavofernandes.atlassian.net/rest/api/3/search?jql=&startAt=$startAt&maxResults=$maxResults",
        $headers,
        ['timeout' => 60]
    );

    $IssueList = json_decode($response->raw_body, true);

    if (isset($IssueList['issues'])) {
        // Pega o total de tarefas que possuem 
        $total = $IssueList['total'];

        // Pega os id's das tarefas e transforma em e string para filtrar na consulta
        foreach ($IssueList['issues'] as $issue) {
            $sepString = substr($issue['fields']['summary'], 0, strpos($issue['fields']['summary'], '-'));
            $cd_os .= $sepString . ',';
        }

        // soma para fazer o proximo request com valores diferentes dos anteriores
        $startAt += $maxResults;

    } else {
        echo 'erro: Jira não possui tarefas';
        break;
    }
}

// Remove ultima virgula
$os_code = substr($cd_os, 0, -1);

?>