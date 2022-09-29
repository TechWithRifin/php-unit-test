--setup autoload--

1. sebelum kita membuat unit test, biasakan untuk membedakan lokasi tempat source code PHP dan source code unit test.
2. untuk membedakan lokasi source code, rata-rata programmer php akan membuat folder tests untuk menyimpan seluruh file unit test yang telah dibuatnya
3. supaya tidak perlu melakukan use namespace, kita bisa menggunakan namespace yang sama untuk folder code php dan folder unit test (ini tidak masalah asalkan lokasi foldernya terpisah).
4. supaya tidak selalu melakukan require_once setiap kali kita ingin menggunakan class pada eksternal file tertentu, kita dapat melakukan setup pada autoload yang ada di file composer.json (dengan ini kita juga tidak perlu melakukan use namaNamespace lagi untuk menggunakan suatu class yang terletak di file yang berbeda (asalkan class tersebut memiliki namaspace yang sama misal sama-sama memiliki namespace Php\Tests)
5. format setupnya adalah "namanamespace":"namaFolder"
6. terdapat 2 jenis autoload yang akan dibuat yaitu autoload (autoload untuk source code php) dan autoload-dev (autoload untuk source code unit test)
7. setelah selesai membuat autoload jangan lupa jalankan command composer dump-autoload pada terminal.

--membuat unit test--

1. untuk membuat unit test, kita bisa membuat class turunan dari PHPUnit\Framework\TestCase
2. biasanya nama file dan class unit test mengikuti nama class yang akan kita test misalnya jika kita ingin melakukan test test terhadap class 'Person', maka nama file dan class unit testnya adalah 'PersonTest'
3. untuk skenario unit testnya, kita akan membuatnya dalam public function dengan awalan test\*. Misalnya testCreate(),testUpdate(), testDelete() dll

note:nama function dari unit testnya wajib memiliki awalan 'test' supaya dapat dijalankan (jika tidak ada awalan test, function tersebut tidak akan dijalankan oleh phpunit)

--menjalankan unit test--

1. setelah membuat unit test, kita bisa menjalankan unit test tersebut menggunakan binary file PHPUnit bernama phpunit
2. caranya gunakan command 'vendor/bin/phpunit namaFileTest' pada terminal
3. misalnya vendor/bin/phpunit tests/CounterTest.php
4. jika kita ingin menjalan semua file unit test yang telah dibuat, maka kita hanya perlu menjalankan command 'vendor/bin/phpunit namaFolderLokasiFileSeluruhUnitTestYangDibuat' pada terminal
5. misalnya 'vendor/bin/phpunit tests'

--menjalankan unit test per Method

1. terkadang kita hanya ingin menjalankan salah satu method tertentu pada unit test yang telah kita buat. Untuk melakukan hal tersebut, kita dapat menggunakan command: vendor/bin/phpunit --filter 'ClassTest::testMethod' tests/ClassTest.php
2. misalnya : vendor/bin/phpunit --filter 'CounterTest::testCounter' tests/CounterTest.php

--phpunit configuration

1. PHPUnit mendukung yang namanya file konfigurasi, dimana kita bisa menambahkan konfigurasi untuk PHPUnitnya
2. file konfigurasi phpunit itu bentuknya adalah file xml (biasanya nama filenya adalah PHPUnit.xml)
3. kita bisa menambahkan informasi apapun pada konfigurasi PHPUnitnya
4. Detailnya apa saja yang bisa kita tambahkan pada file konfigurasinya bisa kita liha di halaman dokumentasinya pada link https://phpunit.readthedocs.io/en/9.5/configuration.html

--menjalankan phpunit dan configuration

1. setalah file configuration nya selesai dibuat, kita bisa menjalankan PHPUnit dengan perintah
2. vendor/bin/phpunit --configuration phpunit.xml
3. atau kita bisa mengkonfigurasi commandnya dengan menambahkan script pada file composer.json dengan menambahkan "test":"phpunit --configuration phpunit.xml"
4. note : jika menggunakan composer, kita tidak perlu lagi menambahkan vendor/bin lagi karena composer otomatis akan mendetectnya

--Test Suite

1. Saat kita menggunakan PHPUnit configuration, kita bisa membuat beberapa test suite
2. Aggap saja test suite ini seperti grub atau fitur didalam unit test. misalnya kita ingin mengelompokkan tiap fitur pada unit test kita, lalu kita hanya ingin menjalankan phpunit pada kelompok fitur yang kita inginkan saja
3. pada kasus seperti diatas, kita bisa membuat test suite yang berbeda untuk mengelompokkan tiap fitur, sehingga saat menjalankan unit test, kita bisa memilih test suite mana yang ingin kita jalankan

--Menjalankan test suite tertentu

1. secara default, saat kita menjalankan unit test dengan configuration, PHPUnit akan menjalankan semua test suite
2. namun jika kita ingin memilih test suite tertentu saja yang dijalankan, kita bisa melakukan perintah
3. vendor/bin/phpunit --configuration phpunit.xml --testsuite namaTestSuiteYangDiinginkan
4. misal kita menkalankan vendor/bin/phpunit --configuration phpunit.xml --testsuite product
5. maka unit test yang dijalankan hanya unit test yang ada di dalam test suite product saja

--Fitur lainnya

1. Secara garis besar, kita sudah mempelajari semua fitur utama yang terdapat di PHPUnit
2. Berikut adalah beberapa fitur yang bisa di eksplore jika ingin memperdalam library phpunit
3. mempelajari annotation di phpunit : https://phpunit.readthedocs.io/en/9.5/annotations.html
4. mempelajari perintah command line phpunit : https://phpunit.readthedocs.io/en/9.5/textui.html
5. mempelajari xml configuration di phpunit : https://phpunit.readthedocs.io/en/9.5/configuration.html
6. mempelajari code coverage : https://phpunit.readthedocs.io/en/9.5/code-coverage-analysis.html
7. code coverage berfungsi untuk memeriksa unit test kita sudah mengcover merapa kode kita. Misal kode kita ada 100 baris, maka code coverage akan memeriksa unit test yang kita buat sudah mengcover berapa baris code (static code analysis).
