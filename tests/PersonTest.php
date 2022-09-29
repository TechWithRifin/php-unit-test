<?php

namespace Php\Tests;

use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{

    // unit test yang baik adalah unit test yang mengimplemetasikan semua skenario sukses maupun gagal

    // unit test untuk skenario sukses
    public function testSuccess()
    {
        $person = new Person("Michael");
        $this->assertEquals("Hello Mark, my name is Michael", $person->sayHello("Mark"));
    }

    // unit test untuk skenario gagal
    public function testException()
    {
        $person = new Person("Michael");
        $this->expectException(\Exception::class); //memberitahu unit test bahwa akan terjadi exception (berharap akan terjadinya exception)
        $person->sayHello(null);
    }

    // implemetasi test output
    public function testGoodByeSuccess()
    {
        $person = new Person("michaela");
        $this->expectOutputString("Good bye Mark" . PHP_EOL); //mengharapkan terdapat output  Good bye Mark
        $person->sayGoodBye("Mark");
    }
}

// Test Exception
// saat membuat unit test, pastikan kita tidak hanya membuat unit test dengan skenario sukses atau benar saja
// kita juga wajib membuat unit test dengan skenario gagal atau salah
// kadang kadang saat terjadi skenario gagal atau salah pada kode yang sedang kita test, kita sering menggunakan Exception untuk memberitahu bahwa kode yang kita buat itu terdapat error.
// PHPUnit mempunyai suatu fitur assertion untuk memastikan bahwa sebuah Exception harus terjadi
// jika Exception terjadi, maka unit test akan dianggap sukses sedangkan jika exception tidak terjadi, maka unit test akan dianggap gagal
// kita bisa menggunakan function Assert::expectException(NamaClassException::class) untuk memastikan bahwa sebuah unit test harus terjadi Exception seperti yang kita ekspektasikan

// Test Output
// Kadang-kadang kita ingin menguji sebuah fitur, namun fitur tersebut tidak mengembalikan data apapun (function yang tidak melakukan return data (void function)) misalnya fitur tersebut hanya melakukan echo saja
// untungnya PHPUnit memiliki fitur untuk mendeteksi output, dengan demikian kita dapat memastikan bahwa output yang di hasilkan sesuai dengan yang kita inginkan
// untuk melakukan itu, kita bisa menggunakan Assert::expectOutputString('output string yang di ekspektasikan')