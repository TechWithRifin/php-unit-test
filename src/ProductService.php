<?php

namespace Php\Tests;

class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function register(Product $product): Product
    {
        // memeriksa apakah data yang dimasukkan sudah ada apa belum
        if ($this->repository->findById($product->getId()) != null) {
            // jika data sudah ada maka akan mengembalikan exception
            throw new \Exception("product is already exists");
        }
        // jika data masih belum ada di database maka akan menyimpannya
        return $this->repository->save($product);
    }

    public function delete(string $id)
    {
        $product = $this->repository->findById($id);
        if ($product == null) {
            // $this->repository->delete($product);
            throw new \Exception("product is not found");
        }

        $this->repository->delete($product); //<-pada saat menggunakan stub, walaupun kita menghapus kode ini ataupun menduplikasikanya, unit test akan tetap dianggap sukses
        // $this->repository->delete($product);
    }
}