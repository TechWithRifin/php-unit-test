<?php

namespace Php\Tests;

use PHPUnit\Framework\TestCase;

class ProductServiceMockTest extends TestCase
{

    private ProductRepository $repository;
    private ProductService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class); //secara otomatis membuat object dummy untuk repository property berdasarkan isi interface ProductRepository
        $this->service = new ProductService($this->repository);
        // dengan ini kita tidak perlu lagi membuat implementasi dari productRepository yang terhubung dengan database (kita hanya perlu membuat mockobject / object pengganti (penjelasan mudahnya disini kita tidak membuat object menggunakan class productRepository yang asli melainkan membuat object pengganti dari class productrepository dan hanya menyalin nama methods dan tipe data dari return methodsnya saja))

    }

    // test mock object
    public function testMock()
    {
        $product = new Product();
        $product->setId("1");
        $this->repository->expects($this->once())->method("findById")->willReturn($product); //mengkonfigurasi method findById supaya hanya bisa dipanggil sekali saja pada unit test ini (tidak boleh lebih ataupun kurang)
        $result = $this->repository->findById("1");
        // $result2 = $this->repository->findById("1"); //jika kode ini di uncoment maka akan menghasilkan error karena kita telah mengkonfigurasi pahwa method findById hanya boleh dipanggil satu kali
        $this->assertEquals($product, $result);
    }

    // test mockObject disertai configurasi
    public function testMockWithConfig()
    {
        $product = new Product();
        $product->setId("1");

        // mengkonfigurasi methods findById yang ada di productRepository supaya mereturn object product yang ada di dalam variable $product
        $this->repository->expects($this->once())->method("findById")->willReturn($product);
        $result = $this->repository->findById("1");
        $this->assertEquals($product, $product);
    }

    public function testMockMap()
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
        $this->repository->expects($this->exactly(2))->method("findById")->willReturnMap($map);

        $this->assertSame($product1, $this->repository->findById("1")); //jika kita findbyid dengan argument 1 maka kembaliannya harusnya object $product1 seperti yang sudah kita configurasi di map
        $this->assertSame($product2, $this->repository->findById("2")); //dan jika kita findById dengan argument 2 maka kembaliannya harusnya object $product2 seperti yang sudah kita configurasi di map
    }

    public function testStubWithCallback()
    {
        $this->repository->expects($this->atLeast(1))->method("findById")->willReturnCallback(function (string $id) { //jumlah parameter untuk callback function harus sama dengan jumlah parameter pada function findById (karena parameter tersebut akan menerima inputan argument saat function findById dipanggil)
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
        $this->repository->expects($this->once())->method("findById")->willreturn(null);
        $this->repository->expects($this->once())->method("save")->willReturnArgument(0); //saat method save dipanggil, maka method tersebut akan megembalikan value dari argument ke 0 dari method tersebut

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

        $this->repository->expects($this->once())->method("findById")->willReturn($productInDB);
        $this->repository->expects($this->never())->method("save");

        $product = new Product();
        $product->setId("1");

        $this->service->register($product);
    }

    public function testDeleteSuccess()
    {
        $product = new Product();
        $product->setId("1");

        $this->repository->expects($this->once())->method("findById")->with($this->equalTo("1"))->willReturn($product); //mengkonfigurasi function findById supaya hanya bisa dipanggil sekali saja pada unit test ini dan isi dari parameternya harus string bernilai '1' yang ada didalam variable $product 
        $this->repository->expects($this->once())->method("delete")->with($this->equalTo($product)); //mengkonfigurasi function delete pada class productRepository supaya hanya bisa dipanggil sekali saja (dengan ini kita dapat menyelesaikan permasalahan terkait pemanggilan function delete (lihat komentar pada function delete yang ada di dalam class ProductService)) pada konfigurasi ini juga mengatur isi dari parameter function delete harus object Product yang ada didalam variable $product 

        $this->service->delete("1");
        $this->assertTrue(true, "success delete");
    }

    public function testDeleteException()
    {
        $this->repository->expects($this->once())->method("findById")->with($this->equalTo("1"))->willReturn(null);
        $this->repository->expects($this->never())->method("delete"); //mengkonfigurasi function delete pada class productRepository supaya tidak akan pernah dipanggil pada unit test ini karena function delete pada class productService ini akan mengembalikan error sebelum function delete class productRepository dipanggil
        $this->expectException(\Exception::class);
        $this->service->delete("1");

        // untuk melihat perubahannya coba ubah kode didalam function delete yang ada didalam class ProductService.php (bagian $this->repository->delete() atau $this->repository->findById() -> duplikasi / hapus kode tersebut ataupun ubah isi argumentnya)
    }
}


/**
 * * Mock Object
 * PHPUnit memiliki fitur yang bernama Mock Object
 * Mock Object memiliki fungsi yang sama seperti stub (apapun yang dapat dilakukan oleh stub itu juga dapat dilakukan oleh mock object), hanya saja pada mock object, kita bisa melakukan verifikasi berapa kali suatu method itu dipanggil (ini bisa menyelesaikan kelemahan dari stub)
 * Mock object adalah improvement dari stub itu sendiri 
 * cara pembuatan mock object adalah dengan menggunakan function createMock(namaClassYangAkanDiGanti)
 */

/**
 * * Konfigurasi Mock Object (expects() method)
 * Konfirgurasi mock object mirim dengan stub yaitu nenggunakan function method(namaFunction)
 * Namun sebelum kita menggunakan function method(namaFunction), kita dapat menambahkan expect(invocationRule) sebagai ekspektasi berapa kali funtion dipanggil
 * Denga fitur expect, kita dapat menentukan function yang ditentukan didalam fitur method() hanya bisa dipanggil berapa kali pada sebuah unit test dan  jika misalnya function yang sudah ditentukan didalam method() ternyata dipanggil lebih dari yang kita ekspektasikan maka unit test ditanyatakan gagal.
 */

/**
 * * Invocation Rule
 * - any = function yang ditentukan dapat dipanggil berapa kalipun tanpa batasan
 * - never = function yang ditentukan haruslah tidak akan pernah dipanggil
 * - atLeast(number) = function yang ditentukan minimal harus dipanggil sebanyak number yang sudah ditentukan
 * atLeastOnce() = function yang ditentukan harus dipanggil minimal sekali
 * once() = function yang ditentukan harus dipanggil satu kali (tidak boleh kurang dan tidak boleh lebih)
 * axacly(number) = function yang ditentukan harus dipanggil sebanyak number yang sudah ditentukan
 * atMost(number) = function yang ditentukan maksimal dipanggil sebanyak number yang sudah ditentukan 
 */

/** 
 ** Memastikan Parameter Benar (with() method)
 * saat menggunakan mock object, kita dapat memastikan berapa kali function yang sudah kita tentukan itu dipanggil
 * Namun kalau parameter yang dikirim salah, maka secara otomatis unit test akan tetap dianggap sukses (karena didalam mock secara default tidak akan mengecek parameternya), padahal kita mengharapkan unit test tersebut akan gagal
 * Pada kasus ini,untungnya mock object juga mendukung verifikasi parameter untuk memastikan bahwa parameter yang diterima sebuah function yang kita tentukan itu benar
 * Untuk melakukan hal tersebut, kita bisa menggunakan method bernama with(..constraint) pada mock object
 * tambahkan constraint sejumlah parameter yang ada didalam function yang akan kita konfigurasi
 * semua constraint dapat kita lihat pada link
 * https://github.com/sebastianbergmann/phpunit/tree/master/src/Framework/Constraint
 * 
 * */

/**
 * *Mock Trait Dan Abtract Class*
 * function createMock(namaClassYangAkanDiGanti) hanya bisa digunakan untuk class dan interface saja. Jika kita butuh membuat mock object, namun dari data Trait atau Abstract Class, maka kita perlu membuatnya menggunakan function yang berbeda
 * Function getMockForTrait(namaTrait) untuk membuat mock object dari trait
 * Function getMockForAbtractClass(namaAbstractClass) untuk membuat mock object dari abtract class
 */