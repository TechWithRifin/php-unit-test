<?php

namespace Php\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

//harus extends testcase supaya class dianggap unit test
class CounterTest extends TestCase
{

    public function testCounter(): void
    {
        $counter = new Counter();
        $counter->increment();
        // $counter->increment();
        // assertEquals berfungsi untuk mengecek nilai yang dicek dan ekspektasi itu harus sama
        // format pengecekan assertEquals(nilai ekspektasi, nilai yang akan di periksa);
        // cara 1
        Assert::assertEquals(1, $counter->getCounter());
        // cara 2
        $counter->increment();
        $this->assertEquals(2, $counter->getCounter());
        // cara 3
        $counter->increment();
        self::assertEquals(3, $counter->getCounter());
    }

    // implemetasi penggunaan test incomplete
    public function testIncrement()
    {
        $counter = new Counter(); //kode ini dieksekusi
        $this->assertEquals(0, $counter->getCounter()); // kode ini dieksekusi
        $this->markTestIncomplete("unit test ini belum selesai karena saya capek"); //kode ini memberi tahu phpunit bahwa kita belum selesai melakukan koding pada methods unit test ini kemudian akan terdapat tanda incomplete ketika dia di eksekusi
        echo "ini tidak akan dieksekusi"; //secara otomatis kode ini tidak akan dieksekusi karena berada dibawah markTestIncomplete
    }

    // contoh implementasi @test annotation
    /**
     * @test
     */
    public function increment(): void
    {
        $counter = new Counter();
        $counter->increment();
        $this->assertEquals(1, $counter->getCounter());
    }

    // contoh implementasi skip test
    public function testSkip()
    {
        $this->markTestSkipped("unit test ini masih error dan saya masih bingung"); //memberi tahu phpunit bahwa unit test ini akan diskip (disable) terlebih dahulu
        echo "ini tidak akan dieksekusi"; //secara otomatis kode ini tidak akan di eksekusi karena perada dibawah kode markTestSkipped()
    }

    // contoh implementasi test dependency

    public function testFirst(): Counter
    {
        $counter = new Counter();
        $counter->increment();
        $this->assertEquals(1, $counter->getCounter());
        return $counter;
    }

    /**
     * @depends testFirst
     */
    public function testSecond(Counter $counter): void
    {
        $counter->increment();
        $this->assertEquals(2, $counter->getCounter()); //hasilnya 2 karena melanjutkan increment dari function sebelumnya
    }

    // contoh implementasi annotation @requires (skip berdasarkan kondisi)

    // unit test ini hanya dapat berjalan pada os windows saja
    /**
     * @requires OSFAMILY Windows
     */
    public function testOnlyRunInWindows()
    {
        $this->assertTrue(true, "Only in Windows");
    }

    // unit test ini hanya dapat berjalan pada os linux dengan versi php lebih dari sama dengan versi 8
    /**
     * @requires PHP >= 8
     * @requires OSFAMILY Linux
     */
    public function testOnlyForLinuxAndPHP8()
    {
        $this->assertTrue(true, "Only for mac and PHP 8");
    }
}

// assertion
// assertion adalah pengecekan apakah sebuah kondisi sedah terpenuhi atau tidak. Jika kondisinya tidak terpenuhi, maka unit testnya dianggap gagal
// jadi yang namanya unit test pasti ada ekspektasinya misalnya jika kita melakukan increment ekspektasi kita adalah nilainya jadi 1 dan jika kita melakukan increment lagi ekspektasi kita nilainya akan menjadi 2
// kita dapat menggunakan assertion untuk memastikan bahwa ekspektasi yang kita inginkan itu benar hasilnya.

// class Assert

// untuk melakukan assertion, kita tidak perlu melakukannya secara manual menggunakan if else atau try catch statement. Kita bisa memanfaatkan class yang sudah disediakan oleh PHPUnit yang bernama PHPUnit\Framework\Assert
// class Assert memiliki static function yang sangat banyak dan dapat kita gunakan sesuai kebutuhan
// ketika suatu kondisi pada assertions tidak terpenuhi, maka secara otomatis akan terjadi error dan unit test akan dianggap gagal
// dokumentasi menganai assertion dan method2nya dapat dilihat pada link https://phpunit.readthedocs.io/9.5/assertions.html

// assert di testcase
// saat kita membuat class unit test, maka kita wajib membuat untuk meng extends class TestCase
// sedangkan class TestCase itu sendiri, merupakan turunan turunan dari class Assert
// jadi secara tidak langsung, kita juga bisa menggunakakn function-function assertions langsung dari unit test menggunakan $this atau self tanpa melakukan use PHPUnit\Framework\Assert terlebih dahulu

