# Фронтенд библиотеки, PaymentProcessor

Фронтенд библиотеки представляет класс **[PaynetEasy\PaynetEasyApi\PaymentProcessor](../../source/PaynetEasy/PaynetEasyApi/PaymentProcessor.php)**. Класс предоставляет следующие возможности:
* **[executeWorkflow()](#executeWorkflow)**: простое выполнение сценариев оплаты, которые состоят из нескольких запросов
* **[executeQuery()](#executeQuery)**: простое выполнение запроса к PaynetEasy
* **[executeCallback()](#executeCallback)**: простая обработка данных, полученных от PaynetEasy при возвращении пользователя с платежного шлюза или при поступлении коллбэка от PaynetEasy

### <a name="executeWorkflow"></a>executeWorkflow(): простое выполнение сценариев оплаты, которые состоят из нескольких запросов

Некоторые сценарии обработки платежа имеют асинхронную природу и состоят из нескольких запросов. Так, некоторые запросы не возвращают результат платежа сразу и требуют многократного выполнения запроса **status**, после которого клиент может быть отправлен на шлюз PaynetEasy для проведения дополнительных шагов авторизации. После возвращения клиента на сервис мерчанта необходима обработка данных, полученных от шлюза.
<a name="async_queries_list"></a>Cписок асинхронных запросов:
* sale
* preauth
* capture
* return
* make-rebill
* transfer-by-ref

Отдельный сценарий обработки необходим и при интеграции платежной формы. Запрос к шлюзу возвращает ссылку на платежную форму, на которую должен быть отправлен клиент. После заполнения и отправки данных шлюз обрабатывает платежную форму и возвращает клиента на сервис мерчанта. После возвращения клиента на сервис мерчанта необходима обработка данных, полученных от шлюза.
<a name="form_queries_list"></a>Список запросов для интеграции платежной формы:
* sale-form
* preauth-form
* transfer-form

Для удобного выполнения всех этих платежных сценариев в **PaymentProcessor** реализован метод **[executeWorkflow()](../../source/PaynetEasy/PaynetEasyApi/PaymentProcessor.php#L122)**. Ознакомиться с использованием данного метода можно в следующих файлах:
* [Пример выполнения запроса sale](../../example/sale.php)
* [Пример выполнения запроса preauth](../../example/preauth.php)
* [Пример выполнения запроса capture](../../example/capture.php)
* [Пример выполнения запроса return](../../example/return.php)
* [Пример выполнения запроса make-rebill](../../example/make-rebill.php)
* [Пример выполнения запроса transfer-by-ref](../../example/transfer-by-ref.php)
* [Пример выполнения запроса sale-form](../../example/sale-form.php)
* [Пример выполнения запроса preauth-form](../../example/preauth-form.php)
* [Пример выполнения запроса transfer-form](../../example/transfer-form.php)

### <a name="executeQuery"></a>executeQuery(): простое выполнение запроса к PaynetEasy

Некоторые операции с платежами не требуют сложных сценариев обработки и выполняются с помощью одного запроса.
Список простых операций над платежом:
* create-card-ref
* get-card-info
* status

Для удобного выполнения этих операций в **PaymentProcessor** реализован метод **[executeQuery()](../../source/PaynetEasy/PaynetEasyApi/PaymentProcessor.php#L178)**. Ознакомиться с использованием данного метода можно в следующих файлах:
* [Пример выполнения запроса create-card-ref](../../example/create-card-ref.php)
* [Пример выполнения запроса get-card-info](../../example/get-card-info.php)
* [Пример выполнения запроса status](../../example/status.php)

### <a name="executeCallback"></a>executeCallback(): простая обработка данных, полученных от PaynetEasy

Каждый [асинхронный запрос](#async_queries_list) может завершиться перенаправлением пользователя на платежный шлюз для выполнения дополнительных действий, а каждый [запрос для интеграции платежной формы](#form_queries_list) обязательно содержит такое перенаправление. Каждый раз при возвращении пользователя на сервис мерчанта передаются данные с результатом обработки платежа. Также, если в [конфигурации стартового запроса](../00-basic-tutorial.md#stage_1_step_3) был задан ключ **server_callback_url**, то через некоторое время PaynetEasy вызовет этот url и передаст ему данные, описанные в wiki PaynetEasy в разделе [Merchant Callbacks](http://wiki.payneteasy.com/index.php/PnE:Merchant_Callbacks). Для удобной обработки этих данных в **PaymentProcessor** реализован метод **[executeCallback()](../../source/PaynetEasy/PaynetEasyApi/PaymentProcessor.php#L215)**. Ознакомиться с использованием данного метода можно в следующих файлах:
* [Базовый пример использования библиотеки](../00-basic-tutorial.md#stage_2)