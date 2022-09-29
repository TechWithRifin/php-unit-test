<?php

namespace Php\Tests;

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    // test dengan input secara manual
    public function testManual()
    { //test biasa tidak dapat diberi parameter
        $this->assertEquals(10, Math::sum([5, 5]));
        $this->assertEquals(20, Math::sum([4, 4, 4, 4, 4]));
        $this->assertEquals(9, Math::sum([3, 3, 3]));
        $this->assertEquals(0, Math::sum([]));
        $this->assertEquals(2, Math::sum([2]));
        $this->assertEquals(4, Math::sum([2, 2]));
    }

    // test dengan dataProvider dengan studi kasus yang sama

    /**
     * @dataProvider mathSumData
     */
    public function testDataProvider(array $values, int $expected)
    {
        $this->assertEquals($expected, Math::sum($values));
    }

    // berbeda dengan testManual yang menghitung jumlah test berdasarkan banyaknya function yang dibuat, saat kita mengimplementasikan dataProvider, jumlah test akan dihitung berdasarkan jumlah scenario input yang dibuat 

    // membuat function untuk data provider
    public function mathSumData(): array //return wajib array
    {
        /**
         * fotmat pengisian data provider
         * [value parameter untuk pertama, value untuk parameter kedua, dst],<-scenario input pertama
         * [value parameter pertama, value parameter kedua, dst],<-scenario input kedua
         * dst
         */
        return [
            [[5, 5], 10],
            [[4, 4, 4, 4, 4], 20],
            [[3, 3, 3], 9],
            [[], 0],
            [[2], 2],
            [[2, 2], 4]
        ];
    }

    // implementasi annotation @testWith
    // format pengisian data
    /**
     * [value parameter untuk pertama, value untuk parameter kedua, dst]<-scenario input pertama
     * [value parameter pertama, value parameter kedua, dst]<-scenario input kedua
     * dst
     */

    /**
     * @testWith [[5, 5], 10]
     *           [[4, 4, 4, 4, 4], 20]
     *           [[3, 3, 3], 9]
     *           [[], 0]
     *           [[2], 2]
     *           [[2, 2], 4]
     */
    public function testWith(array $values, int $expected)
    {
        $this->assertEquals($expected, Math::sum($values));
    }
}

// data provider
// dalam membuat unit test, sering kali mendapat kasus dimana kita ingin mengkombinasikan beberapa input data
// Sebenarnya hal ini bisa dilakukan secara manual satu persatu
// hanya saja akan terlalu banyak hal yang harus kita tulis misal kita ingin melakukan 4 atau lima test scenario maka kita harus copy paste kode yang sama lalu diganti input datanya (dengan melakukan ini, maka banyak duplikasi kode yang akan kita buat)
// untungnya PHPUnit mendukung fitur data provider, yang mana kita dapat membuat unit test dengan parameter dan data paramenternya itu disediakan (diprovide) dari function lain
// untuk melakukan ini, kita bisa menggunakan function annotation @dataProvider namaProviderFunction
// jadi nanti si providerfunction akan mengembalikan data parameter kemudian unit test kita akan mengambil data parameternya dari si dataprovider ini
// dataProvider sangat cocok untuk menangani input2 yang kompleks seperti object

// @testWith annotation
// saat menggunakan @dataProvider annotation, kita diharuskan untuk membuat function yang mengembalikan data untuk mengisi parameter2 dari testFunction
// terdapat cara lebih mudah tanpa harus membuat function terlebih dahulu, yaitu dengan menggunakan annotation @testWith
// Dengan menggunakan @testWith kita dapat mengisi data pada parameter2 yang ada pada function unit test tanpa harus membuat function yang mengembalikan data. Kita cukup memasukkan data dalam doc saja 
// format pengisian data sama dengan dataPrivider
// testWith annotation sangat cocok untuk menangani input2 sederhana seperti int, array, string dan boolean