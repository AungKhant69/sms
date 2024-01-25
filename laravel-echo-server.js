{
    import Echo from 'laravel-echo';
	"authHost": "http://127.0.0.1:8000/",
	"authEndpoint": "/broadcasting/auth",
	"clients": [
		{
			"appId": "e4ede941d9cab9ec",
			"key": "986913e2cb0b2b61cd156f412d33a7d8"
		}
	],
	"database": "mysql",
	"databaseConfig": {
		"redis": {},
		"mysql": {
            "host": env('DB_HOST', '127.0.0.1'),
            "port": env('DB_PORT', '3306'),
            "user": env('DB_USERNAME', 'forge'),
            "password": env('DB_PASSWORD', ''),
            "database": env('DB_DATABASE', 'forge'),
        }
	},
	"devMode": true,
	"host": null,
	"port": "6001",
	"protocol": "http",
	"socketio": {},
	"secureOptions": 67108864,
	"sslCertPath": "",
	"sslKeyPath": "",
	"sslCertChainPath": "",
	"sslPassphrase": "",
	"subscribers": {
		"http": true,
		"redis": true
	},
	"apiOriginAllow": {
		"allowCors": true,
		"allowOrigin": "http://127.0.0.1:8000/",
		"allowMethods": "GET, POST, PUT, DELETE",
		"allowHeaders": "Origin"
	}
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
    });
}
