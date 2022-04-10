# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## Project

Proje kısaca bahsedecek olursak;
Proje de bir Plato sabit boyutlu bir plato oluşturma ve oluşan bu platoya robotlar indirerek bu robotları plato yüzeyinde setState metoduna gönderdiğimiz komutlar ile hareket ettirmektir.

Proje içerisinde
createPlato : Plato oluşturma endpoint'dir. Bu endpoint e zorunlu alanlar gönderilerek plato oluşturmak hedeflenmiştir.
getPlato : Tüm platoların çekildiği servistir.

createRover : Belirli bir platoya indirilmek üzere oluşturulan robottur.
getRover: Var olan tüm robotların çekildiği servistir.
getState : Bu method bize string bir ifade döndürmektedir. ve dönen değer içerisinde robotun plato üzerindeki konumu ve kordinantları yer almaktadır
setState: Bu platoya parametre olarak girilen command'ler ile robot plato üzerinde hareketi hedeflenmiştir.Bu servisin kontrol ettiği noktalar sayesinde robot plato yüzeyinde kalmakta olup ilgili test caseler unit teste eklenmiştir
Eğer robotu belirli kordinant sistemi dışarısına çıkardığımızda yani onunla ilgili komut verdiğimizde sistem hata yansıtmaktadır.
Bir komut girilmediğinde hata yansıtmaktadır
hatalı komut girildiğinde ('L,M,R' dışında) sistem hata verecek şekilde düzenlenmiştir.


