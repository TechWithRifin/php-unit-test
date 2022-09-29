<?php

namespace Php\Tests;

interface ProductRepository
{
    public function save(Product $product): Product; //returnnya stub baru dari product
    public function delete(?Product $product): void; //methods ini tidak akan melakukan apa apa
    public function findById(string $id): ?product; //methods ini akan mereturn null
    public function findAll(): array; //methods ini akan mereturn array kosong
}

// perhatian
// penjelasan diatas terjadi jika kita memesukkan class atau interface tersebut ke dalam argument method createStub()