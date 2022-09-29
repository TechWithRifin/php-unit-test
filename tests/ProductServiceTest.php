<?php

namespace Php\Tests;

use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{

    private ProductRepository $repository;
    private ProductService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createStub(ProductRepository::class); //secara otomatis membuat object dummy untuk repository property berdasarkan isi interface ProductRepository
        $this->service = new ProductService($this->repository);
        // dengan ini kita tidak perlu lagi membuat implementasi dari productRepository yang terhubung dengan database (kita hanya perlu membuat stab / object pengganti (penjelasan mudahnya disini kita tidak membuat object menggunakan class productRepository yang asli melainkan membuat object pengganti dari class productrepository dan hanya menyalin nama methods dan tipe data dari return methodsnya saja))

    }

    // test stub
    public function testStub()
    {
        $result = $this->repository->findById("1");
        $this->assertEquals(null, $result);
    }

    // test stub disertai configurasi
    public function testStubWithConfig()
    {
        $product = new Product();
        $product->setId("1");

        // mengkonfigurasi methods findById yang ada di productRepository supaya mereturn object product yang ada di dalam variable $product
        $this->repository->method("findById")->willReturn($product);
        $result = $this->repository->findById("1");
        $this->assertEquals($product, $product);
    }

    public function testStubMap()
    {
        $product1 = new Product();
        $product1->setId("1");

        $product2 = new Product();
        $product2->setId("2");

        /**
         * format penulisan map adalah
         * [
         *   [valueParameter, returnValue] <- skenario input pertama
         *   [valueParameter1,valueparameter2, returvalue] <- jika parameter method lebih dari 1 (skenario input kedua)
         * [dst]
         * ]
         */
        $map = [
            ["1", $product1],
            ["2", $product2]
        ];
        $this->repository->method("findById")->willReturnMap($map);

        $this->assertSame($product1, $this->repository->findById("1")); //jika kita findbyid dengan argument 1 maka kembaliannya harusnya object $product1 seperti yang sudah kita configurasi di map
        $this->assertSame($product2, $this->repository->findById("2")); //dan jika kita findById dengan argument 2 maka kembaliannya harusnya object $product2 seperti yang sudah kita configurasi di map
    }

    public function testStubWithCallback()
    {
        $this->repository->method("findById")->willReturnCallback(function (string $id) { //jumlah parameter untuk callback function harus sama dengan jumlah parameter pada function findById (karena parameter tersebut akan menerima inputan argument saat function findById dipanggil)
            $product = new Product();
            $product->setId($id); //memasukkan id yang di masukkan pada argument findById kedalam properti id object product
            return $product; //jika method findById dipanggil,method tersebut akan mengembalikan object product yang ada di dalam variabel $product
        });

        $this->assertEquals("1", $this->repository->findById("1")->getId()); //nilai dari property id harusnya bernilai 1 karena kita menginputkan nilai 1 pada argument findById
        $this->assertEquals("2", $this->repository->findById("2")->getId());
        $this->assertEquals("3", $this->repository->findById("3")->getId());
    }

    public function testRegisterSuccess()
    {
        $this->repository->method("findById")->willreturn(null);
        $this->repository->method("save")->willReturnArgument(0); //saat method save dipanggil, maka method tersebut akan megembalikan value dari argument ke 0 dari method tersebut

        $product = new Product();
        $product->setId("1");
        $product->setName("contoh");

        $result = $this->service->register($product);
        $this->assertEquals($product->getId(), $result->getId());
        $this->assertEquals($product->getName(), $result->getName());
    }

    public function testRegisterException()
    {
        $this->expectException(\Exception::class);

        $productInDB = new Product();
        $productInDB->setId("1");

        $this->repository->method("findById")->willReturn($productInDB);

        $product = new Product();
        $product->setId("1");

        $this->service->register($product);
    }

    public function testDeleteSuccess()
    {
        $product = new Product();
        $product->setId("1");

        $this->repository->method("findById")->willReturn($product);

        $this->service->delete("1");
        $this->assertTrue(true, "success delete");
    }

    public function testDeleteException()
    {
        $this->expectException(\Exception::class);
        $this->repository->method("findById")->willReturn(null);
        $this->service->delete("1");
    }
}

/**
 * Permasalahan dalam membuat unit test
 * kadang saat kita membuat unit test untuk sebuah class, kita mengalami suatu permasalahan yaitu class yang akan kita uji tersebut tergantung dengan object class lain (class yang kita butuhkan membutuhkan class lain untuk berjalan misalnya class service membutuhkan class repository agar dapat berjalan).
 * apalagi jika ternyata class (object) yang dibutuhkan tersebut juga bergantung dengan class (object) lain lagi (misal class repository membutuhkan class model)
 * hal tersebut akan sangat menyulitkan saat membuat unit test karena harus membuat object dari semua class yang dibutuhkan tersebut
 * semakin komplex relasi antar objectnya semakin susah kita mau membuat unit testnya
 */

