DOCUMENTAÇÃO DA API CRIADA INVESTAPP

Developer: Renan Vieira
Subject: Teste de criação de api

Ferramentas: 
- desenvolvido em PHP com VSCode/SublimeText;
- utilizado banco de dados MySQL com SGBD HeidiSQL;
- utilizado o Postman para realização de testes;
- utilizado pacote XAMPP para rodar o projeto em local;



Para o teste, primeiro use a api de gerar um token.
Depois use esse token gerado em "Authorization: Bearer {token}" para executar as demais API's.

Obs.: os teste foram realizados no POSTMAN.


- Segue as informações das API's abaixo:


. Gerar token para o proprietário:
- link: http://localhost/investapp/api/auth/gettoken
- metodo de envio: POST
- dados de envio (em JSON), exemplo: 
{
	"owner":"administrador",
	"email":"admin@email.com"
}
- retorno: será em JSON, com o resultado esperado. Exemplo:
{
    "status": 200,
    "data": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvd25lciI6IkFETUlOSVNUUkFET1IiLCJlbWFpbCI6ImFkbWluQGVtYWlsLmNvbSJ9.epzewFiYXj-pcjC9ApWS8WfaPDnurk_TNMWbx105w6o"
}

#############################################################

. Criar um novo proprietário:
- link: http://localhost/investapp/api/users/create_owner
- metodo de envio: POST
- dados de envio (em JSON), exemplo: 
{
	"owner":"Renan",
	"email":"renan@teste.com"
}
- retorno: será em JSON. Exemplos:
{
    "status": 200,
    "message": "Usuário Renan inserido com sucesso!"
}
ou
{
    "status": 400,
    "message": "Dados duplicados. Esse email já está sendo usado!"
}

#############################################################

. Listar todas os usuários:
- link: http://localhost/Investapp/api/users/
- metodo de envio: GET
- retorno: será em JSON. Exemplos:
[
    {
        "id": "8",
        "owner_name": "RENAN",
        "email": "renan@teste2.com",
        "token": null
    }
]
ou
{
    "status": 404,
    "message": "NENHUM DADO ENCONTRADO"
}

#############################################################

. Listar um usuário pelo id:
- link: http://localhost/Investapp/api/users/{id}
- metodo de envio: GET
- retorno: será em JSON. Exemplos:
{
    "id": "9",
    "owner_name": "RENAN",
    "email": "renan@teste2.com",
    "token": null
}
ou
{
    "status": 404,
    "data": "USUÁRIO NÃO FOI ENCONTRADO EM NOSSA BASE DE DADOS"
}

#############################################################

. Listar todas as aplicações(investimentos) ativas(com status = 1):
- link: http://localhost/Investapp/api/investments/
- metodo de envio: GET
- retorno: será em JSON. Exemplos:
[
    {
        "id": "16",
        "owner": "Renan",
        "create_date": "2022-01-12",
        "initial_amount": "1000",
        "actual_amount": "1010.43",
        "next_birthday_date": "2022-04-12",
        "status": "1",
        "withdrawal_amount": "1008.08",
        "withdrawal_date": "2022-03-18"
    },
    {
        "id": "19",
        "owner": "Renan",
        "create_date": "2022-03-16",
        "initial_amount": "1000",
        "actual_amount": "1000",
        "next_birthday_date": "2022-04-16",
        "status": "1",
        "withdrawal_amount": null,
        "withdrawal_date": null
    },
    {
        "id": "20",
        "owner": "Renan",
        "create_date": "2022-03-06",
        "initial_amount": "1000",
        "actual_amount": "1000",
        "next_birthday_date": "2022-04-06",
        "status": "1",
        "withdrawal_amount": null,
        "withdrawal_date": null
    },
    {
        "id": "21",
        "owner": "Renan",
        "create_date": "2022-02-06",
        "initial_amount": "1000",
        "actual_amount": "1005.2",
        "next_birthday_date": "2022-04-06",
        "status": "1",
        "withdrawal_amount": null,
        "withdrawal_date": null
    }
]
ou
{
    "status": 404,
    "message": "NENHUM DADO ENCONTRADO"
}

#############################################################

