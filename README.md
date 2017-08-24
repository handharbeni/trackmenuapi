# URL Routing

## [User]

### GET User Detail
/users/?token={access_token}

### GET Menu
/users/menu

### GET Order
/users/order?token={access_token}

### GET Location Kurir
/users/tracking?kurir_id={id_kurir}

### GET Rating
/users/rating?opsi={opsi}

### GET Search Order by ID
/users/search?id_order={id_order}

### POST Login
=> email
=> password
/users/login

### POST Daftar
=> nama
=> email
=> no_hp
=> password
/users/daftar

### POST Add Item 
=> token
=> method (add_item)
=> id_order (optional)
=> id_menu
=> jumlah
=> keterangan (optional)
/users/order

### POST Add Order 
=> token
=> method (new_order)
=> id_order (optional)
=> id_outlet
=> alamat
=> latitude
=> longitude
=> delivery_fee
=> keterangan (optional)
/users/order

### POST Order Done 
=> token
=> method (done)
=> id_order
/users/order

### POST Profile 
=> token
=> nama
=> lokasi
=> alamat
=> no_hp
/users/profile

### POST Rating [OK]
=> token
=> id_user
=> method (rating_menu)
=> id_menu
=> rating
=> keterangan

=> method (rating_kurir)
=> id_kurir
=> rating
=> keterangan

=> method (rating_outlet)
=> id_outlet
=> rating
=> keterangan
/users/rating

## [Kurir]

### GET Kurir Detail [OK]
/kurir/?token={access_token}

### GET Order by self [OK]
/kurir/order?token={access_token}

### GET Order by self and accepted [OK]
/kurir/order?by=accepted&token={acccess_token}

### GET Order Detail [OK]
/kurir/order?order_id={id_order}&token={access_token}

### POST Login [OK]
=> username 
=> password
/kurir/login

### POST Order Active [OK]
=> token
=> id_order
/kurir/order

## [Admin]

### GET Admin detail [OK]
/admin/?token={access_token}

### GET Admin password [OK]
/admin/?token={access_token}&getpwd=true

### GET Menu [OK]
/admin/menu?token={access_token}

### GET Menu Detail [OK]
/admin/menu?token={access_token}&sha={sha}

### GET Order [OK]
/admin/order?token={access_token}

### GET Order Detail [OK]
/admin/order?order_id={order_id}&token={access_token}

### GET Rating [OK]
/admin/rating?opsi={opsi}&token={access_token}

### POST Login [OK]
=> username
=> pasword
/admin/login

### POST accept order !k
=> token
=> method (accept_order)
=> id_order
/admin/kurir

### POST order ke kurir [OK]
=> token
=> method (send_order)
=> id_order
=> id_kurir
/admin/kurir

### POST cancel order ke kurir !k
=> token
=> method (cancel_order)
=> id_order
/admin/kurir

### POST delete order !k
=> token
=> method (delete_order)
=> id_order
/admin/kurir [!k]

### POST Menu [OK]
=> token

=> method (add)
=> nama
=> gambar
=> harga
=> kategori

=> method (update)
=> token
=> sha
=> nama (optional)
=> gambar (optional)
=> harga (optional)
=> kategori (optional)

=> method (delete)
=> token
=> sha

/admin/menu

### POST Kurir
=> token

=> method (add_kurir) [OK]
=> nama
=> username
=> password
=> foto
=> no_hp
=> no_plat

=> method (delete_kurir) [OK]
=> token_kurir

=> method (update_kurir) !k
=> token_kurir
=> nama (optional)
=> username (optional)
=> password (optional)
=> foto (optional)
=> no_hp (optional)
=> no_plat (optional)

/admin/kurir

### POST Outlet
=> token

=> method (add_outlet) [OK]
=> username
=> password
=> id_resto
=> nama_outlet
=> alamat
=> latitude
=> longitude

=> method (update_outlet) [OK]
=> nama_outlet (optional)
=> alamat (optional)
=> username (optional)
=> password (optional)
=> latitude (optional)
=> longitude (optional)

=> method (delete_outlet) [OK]

/admin/outlet

### POST User !k
=> token

=> method (add_user) !k
=> nama
=> email
=> password
=> no_hp
=> alamat

=> method (delete_user) !k
=> user_token

/admin/user

### POST Profile [OK]
=> username (optional)
=> password (optional)
/admin/profile

### POST Banner !k
=> token
=> method (add_banner) !k

=> method (delete_banner) !k

=> method (update_banner) !k

### POST Setting [OK]
=> token
=> km
/admin/setting

[Outlet]
### GET Outlet detail
/outlet/?token={access_token}

### GET Menu [OK]
/outlet/menu?token={access_token}

### GET Menu Detail [OK]
/admin/menu?token={access_token}&sha={sha}

### GET Order by Outlet
/outlet/order?token={access_token}

### GET Order Detail [OK]
/outlet/order?order_id={id_order}&token={access_token}

### GET Rating [OK]
/users/rating?opsi={opsi}&token={access_token}

### POST order ke kurir [OK]
=> token
=> method (send_order)
=> id_order
=> id_kurir
/outlet/kurir

### POST cancel order ke kurir !k
=> token
=> method (cancel_order)
=> id_order
/outlet/kurir

### POST Profile !
/outlet/profile

### POST Setting !
/outlet/setting

## [Other]

### GET Tools Value

## Get all result
/public/tools_value?access=true

## Get result from key
/public/tools_value?access=true&key={key}

### GET Hot Order [Coming Soon]
/public/feature?type=hot-order&access=true

### GET Banner [OK]
/public/feature?type=banner&access=true

### GET Outlet [OK]
/public/list?type=outlet&access=true

### GET User [OK]
/public/list?type=user&access=true

### GET Admin [OK]
/public/list?type=admin&access=true

### GET Kurir [OK]
/public/list?type=kurir&access=true

### GET Resto [OK]
/public/list?type=resto&access=true