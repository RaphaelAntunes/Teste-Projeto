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
            'titulo' => 'Exemplo de Título',
            'descricao' => 'Exemplo de Descrição',
            'data_inicio' => '2023-09-20',
            'data_prazo' => '2023-09-30',
            'usr_responsavel' => 'kainangabriel2019@gmail.com',
        ]);

        $response->assertStatus(201);
    }
}