/**
 * Stub
 * pada saat kita membuat unit test untuk sebuah class, dan ternyata class tersebut membutuhkan dependency (ketergantungan) dengan object(class) lain, supaya tidak menyulitkan kita maka kita dapat membuat object pengganti untuk class yang dibutuhkan tersebut. object pengganti ini dapat kita konfigursi agar sesuai dengan keinginan kita
 * teknik ini dinamakan stubbing, dan object pengganti yang akan kita buat disebut stub
 * PHPUnit mendukung pembuatan object stub secara mudah hanya dengan menggunakan function createStub(namaClassYangAkanDiGanti) yang terdapat di dalam class TestCase (isi argument dari createStub dapat berupa class ataupun interface)
 * method createStub() secara otomatis akan membuat object dari class atau interface yang kita inginkan dengan default implementation (misal sebuah function yang akan mengembalikan data berupa array maka object stub sersebut akan mengembalikan array kosong secara default)
 */

/**
 * konfigurasi stub
 * secara default, semua function di dalam stub (object pengganti) akan menghasilkan null jika nullable return type nya dan default return value jika tipe data returnnya primitive atau stub baru jika tipe data returnnya class (lebih jelasnya lihat class productRepository.php)
 * biasanya kita ingin melakukan konfigurasi stub terlebih dahulu sebelum kita menggunakan stub nya.
 * dengan melakukan hal tersebut, kita bisa memprediksi tingkah laku dari stub tersebut akan sesuai dengan konfigurasi yang kita buat
 * Melakukan konfigurasi stub sederhananya hanya memberi tahu stub, jika sebuah function di eksekusi, maka kita ingin mengembalikan data apa?
 * jadi gampangnya kita ingin mengkonfigurasi isi dari object stub (misal nanti methods findbyid dengan argument bernilai 1 di panggil maka kita ingin mengembalikan value 'a' sedangkan kalau method findbyid dengan argument bernilai 2 dipanggil, maka kita ingin mengembalikan value null)
 */

/**
 * Invocation Stubber
 * Stub memiliki function yang bernama method('namaFunctionYangAkanDikonfigurasi'), yang mana dia akan mengembalikan invocationStubber
 * dengan invocationStubber, kita bisa mengatur hasil kembalian dari function yang dipanggil di stub
 * untuk lebih detailnya kita bisa lihat di link
 * https://github.com/sebastianbergmann/phpunit/phpunit/blob/master/src/Framework/MockObject/Builder/InvocationStubber.php
 */

/**
 * integrasi dengan stub
 * sekarang kita sudah tahu cara menggunakan stub
 * jika kita akan membuat unit testuntuk sebuah class, dan ternyata class yang akan kita uji tersebut membutuhkan object lain, maka disarankan untuk menggunakan stub
 * hal ini supaya tingkah laku objectnya bisa kita konfigurasi. Sehingga perubahan yang terjadi di class aslinya tidak akan mempengaruhi unit test kita (misalnya jika kita hanya ingin menguji class productService dan ternyata class productrepository terjadi perubahan database dari mysql ke postgresql. Dengan menggunakan stub, kita tidak perlu takut kode testnya akan terjadi error)
 * juga jika kita menggunakan stub, data didalam database kita tidak akan berubah pada saat kita menjalankan test pada class productRepository
 */

/**
 * Masalah dengan stub
 * Stub sangat cocok sekalik ketika kita butuh membuat object dependency untuk class yang sedang kita test
 * namun ada satu masalah yang terdapat di stub, yaitu kita tidak bisa mengetahui berapa kali interaksi terjadi terhadap object stub tersebut  
 */

/**
 * Kesalahan pada unit test
 * lihat pada function testDeleteSuccess dan testDeleteException
 * sekilas, unit test yang kita buat tersebut tidak ada masalah
 * tapi coba jika kita ubah class ProductService nya
 * Misalnya kita melakukan pemanggilan method deletenya sebanyak lebih dari satu kali
 * atau bahkan kita hapus kode  $this->repository deletenya (lebih jelasnya lihat function delete pada class productservice)
 * Hasilnya, semua unit test akan tetap sukses dan artinya terdaoat kesalahan dalam unit test kita
 * inilah kelemahan dari stub (daripada menggunakan stub, lebih baik menggunakan mock object)
 */