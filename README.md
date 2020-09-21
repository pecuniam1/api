# API
## This is a sample PHP site to show how various HTTP methods work with an API

| Method | Endpoint | Purpose |
| ------ | -------- | ------- |
| GET | [https://api.joekellyonline.com/users](https://api.joekellyonline.com/users) | Returns a list of users in JSON. |
| GET | anything else | Will return page not found (404). |
| POST | [https://api.joekellyonline.com/auth](https://api.joekellyonline.com/auth) | Will verify login and return token. |
| DELETE | [https://api.joekellyonline.com/auth](https://api.joekellyonline.com/users) | Will remove the token. |
| HEAD, PUT, CONNECT<br>OPTIONS, TRACE, PATCH | all | Will return method not allowed (405). |