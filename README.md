### Notificação com RabbitMQ e Laravel
Codificação em PHP de uma API REST desenvolvida com o framework Laravel para processar notificações em filas utilizando RabbitMQ. As notificações podem ser enviadas por e-mail ou como notificações push para dispositivos móveis.

- Requisitos
Certifique-se de ter os seguintes requisitos antes de executar a API:

- PHP >= 8.1
- Composer (https://getcomposer.org/)
- RabbitMQ instalado e configurado
- Chave de API válida do Firebase Cloud Messaging (FCM) para notificações push (se aplicável)

####Siga os passos abaixo para configurar e executar a API:

Clone o repositório para o seu ambiente local:

git clone <url-do-repositorio>
Navegue até o diretório do projeto:

cd Notification-Laravel-RabbitMQ
- Instale as dependências do Composer:

- composer install

#### Configure as variáveis de ambiente:
Renomeie o arquivo .env.example para .env e defina as configurações do RabbitMQ e da chave de API do FCM, se necessário.

Execute as migrações do banco de dados:

- php artisan migrate
Inicie o servidor de desenvolvimento:

- php artisan serve
Uso
#### A API possui apenas uma rota, que é responsável por consumir as notificações da fila e processá-las:

POST /notifications: Esta rota consome as notificações presentes na fila do RabbitMQ e as processa. Dependendo do tipo de notificação (e-mail ou push), a API enviará a notificação para o respectivo destino.
Certifique-se de que o servidor RabbitMQ esteja em execução antes de consumir as notificações.

Exemplo de Notificação
Para enviar uma notificação, adicione uma mensagem JSON na fila com o seguinte formato:

- Para notificação por e-mail:

json
**{
  "type": "email",
  "data": {
    "email": "destinatario@examplo.com"
  }
}**
- Para notificação push:

json
**{
  "type": "push",
  "data": {
    "device_token": "TOKEN_DO_DISPOSITIVO"
  }
}**

Este projeto mostra como criar uma API que consome filas de notificações usando RabbitMQ e Laravel. 

Lembre-se de que é importante configurar corretamente o ambiente de produção, garantir a segurança da API e monitorar o processamento das filas para evitar problemas de desempenho e escalabilidade.

Autor:
**Emerson Amorim**
