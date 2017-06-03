# URL Routing

## [User/Customer]

### GET User Detail [OK]
/users/?token={access_token}

### GET Menu [OK]
/users/menu?token={access_token}

### GET Order Active [OK]
/users/order?token={access_token}

### GET Location Kurir [OK]
/users/tracking?kurir_id={id_kurir}&token={access_token}

### POST Order [OK]
=> token
=> method (new_order)
=> id_order (optional)
=> id_menu
=> jumlah
/users/order

### POST Login [OK]
=> email
=> password
/users/login

### POST Daftar [OK]
=> nama
=> email
=> password
/users/daftar

### POST Location (set location)

### POST Order Done [OK]
=> token
=> method (done)
=> id_order
/users/order

## [Kurir]

### GET Kurir Detail [OK]
/kurir/?token={access_token}

### GET Available Order

/users/order?status=tersedia&token={access_token}

### GET Order by self
/users/order?status=self&token={access_token}

### POST Login [OK] 
=> username 
=> password
/kurir/login

### POST Order Active

## [Admin]

### GET Admin Detail [OK]
/admin/?token={access_token}

### GET Order

### GET Order Done

### POST Login [OK]
=> username
=> pasword
/admin/login

### POST Order to Kurir

### POST Menu (setting)
