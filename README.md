API Carnê
API para gerenciamento de itens de carnê utilizando um banco de dados em arquivo JSON.

Visão Geral
A API Carnê é uma aplicação simples para gerenciar dados relacionados a parcelas de carnê. Ela permite adicionar novos registros e consultar registros existentes usando um arquivo JSON como banco de dados.

Requisitos
PHP 7.4 ou superior
Servidor web (Apache, Nginx, etc.)

Instalação
Clone o repositório: git clone https://github.com/petnupe/apiCarne.git;
Navegue até o diretório do projeto: cd apiCarne;
Certifique-se de que o diretório storage existe e tem permissões de leitura e escrita.

Testes
Para testar a API, você pode usar ferramentas como Insomnia ou Postman.

Adicionar um carnê
Envie uma requisição POST para /carne com o corpo JSON contendo os dados do item:

Exemplo:
POST /carne HTTP/1.1
Content-Type: application/json
{
    "valor_total": 1000.00,
    "valor_entrada": 200.00,
    "quantidade_parcelas": 10,
    "primeiro_dia_cobranca": "2024-08-01",
    "periodicidade": "mensal"
}
 
Resposta:
{
    "total": "1000.00",
    "valor_entrada": "200.00",
    "parcelas": [
        {
            "data_vencimento": "2024-08-01",
            "valor": "80.00",
            "numero": 1
        },
    ],
    "entrada": true,
    "id": 1
}

Consultar um carnê
Envie uma requisição GET para /carne com o ID do item no corpo da requisição JSON:

Exemplo:
GET /carne HTTP/1.1
Content-Type: application/json
{
    "id": 1
}
 
Resposta:
{
    "total": "1000.00",
    "valor_entrada": "200.00",
    "parcelas": [
        {
            "data_vencimento": "2024-08-01",
            "valor": "80.00",
            "numero": 1
        },
    ],
    "entrada": true,
    "id": 1
}