. Listar uma aplicação(investimento) ativa de acordo com o {id}:
- link: http://localhost/Investapp/api/investments/{id}
- metodo de envio: GET
- retorno: será em JSON. Exemplos:
{
    "id": "21",
    "owner": "Renan",
    "create_date": "2022-02-06",
    "initial_amount": "1000",
    "actual_amount": "1005.2",
    "next_birthday_date": "2022-04-06",
    "status": "1",
    "withdrawal_amount": null,
    "withdrawal_date": null
}
ou
{
    "status": 404,
    "message": "A APLICAÇÃO NÃO FOI ENCONTRADA EM NOSSA BASE DE DADOS"
}

#############################################################

. Listar uma aplicação(investimento) ativa de acordo com o {nome do proprietário}:
- link: http://localhost/Investapp/api/investments/owner/{owner-name}
- metodo de envio: GET
- retorno: será em JSON. Exemplos:
{
    "1": {
        "id": "18",
        "owner": "Renan",
        "create_date": "2022-02-16",
        "initial_amount": "1000",
        "actual_amount": "1005.2",
        "next_birthday_date": "2022-04-16",
        "status": "1",
        "withdrawal_amount": null,
        "withdrawal_date": null
    },
    "2": {
        "id": "22",
        "owner": "Renan",
        "create_date": "2022-01-16",
        "initial_amount": "800",
        "actual_amount": "808.34",
        "next_birthday_date": "2022-04-16",
        "status": "1",
        "withdrawal_amount": "806.46",
        "withdrawal_date": "0000-00-00"
    }
}
ou
{
    "status": 404,
    "message": "NENHUM DADO ENCONTRADO"
}

#############################################################

. Listar uma aplicação(investimento) ativa de acordo com o {nome do proprietário}, {quantidade de visualização} e {número da página}:
- link: http://localhost/Investapp/api/investments/owner/{owner-name}/{amount_of_view}/{page_number}
- metodo de envio: GET
- retorno: será em JSON. Exemplos:
{
    "1": {
        "id": "18",
        "owner": "Renan",
        "create_date": "2022-02-16",
        "initial_amount": "1000",
        "actual_amount": "1005.2",
        "next_birthday_date": "2022-04-16",
        "status": "1",
        "withdrawal_amount": null,
        "withdrawal_date": null
    },
    "2": {
        "id": "22",
        "owner": "Renan",
        "create_date": "2022-01-16",
        "initial_amount": "800",
        "actual_amount": "808.34",
        "next_birthday_date": "2022-04-16",
        "status": "1",
        "withdrawal_amount": "806.46",
        "withdrawal_date": "0000-00-00"
    }
}
ou
{
    "status": 404,
    "message": "Não encontrado"
}
ou
{
    "status": 404,
    "message": "Não exite mais dados para exibir."
}

#############################################################

. Criar uma nova aplicação(investimento):
- link: http://localhost/investapp/api/investments/new
- metodo de envio: POST
- dados de envio (em JSON), exemplo: 
{
	"owner":"Renan",
    "create_date":"11-03-2022",
    "initial_amount":1000
}
- retorno: será em JSON. Exemplos:
{
    "status": 200,
    "message": "Investimento criado em 2022-03-16 com sucesso!"
}
ou, caso envie faltando um dos dados:
{
    "status": 400,
    "message": "Dados inválidos"
}
ou
{
    "status": 400,
    "message": "Data de criação é maior que a data atual."
}
ou
{
    "status": 400,
    "message": "O valor informado deve ser sempre positivo (maior que zero)."
}

#############################################################

. Retirar uma aplicação(investimento):
- link: http://localhost/Investapp/api/investments/withdrawal
- metodo de envio: DELETE
- dados de envio (em JSON), exemplo: 
{
	"id":12,
    "withdrawal_date":"12-03-2022"
}
- retorno: será em JSON. Exemplos:
{
    "status": 200,
    "msg": "Retirada de 1000.00 em 2022-03-13 realizada com sucesso!"
}
ou
{
    "status": 404,
    "message": "Nenhum registro foi encontrado."
}
ou
{
    "status": 400,
    "message": "Data de retirada não pode ser maior que a data atual nem menor que a data de criação do investimento."
}
