<?php

namespace Php\Tests;

use PHPUnit\Framework\TestCase;

class PersonTest2 extends TestCase
{
    private Person $person;

    protected function setUp(): void
    {
        // function ini akan sebelum 
        $this->person = new Person('Carl');
        echo "setup" . PHP_EOL;
    }

    /**
     * @before
     */
    public function cobaBefore()
    {
        echo "before" . PHP_EOL;
    }

    public function testSuccess()
    {
        $this->assertEquals("Hello Budi, my name is Carl", $this->person->sayHello("Budi"));
    }

    public function testException()
    {
        $this->expectException(\exception::class);
        $this->person->sayHello(null);
    }

    protected function tearDown(): void
    {
        echo "Tear Down" . PHP_EOL;
    }

    /**
     * @after
     */
    public function cobaAfter()
    {
        echo "after" . PHP_EOL;
    }
}


// Fixture
// salah satu hal yang memakan banyak waktu saat membuat unit test ialah membuat kode awal yang berulang-ulang sebelum unit test atau membuat kode akhir yang berulang ulang setelah unit test
// hal ini dinamakan fixture
// pada unit test yang sudah kita buat sebelumnya (PersonTest dan CounterTest), kita berulang kali membuat object $counter atau $person di setiap function unit test
// hal tersebut terlalu memakan banyak waktu
// untungnya PHPUnit memiliki fitur fixture yang dapat mempercepat dan mempermudah kita dalam membuat unit test
// dengan fitur tersebut, kita tidak perlu lagi membuat kode awal dan akhir disetiap function unit test yang akan kita buat

// fixture setUp() function
// class TestCase memiliki sebuah function yang bernama setUp()
// function ini merupakan function yang akan selalu dieksekusi sebelum function unit test di eksekusi (jadi misalkan kita memiliki 10 function unit test didalam sebuah TestClass, maka function setUp() tersebut akan dieksekusi sebanyak 10 kali)
// function setUp() cocok sekali untuk membuat kode yang kita inginkan sebelum unit test dijalankan

// Annotation @before
// Annotation @before memiliki fungsi yang sama dengan setUp() function
// jika kita ingin membuat setUp() function dengan nama yang berbeda, kita dapat menggunakan annotation @before
// kita bahkan dapat membuat lebih dari satu setUp() function menggunakan annotation @before

// perhatian
// function fixture akan dieksekusi tiap kali method unit test di eksekusi
// itu artinya jika didalam 1 class terdapat 10 function unit test, maka fixturenya akan dieksekusi 10 kali

// fixture tearDown() function
// selain fixture setUp() class TestCase juga memiliki fixture function bernama tearDown()
// berbeda dengan setUp() function, tearDown() function merupakan function yang akan selalu dieksekusi setelah function unit test dieksekusi (jadi misalkan kita memiliki 10 function unit test didalam sebuah TestClass, maka function tearDown() tersebut akan dieksekusi sebanyak 10 kali)
// function tearDown() sangat cocok untuk membuat kode yang kita inginkan setelah unit test dijalankan

// Annotation @after
// Annotation @after memiliki fungsi yang sama dengan tearDown() function
// jika kita ingin membuat tearDown() function dengan nama yang berbeda, kita dapat menggunakan annotation @after
// kita bahkan dapat membuat lebih dari satu tearDown() function menggunakan annotation @after

// independent unit test
// secara default, object class unit test itu sebenarnya akan selalu dibuat sebelum function unit test dijalankan. Jadi tidak menggunakan object unit test yang sama.
/**
 * begini cara berjalan unit test :
 * membuat object unit test
 * menjalankan fixture setUp
 * menjalankan function unit test
 * menalankan fixture tearDown
 * ulangi dari awal untuk function unit test selanjutnya
 */

// sharing fixture
// karena object dari class unit test selalu dibuat ulang, maka kadang agak menyulitkan jika kita ingin membuat data yang bisa disharing antar function unit test, misal koneksi database
// untuk mengatasi masalah tersebut, kita bisa menggunakan sharing fixture dengan cara membuat datanya berupa variable static, sehingga variable static tersebut tidak perlu tergantung dengan object unit test lagi

// fixture setUpBeforeClass
// sekarang pertanyaannya, bagaimana cara menginisialisasi data tersebut karena kita tidak bisa menggunakan setUp() method karena method tersebut bukan static function
// untungnya PHPUnit memiliki fitur static function setUPBeforeClass(). fitur ini hanya di eksekusi sekali diwal saja (jadi misalkan di dalam 1 class terdapat 10 function unit test, maka static function  setUPBeforeClass() hanya dijalankan sekali saja yaitu diawal sebelum semua unit test didalam class tersebut dieksekusi)

// annotation @beforeClass
// cara kerjanya sama dengan seUpBeforeClass
// jika kita ingin membuat static function setUpBeforeClass() dengan nama yang berbeda, kita dapat menggunakan annotation @beforeClass
// kita bahkan dapat membuat lebih dari satu static function setUpBeforeClass() menggunakan annotation @beforeClass

// fixture static function tearDownAfterClass
// PHPUnit memiliki fitur static function tearDownAfterClass(). fitur ini hanya di eksekusi sekali diakhir saja (jadi misalkan di dalam 1 class terdapat 10 function unit test, maka static function  tearDownAfterClass() hanya dijalankan sekali saja yaitu diakhir ketika semua unit test didalam class tersebut selesai dieksekusi)

// annotation @afterClass
// cara kerjanya sama dengan tearDownAfterClass
// jika kita ingin membuat static function tearDownAfterClass() dengan nama yang berbeda, kita dapat menggunakan annotation @afterClass
// kita bahkan dapat membuat lebih dari satu static function tearDownAfterClass() menggunakan annotation @afterClass