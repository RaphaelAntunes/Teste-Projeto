<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->post('http://localhost:8000/criar-evento', [
            'title' => 'Exemplo de Título',
            'description' => 'Exemplo de Descrição',
            'start' => '2023-09-20',
            'end' => '2023-09-30',
            'usr_responsavel' => 'kainangabriel2019@gmail.com',
        ]);

        $response->assertStatus(201);
    }
}
