<?php

include "vendor/autoload.php";

date_default_timezone_set('America/Sao_Paulo');

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;

// Caminho para o arquivo de configuração
$configFile = 'conf.ini';

// Lê o arquivo INI e armazena as configurações em um array
$config = parse_ini_file($configFile);

$chave = strval($config['chave']);

$pfxcontent = file_get_contents('certificado/'.$config['certificado']);
$password = $config['senhaCertificado'];

$config = [
    "atualizacao" => date('Y-m-d H:i:s'),
    "tpAmb" => intval($config['tpAmb']),
    "razaosocial" => $config['razaosocial'],
    "siglaUF" => $config['siglaUF'],
    "cnpj" => $config['cnpj'],
    "schemes" => "PL_009_V4",
    "versao" => "4.00"

];

$configJson = json_encode($config);

$certificate = Certificate::readPfx($pfxcontent, $password);

if ($certificate !== false) {
    $tools = new Tools($configJson, $certificate);
    $tools->model('55');
    $tools->setEnvironment(1);

    $response = $tools->sefazDownload($chave);
    
    header('Content-type: text/xml; charset=UTF-8');
    
    $xml = $response;
    echo $xml;

    // Encontrar o valor do elemento <docZip>
    $docZipStart = strpos($xml, '<docZip');
    $docZipEnd = strpos($xml, '</docZip>') + 9; // Tamanho da tag </docZip> é 9

    $docZipContent = substr($xml, $docZipStart, $docZipEnd - $docZipStart);

    // Extrair o conteúdo base64 do elemento <docZip>
    $base64Content = substr($docZipContent, strpos($docZipContent, '>') + 1, -9); // Remove as tags 
    
    $conteudo = gzdecode(base64_decode($base64Content));

    // Especifica o nome do arquivo
    $nomeArquivo = "xml-gerado/".$chave."-nfe.xml";
    
    // Escreve o conteúdo no arquivo
    if (file_put_contents($nomeArquivo, $conteudo) !== false) {
        //echo "Arquivo criado com sucesso!";
    } else {
        echo "Ocorreu um erro ao criar o arquivo.";
    }

} else {
    echo "Falha do Carregamento do Certificado.";
}

