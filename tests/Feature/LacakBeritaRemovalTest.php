<?php

test('halaman lacak berita sudah tidak tersedia', function () {
    $this->get('/lacak')->assertNotFound();
});

test('tautan lacak berita tidak lagi tampil di halaman publik', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('Lacak Berita')
        ->assertDontSee('/lacak', false);
});