// Annotation
// secara default, saat kita membuat function unit test, maka nama function harus diawali dengan kata test
// PHPUnit juga mendukung fitur annotation. annotation adalah suatu meta data atau informasi yang bisa dimasukkan ke dalam source code, dimana di PHP annotation diletakkan pada doc block (blok komentar)
// Terdapat sangat banyak annotation yang terdapat di dalam PHPUnit
// anda dapat melihatnya pada link berikut : https://phpunit.readthedocs.io/9.5/annotation.html

// @test annotation
// @test merupakan annotation yang digunakan untuk menandai bahwa suatu function merupakan sebuah unit test
// dengan menambahkan @test, kita tidak perlu lagi membuat nama function yang selalu diawali dengan kata test

// Test Dependency (@depends annotation)
// ketika membuat unit test, kadang kadang kita ingin melanjutkan unit test sebelumnya atau bahkan tergantung dengan hasil unit test sebelumnya
// Di PHPUnit, kita dapat membuat unit test yang tergantung dengan hasil unit test sebelumnya (jadi kalau unit test sebelumnya itu sukses berarti langsung di lanjutkan ke unit test selanjutnya, kalau gagal, maka tidak akan dilanjutkan ke unit test selanjutnya)
// untuk melakukan ini, kita bisa menggunakan annotation @depends lalu di ikuti dengan namaUnitTest sebelumnya (dengan ini, unit test sebelumnya bisa mengambalikan value dan nanti valuenya itu akan ditangkap oleh parameter unit test selanjutnya)
// dengan menerapkan depends annotation, maka jika kita menjalankan unit test kedua maka secara otomatis unit test pertama akan dijalankan juga. Namun jika kita menjalankan unit test pertama saja, maka unit test kedua tidak ikut dijalankan secara otomatis.

// perhatian
// unit test yang baik harus independen (tidak tergantung dengan unit test lainnya)
// jika kita membuat unit test yang tergantung dengan unit test lain, maka jika unit test sebelumnya error, maka unit test yang bergantung pada unit test tersebut akan ikut error juga
// jadi penggunaan test dependency atau @depends annotation sangat tidak dianjurkan.

/**
 * incomplete test
 * saat membuat unit test, kadang ada kalanya unit test kita belum selesai karena kode testnya terlalu panjang dan kita belum bisa menyelesaikannya saat itu
 * secara default, jika tidak terdapat masalah pada unit testnya, maka PHPUnit akan menganggap unit test tersebut
 * dan kadang jika kita lupa, bisa jadi kita tidak pernah tahu kalo ternyata ada kode pada unit test yang belum selesai, dikarenakan terlihat sukses
 * untuk masalah seperti ini, ada baiknya kita memberi tahu PHPUnit bahwa unit test tersebut belum selesai dengan menggunakan method Assert::markTestIncomplete()
 * dengan melakukan ini, kita tidak perlu takut lagi jika kita lupa kalau ada unit test yang codingnya belum selesai karena pada saat dieksekusi nanti akan terdapat laporan kalau ada unit test yang belum selesai (incomplete).
 */

/**
 * Skip Test
 * kadang-kadang kita mendapat masalah saat membuat unit test kemudian kita bingung cara mengatasinya, sehingga kita ingin men-disable terlebih dahulu init test tersebut sampai ketemu cara menyelesaikan masalah tersebut
 * saat kita ingin men-disable suatu unit test, kita biasanya akan menghapus kata test pada nama functionnya atau jika kita menggunakan @test annotation, kita akan menghapus @test annotation tersebut
 * hanya saja jika kita melakukan hal tersebut, maka secara otomatis unit test akan hilang dari laporan phpunit, dan jika kita lupa untuk men-eneble unit testnya, maka unit test tersebut akan ter-disable selamanya
 * Cara terbaik jika ada unit test yang memang ingin kita disable adalah dengan menggunakan Assert::markTestSkipped()
 * dengan ini, pada saat kita melakukan eksekusi, kita nanti akan mendapatkan laporan dari phpunit bahwa ada unit test yang kita skip.
 */

/**
 * skip berdasrkan kondisi (@requires annotation)
 * PHPUnit mendukung kita untuk melakukan skip unit test berdasarkan kondisi tertentu
 * ada banyak kondisi yang bisa di cek, seperti versi php, sistem operasi dan lain-lain
 * untuk melakukan skip berdasarkan kondisi, kita dapat menggunakan annotationn @requires yang diikuti dengan tipe kondisinya
 * Beberapa kondisi dapat mendukung operasi perbandingan
 */

/**
 * Parameter @requires
 * PHP = versi php yang diperbolehkan
 * PHPUnit = versi phpunit yang diperbolehkan
 * OSFAMILY = os yang di perbolehkan misal hanya memperbolehkan linux saja
 * function = unit test ini hanya bisa berjalan kalau terdapat function yang telah disebutkan
 * extension = unit test ini hanya bisa berjalan kalau terdapat extention yang telah disebutkan
 */