# URL Routing

## [User]

### GET User Detail
/users/?token={access_token}

### GET Menu
/users/menu?token={access_token}

### GET Order
/users/order?token={access_token}

### GET Location Kurir
/users/tracking?kurir_id={id_kurir}&token={access_token}

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

## [Kurir]

### GET Kurir Detail
/kurir/?token={access_token}

### GET Order by self
/kurir/order?token={access_token}

### GET Order Detail
/kurir/order?order_id={id_order}&token={access_token}

### POST Login
=> username 
=> password
/kurir/login

### POST Order Active 
=> token
=> id_order
/kurir/order

## [Admin]

### GET Admin Detail
/admin/?token={access_token}

### GET Menu
/admin/menu?token={access_token}

### GET Order
/admin/order?token={access_token}

### GET Order Detail !k
/admin/order?order_id={order_id}&token={access_token}

### POST Login
=> username
=> pasword
/admin/login

### POST Menu
=> token

=> method (add)
=> nama
=> gambar
=> harga
=> kategori

=> method (update)
=> token
=> id_menu 
=> nama (optional)
=> gambar (optional)
=> harga (optional)
=> kategori (optional)

=> method (delete)
=> token
=> id_menu

/admin/menu

### POST Kurir
=> token

=> method (add_kurir) !k
=> nama
=> username
=> password
=> foto
=> no_hp
=> no_plat

=> method (delete_kurir)
=> id_kurir

=> method (update_kurir) !k

/admin/kurir

### POST Outlet
=> token

=> method (add_outlet)
=> username
=> password
=> id_resto
=> nama_outlet
=> alamat

=> method (update_outlet)
=> id_outlet
=> nama_outlet (optional)
=> alamat (optional)
=> username (optional)
=> password (optional)

=> method (delete_outlet)
=> id_outlet

/admin/outlet

### POST User !k
=> token

=> method (add_user) !k

=> method (delete_user) !k
=> id_user

=> method (update_user) !k

### POST Setting

### POST Setting Profile

[Outlet]
### GET Outlet detail
/outlet/?token={access_token}

### GET Menu

### GET Order by Outlet
/outlet/order?token={access_token}

### POST order ke kurir
=> token
=> method (send_order)
=> id_order
=> id_kurir
/outlet/kurir

### POST Setting

## [Other]

### GET Tools Value

## Get all result
/public/tools_value?access=true

## Get result from key
/public/tools_value?access=true&key={key}

### GET Hot Order [Coming Soon]
/public/feature?type=hot-order&access=true

### GET Banner [Coming Soon]
/public/feature?type=banner&access=true

### GET Outlet
/public/list?type=outlet&access=true

### GET User
/public/list?type=user&access=true

### GET Admin
/public/list?type=admin&access=true

### GET Kurir
/public/list?type=kurir&access=true

### GET Resto
/public/list?type=resto&access=true
