# Khách sạn Michi

## Deloy
````
- composer du
- php artisan key:gen
- php artisan passport:key
- php artisan migrate
- php artisan reset-db
- php artisan ser
````

## Thông tin về các trạng thái
````php
general => [
    0 => "Chờ",
    1 => "Ẩn | Dừng | Hủy" 
]

hotel => [
    2 => "Hoạt động",
]

booking => [
    2 => "Đã Check in",
    3 => "Đã thanh toán",
    4 => "Hoàn thành",
]

comment => [
    2 => "Xác nhận"
]

category => [
    2 => "Hoạt Động"
]

room => [
    2 => "Hoạt động"
]

admin => [
    2 => "Hoạt động"
]
````

## Giới tính
````php
0 => Nam
1 => Nữ
2 => Khác
````
