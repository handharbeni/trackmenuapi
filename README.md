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
=> keterangan (optional)
/users/order

### POST Add Order [OK]
=> token
=> method (new_order)
=> id_order (optional)
=> alamat
=> latitude
=> longitude
=> delivery_fee
=> keterangan (optional)
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

### GET Semua Order [OK]
/kurir/order?status=all&token={access_token}

### GET Order by self [OK]
/kurir/order?status=self&token={access_token}

### POST Login [OK] 
=> username 
=> password
/kurir/login

### POST Order Active [OK]
=> token
=> id_order
/kurir/order

## [Admin]

### GET Admin Detail [OK]
/admin/?token={access_token}

### GET Order

### GET Order Done

### POST Login [OK]
=> username
=> pasword
/admin/login

### POST Menu (setting)

### POST Kurir (daftarkan kurir) [OK]
=> token
=> method (add_kurir)
=> nama
=> username
=> password
/admin/kurir

### POST order ke kurir [OK]
=> token
=> method (send_order)
=> id_order
=> id_kurir
/admin/kurir

## [Other]

### GET Tools Value

## Get all result
/tools_value?access=true

## Get result from key
/tools_value?access=true&key={key}