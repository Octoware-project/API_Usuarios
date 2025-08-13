## Al clonar
-composer install

-php artisan key:generate

-php  artisan  passport:keys

-php artisan passport:client --password

-client_id y client_secret ----> Esto lo tenes que guardar 


## Uso
Todas las peticiones deben tener el encabezado `Accept: application/json` y `Content-Type: application/json`.

#### Passport Keys
Cada vez que se haga un despliegue del proyecto (por ejemplo, luego de clonar desde Github) se deben crear las keys de Passport:

`php  artisan  passport:keys`

#### Crear Password Grant Client 
Para que los usuarios puedan iniciar sesion con su usuario y password, procedemos a crear un Grant Client: `php artisan passport:client --password`

Esto se debe realizar cada vez que se despliega el proyecto.

Se debe tomar nota del client_id y client_secret, ya que estos deben ir en cada request para iniciar sesion.

### Registro 

POST a `/api/user`:

{
	name: NombreDelUsuario
	email: EmailDelUsuario
	password: password
}
**Todos los campos son obligatorios.**


### Login 

POST a`oauth/token: (client_id y client_secret vienen del paso anterior)

{
	'username' => 'correo@correo.com,
	'password' => 'password',
	'grant_type' => 'password',
	'client_id' => 'CLIENT_ID',
	'client_secret' => 'CLIENT_SECRET'
}

Esto nos devuelve un JSON con el siguiente contenido:


{
	"token_type": "Bearer",
	"expires_in": 31622400,
	"access_token": "TOKEN",
	"refresh_token": "REFRESH TOKEN"
}

### Validación del Token

GET a `/api/validate` con el token obtenido en un Header llamado `Authorization` 
con el contenido `Bearer TOKEN_OBTENIDO`.

Esto nos devuelve los datos del usuario para procesarlo si se desea. Si no se envia un token, o no se envia un token valido, el endpoint no devuelve nada.


### Logout

