<?php

//////////
//ORACLE//
//////////

//TREINAMENTO

$dbstr1 ="(DESCRIPTION = 
            (ADDRESS = 
              (PROTOCOL = TCP)(HOST = IP)(PORT = PORT))
            (CONNECT_DATA = 
              (SID = trnmv)))";

//Criar a conexao ORACLE     ||Nome|| ||Senha||  ||connection||Character_set||
if(!@($conn_ora = oci_connect('Admin','Fatalis3789',$dbstr1,'AL32UTF8'))){
	echo "Conexão falhou!";	
} else { 
	echo "Conexão OK!";	}


