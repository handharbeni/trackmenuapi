# URL Routing

## [User]

### GET User Detail [OK]
/users/?token={access_token}

### GET Menu [OK]
/users/menu?token={access_token}

### GET Order Active [OK]
/users/order?option=active&token={access_token}

### GET Order All [OK]
/users/order?option=all&token={access_token}

### GET Location Kurir [OK]
/users/tracking?kurir_id={id_kurir}&token={access_token}

### POST Login [OK]
=> email
=> password
/users/login

### POST Daftar [OK]
=> nama
=> email
=> password
/users/daftar

### POST Add Item [OK]
=> token
=> method (add_item)
=> id_order (optional)
=> id_menu
=> jumlah
/users/order

### POST Add Order [OK]
=> token
=> method (new_order)
=> id_order (optional)
=> alamat
=> latitude
=> longitude
/users/order

### POST Order Done [OK]
=> token
=> method (done)
=> id_order
/users/order

### POST Profile [OK]
=> token
=> nama
=> lokasi
=> alamat

## [Kurir]

### GET Kurir Detail [OK]
/kurir/?token={access_token}

### GET Available Order

/users/order?status=tersedia&token={access_token}

### GET Order by self [OK]
/users/order?status=self&token={access_token}

### POST Login [OK] 
=> username 
=> password
/kurir/login

### POST Order Active [OK]
=> token
=> id_order
/users/order

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

### POST Kurir (daftarkan kurir) [OK]
=> token
=> method (add_kurir)
=> nama
=> username
=> password
/admin/kurir

### POST order ke kurir
=> token
=> method (send_order)
=> id_order
/admin/kurir