<?php

date_default_timezone_set('America/Sao_Paulo');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

include "vendor/autoload.php";

use NFePHP\DA\NFe\Danfe;

// Caminho para o arquivo de configuração
$configFile = 'conf.ini';

// Lê o arquivo INI e armazena as configurações em um array
$config = parse_ini_file($configFile);

$xml = file_get_contents(__DIR__ . '/xml-gerado/'.$config['chave'].'-nfe.xml');

try {

    $danfe = new Danfe($xml);
    $danfe->exibirTextoFatura = false;
    $danfe->exibirPIS = false;
    $danfe->exibirIcmsInterestadual = false;
    $danfe->exibirValorTributos = false;
    $danfe->descProdInfoComplemento = false;
    $danfe->exibirNumeroItemPedido = false;
    $danfe->setOcultarUnidadeTributavel(true);
    $danfe->obsContShow(false);
    $danfe->printParameters(
        $orientacao = 'P',
        $papel = 'A4',
        $margSup = 2,
        $margEsq = 2
    );
    $danfe->logoParameters($logo, $logoAlign = 'C', $mode_bw = false);
    $danfe->setDefaultFont($font = 'times');
    $danfe->setDefaultDecimalPlaces(4);
    $danfe->debugMode(false);
    $danfe->creditsIntegratorFooter('Systemaker - Gestão de Negócios - https://systemaker.com.br');
    $pdf = $danfe->render();
    header('Content-Type: application/pdf');
    echo $pdf;
} catch (InvalidArgumentException $e) {
    echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
}