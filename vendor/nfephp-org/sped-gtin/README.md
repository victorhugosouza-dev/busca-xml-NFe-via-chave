# sped-gtin

A partir do layout 4.00 da NFe (e NFCe) o GTIN do produto passa a ser um item OBRIGATÓRIO, quando existir. Portanto em todas as NFe (e NFCe) que contêm produtos que possuem códigos de barras para fins comerciais (GTIN 8, 12, 13 ou 14) derão indicar os mesmos em suas notas.

É importantissimo que esses GTIN sejam corretos e validados, antes de serem inseridos em seus sistemas para evitar rejeições por parte da SEFAZ.

## NT 2021.003_v1_00 Validação do GTIN

Esta Nota Técnica entrou em vigor em setembro e estabelece que apenas GTINs nacionais podem ser utilizados, ou seja apenas GTINs que iniciem 789 ou 790, além disso apenas os produtos cadastrados no [Cadastro Nacional de Produtos - CNP](https://cnp.gs1br.org/).

É importante destacar que o cadastro centralizado irá também comparar outras informações como:

-   GTIN
-   Marca
-   Tipo GTIN (8, 12, 13 ou 14 posições)
-   Descrição do Produto
-   Identificação do Dono da Marca (CNPJ ou CPF)
-   Dados da classificação do produto (Segmento, Família, Classe e Subclasse/Bloco)
-   NCM
-   CEST (quando existir)
-   Peso Bruto e Peso Líquido
-   Unidade de Medida de Peso Bruto e Peso Líquido

Estes dados devem estar compatíveis com os informados na NFe ou NFCe.

Para verificar https://gepir.gs1.org/index.php/search-by-gtin

**NOTA: caso o fabricante/importador, não cadastre corretamente o seu produto poderão ocorrer rejeiçoes, isso não afeta os comerciantes pois quem será impedido de emitir a NFe será o fabricante/importador.**

Esta classe faz exatamente isso, verifica a correção do numero GTIN, com relação a sua estrutura, prefixo, região e digito verificador.

A SEFAZ fará consultas adicionais ao [Cadastro Nacional de Produtos - CNP](https://www.gs1br.org/servicos-e-solucoes/cadastro-centralizado-de-gtin?gclid=Cj0KCQjw4_zVBRDVARIsAFNI9eCoTYJdZTQ36i4aAWsW4Hmppbqk4BVEvty4gQKXnMnAfX2XRcQcawwaAgv9EALw_wcB). mas ainda não existe a disponibilidade de uma API para que nós também possamos faze-lo, então ainda poderão haver rejeições caso os dados que você possue não estejam de acordo com o CNP.

[![Actions Status][ico-workflow]][link-actions]

![PHP][ico-php]
[![Latest Stable Version][ico-stable]][link-packagist]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

[![Issues][ico-issues]][link-issues]
[![Forks][ico-forks]][link-forks]
[![Stars][ico-stars]][link-stars]

## Formas de Uso

```php

use NFePHP\Gtin\Gtin;

try {
    $gtin = new Gtin('78935761');
    $region = $gtin->region;
    $prefix = $gtin->prefix;
    $digitoVerificador = $gtin->checkDigit;

    if ($gtin->isValid()) {
        echo "Valido";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}


```

### A classe Gtin, possue várias propriedades

**region - (string) indica a região de uso do numero ex. GS1 Brasil**

**prefix - (string) indica o prefixo da região ex. 789**

**checkDigit - (int) indica o digito verificador para uma determinada sequência numérica (calculado)**

**restricted - (bool) indica se o pefixo é de uso restrito**

**type - (int) indica o tipo de gtin (8,12,13, ou 14)**

### A função principal da classe é :

function isValid()

Essa função não recebe parâmetros e retornará TRUE caso o numero GTIN seja em principio válido.

A classe também pode ser instanciada estaticamente.

```php

use NFePHP\Gtin\Gtin;

try {
    $gtin = Gtin::check('78935761');
    $region = $gtin->region;
    $prefix = $gtin->prefix;
    $digitoVerificador = $gtin->checkDigit;

    if ($gtin->isValid()) {
        echo "Válido";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

Ou ainda:

```php

use NFePHP\Gtin\Gtin;

$gtin = "78935761";

try {
    if (Gtin::check($gtin)->isValid()) {
        echo "Válido";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

_NOTA: A validação retornará TRUE (Válida) caso o GTIN seja "SEM GTIN" exatamente dessa forma com letras maiúsculas, qualquer outra forma gera uma rejeição._

## Validações e Exceptions

Caso o numero fornecido não atenda alguma restrição será retornado um Exception:

|                                           Exception                                           |                                        Causa                                        |                       Solução                        |
| :-------------------------------------------------------------------------------------------: | :---------------------------------------------------------------------------------: | :--------------------------------------------------: |
|                               Um numero GTIN deve ser passado.                                |                     Nenhuma variável foi passada como parâmetro                     | Verifique o conteúdo da variável que deve ser o GTIN |
|                   Um numero GTIN contêm apenas numeros [????] não é aceito.                   |                Foi passado uma string contendo digitos não numéricos                | Verifique o conteúdo da variável que deve ser o GTIN |
|             Apenas GTIN 8, 12, 13 ou 14 esse numero não atende esses parâmetros.              |        Com os nomes dizem os GTIN devem ter tamanhos definidos 8,12,13 ou 14        | Verifique o conteúdo da variável que deve ser o GTIN |
|                         Um GTIN 14 não pode iniciar com numeral ZERO.                         |                                  auto explicativo                                   | Verifique o conteúdo da variável que deve ser o GTIN |
|                            O prefixo ??? do GTIN é INVALIDO [???].                            | O prefixo usado não corresponde a lista de prefixos válidos ou é um de uso restrito |          Esse numero não deve estar correto          |
|                               O digito verificador é INVALIDO.                                |             O último digito é calculado e não corresponde ao fornecido              |          Esse numero de GTIN está INCORRETO          |
| Somente prefixos do Brasil [789, 790] são aceitáveis. O prefixo ??? do GTIN é INVALIDO [???]. |                              GTIN não é do GS1 Brasil                               |     Esse número GTIN não é aceitável pela SEFAZ      |

Este pacote é aderente com os [PSR-1], [PSR-2] e [PSR-4]. Se você observar negligências de conformidade, por favor envie um patch via pull request.

[psr-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[psr-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[psr-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

Não deixe de se cadastrar no [grupo de discussão do NFePHP](http://groups.google.com/group/nfephp) para acompanhar o desenvolvimento e participar das discussões e tirar dúvidas!

## Install

**Este pacote está listado no [Packgist](https://packagist.org/) foi desenvolvido para uso do [Composer](https://getcomposer.org/), portanto não será explicitada nenhuma alternativa de instalação.**

_E deve ser instalado com:_

```bash
composer require nfephp-org/sped-gtin
```

Ou ainda alterando o composer.json do seu aplicativo inserindo:

```json
"require": {
    "nfephp-org/sped-gtin" : "^1.0"
}
```

_Para utilizar o pacote em desenvolvimento (branch master) deve ser instalado com:_

```bash
composer require nfephp-org/sped-gtin:dev-master
```

_Ou ainda alterando o composer.json do seu aplicativo inserindo:_

```json
"require": {
    "nfephp-org/sped-gtin" : "dev-master"
}
```

> NOTA: Ao utilizar este pacote na versão em desenvolvimento não se esqueça de alterar o composer.json da sua aplicação para aceitar pacotes em desenvolvimento, alterando a propriedade "minimum-stability" de "stable" para "dev".
>
> ```json
> "minimum-stability": "dev"
> ```

## Requirements

Para que este pacote possa funcionar são necessários os seguintes requisitos do PHP e outros pacotes dos quais esse depende.

-   PHP >= 7.x (recomendável PHP 7.2.x) (já inclui suporte PHP 8.0.x)

## Contributing

Para contribuir com correções de BUGS, melhoria no código, documentação, elaboração de testes ou qualquer outro auxílio técnico e de programação por favor observe o [CONTRIBUTING](CONTRIBUTING.md) e o [Código de Conduta](CONDUCT.md) para maiores detalhes.

## Change log

Acompanhe o [CHANGELOG](CHANGELOG.md) para maiores informações sobre as alterações recentes.

## Testing

Todos os testes são desenvolvidos para operar com o PHPUNIT

## Security

Caso você encontre algum problema relativo a segurança, por favor envie um email diretamente aos mantenedores do pacote ao invés de abrir um ISSUE.

## Credits

Roberto L. Machado (owner and developer)
Gustavo Lidani (developer)

## License

Este pacote está diponibilizado sob LGPLv3 ou MIT License (MIT). Leia [Arquivo de Licença](LICENSE.md) para maiores informações.

[ico-stable]: https://poser.okvpn.org/nfephp-org/sped-gtin/v/stable
[ico-stars]: https://img.shields.io/github/stars/nfephp-org/sped-gtin.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/sped-gtin.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/sped-gtin.svg?style=flat-square
[ico-workflow]: https://github.com/nfephp-org/sped-gtin/actions/workflows/ci.yml/badge.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-gtin.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-gtin.svg?style=flat-square
[ico-license]: https://poser.okvpn.org/nfephp-org/sped-gtin/license
[ico-php]: https://img.shields.io/packagist/php-v/nfephp-org/sped-gtin
[link-packagist]: https://packagist.org/packages/nfephp-org/sped-gtin
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-gtin
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-gtin/issues
[link-forks]: https://github.com/nfephp-org/sped-gtin/network
[link-stars]: https://github.com/nfephp-org/sped-gtin/stargazers
[link-actions]: https://github.com/nfephp-org/sped-gtin/actions
