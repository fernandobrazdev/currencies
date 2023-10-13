# Scrap ISO_4217

Este projeto realiza um crawling de moedas no padrão ISO 4217

## Instalação

Este é um projeto desenvolvido com Laravel Framework versão 10. Siga estas instruções:

```bash
1- Clone este resposiório
2- Execute o comando "composer install" dentro da pasta do projeto
3- Para rodar o projeto execute o comando "php artisan server"
```

## Instruções de uso
O endpoint para execução é **"<project-url>/api/currency"**

O input de dados deve conter dois parâmetros obrigatórios **"type"** e **"value"**. Value deve ser um Código ISO valido ex: GBP ou um número ex: 826, ou um array destes modelos conforme exemplos abaixo:

```json
{
    "type": "code",
    "value": "AUD"
}
```
ou
```json
{
    "type": "code_list",
    "value": ["AUD","DKK"]
}
```
ou
```json
{
    "type": "number",
    "value": "948"
}
```
ou
```json
{
    "type": "number_list",
    "value": ["948","208"]
}
```
O retorno serão os dados no formato abaixo:
```json
[
    {
        "code": "DKK",
        "number": "208",
        "decimal": "2",
        "currency": "Coroa dinamarquesa",
        "currency_locations": [
            {
                "icon": "//upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Denmark.svg/22px-Flag_of_Denmark.svg.png",
                "location": "Dinamarca"
            },
            {
                "icon": "//upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Flag_of_the_Faroe_Islands.svg/22px-Flag_of_the_Faroe_Islands.svg.png",
                "location": "Ilhas FeroÃ©"
            },
            {
                "icon": "//upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_Greenland.svg/22px-Flag_of_Greenland.svg.png",
                "location": "GronelÃ¢ndia"
            }
        ]
    }
]
```
## Desenvolvedor

Este projeto foi desenvolvido por Fernando Braz | fernando@zarbsolution.com.br
